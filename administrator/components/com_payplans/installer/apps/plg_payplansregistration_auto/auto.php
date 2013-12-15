<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @contact		shyam@joomlaxi.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Payplans Registration Plugin
 *
 * @package		Payplans
 * @subpackage	Plugin
 */
class  plgPayplansregistrationAuto extends XiPluginRegistration
{
	protected $_registrationUrl = 'index.php?option=com_payplans&view=plan&task=login';

	function _isRegistrationUrl()
	{
		$vars = $this->_getVars();
		if($vars['option'] == 'com_payplans' && $vars['view'] == 'plan' && $vars['task'] == 'login'){
			return true;
		}
		
		return false;
	}
	
	function _isRegistrationCompleteUrl()
	{
		return true;
	}
	
	/** 
	 * @see XiPluginRegistration::_doStartRegistration()
	 * 
	 */
	protected function _doStartRegistration()
	{
		$planId = $this->_getPlan();

		$email 	  = JRequest::getVar('payplansRegisterAutoEmail', false);
		$username = JRequest::getVar('payplansRegisterAutoUsername', false);
		$password = JRequest::getVar('payplansRegisterAutoPassword', false);
                
		
		// if $username is not post then redirect to login page again
		if(!$username){
			$this->_app->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=login&plan_id='.$planId));
		}
		
		// if email is not post then redirect to login page again
		if(!$email){
			$this->_app->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=login&plan_id='.$planId));
		}
		
		// if password is not post then redirect to login page again
		if(!$password || JString::strlen(Jstring::trim($password)) === 0) {
			$this->_app->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=login&plan_id='.$planId));
		}

        //if captcha code not matched then redirect to login page again
		if($this->params->get('show_captcha', 0)){
			$post       = JRequest::get('post');      
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res        = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);
			if(isset($res[0]) && !$res[0]){
				$this->_app->enqueueMessage(XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_REGISTRATION_SAVE_FAILED_16'));	
				$this->_app->redirect(XiRoute::_('index.php?option=com_payplans&view=plan&task=login&plan_id='.$planId));
            }
		 }
 
		
		$userId = $this->_autoRegister($username, $email, $password);
		
		if($userId){
			// registration is completed here so call afterRegistrationComplete
			$this->_setUser($userId);
			return $this->_doCompleteRegistration();
		}
		
		return true;
	}
	
	function _autoRegister($username, $email, $password)
	{
		return $this->_autoRegister16($username, $email, $password);
	}
	
	function _autoRegister16($username, $email, $password)
	{
		require_once  JPATH_ROOT.DS.'components'.DS.PAYPLANS_COM_USER.DS.'models'.DS.'registration.php';
		
		$model = new UsersModelRegistration();
		JFactory::getLanguage()->load(PAYPLANS_COM_USER);
		
		jimport('joomla.mail.helper');
		if(!JMailHelper::isEmailAddress($email)){
			$this->_app->enqueueMessage(XiText::_('COM_PAYPLANS_INVALID_EMAIL_ADDRESS'));
			return false;
		}
		
		if(PayplansHelperUser::exists('email', $email)){
			$this->_app->enqueueMessage(XiText::_('COM_PAYPLANS_EMAIL_ALREADY_REGISTERED'));
			return false;
		}
		
		if(PayplansHelperUser::exists('username', $username)){
			$this->_app->enqueueMessage(XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_USERNAME_ALREADY_REGISTERED'));
			return false;
		}
		
		// load user helper
		jimport('joomla.user.helper');
//		$password = JUserHelper::genRandomPassword();
		$temp = array(	'username'=>$username,'name'=>$username,'email1'=>$email,
						'password1'=>$password, 'password2'=>$password, 'block'=>0 );
				
		$config = JFactory::getConfig();
		$params = JComponentHelper::getParams('com_users');

		// Initialise the table with JUser.
		$user = new JUser;
		
		$data = (array)$model->getData();
		// Merge in the registration data.
		foreach ($temp as $k => $v) {
			$data[$k] = $v;
		}

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];
		
		
//		$useractivation = $params->get('useractivation');

		// if acc_verification is not set to never_acc_creation, then user activation is required
		$useractivation = $this->_isEmailVerificationRequired() ;
		$useractivation = $useractivation
							|| $this->params->get('acc_verification', 'always_email') == 'never_sub_active';							
		
		// Check if the user needs to activate their account.
		if ($useractivation) {
			jimport('joomla.user.helper');
			$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
			$data['block'] = 1;
		}

		// Bind the data.
		if (!$user->bind($data)) {
			$this->_app->enqueueMessage(XiText::sprintf('PLG_PAYPLANSREGISTRATION_AUTO_BIND_FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save()) {
			$this->_app->enqueueMessage(XiText::sprintf('PLG_PAYPLANSREGISTRATION_AUTO_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}

		// Compile the notification mail values.
		$data = $user->getProperties();
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::base();

		if ($this->_isEmailVerificationRequired())
		{
			// Set the link to activate the user account.
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

			$emailSubject	= XiText::sprintf(
				'PLG_PAYPLANSREGISTRATION_AUTO_ACCOUNT_DETAILS_FOR',
				$data['name'],
				$data['sitename']
			);

			$emailBody = XiText::sprintf(
				'PLG_PAYPLANSREGISTRATION_AUTO_SEND_MSG_ACTIVATE',
				$data['name'],
				$data['sitename'],
				$data['siteurl'].'index.php?option=com_payplans&view=user&task=activate_user&activation='.$data['activation'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
			
			if($this->params->get('acc_verification', 'always_email') == 'manual_acc_active'){
				$emailBody = XiText::sprintf(
							'PLG_PAYPLANSREGISTRATION_AUTO_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
							$data['name'],
							$data['sitename'],
							$data['siteurl'].'index.php?option=com_payplans&view=user&task=activate_user&activation='.$data['activation'],
							$data['siteurl'],
							$data['username'],
							$data['password_clear']
				);
			}
		} else {

			$emailSubject	= XiText::sprintf(
				'PLG_PAYPLANSREGISTRATION_AUTO_ACCOUNT_DETAILS_FOR',
				$data['name'],
				$data['sitename']
			);

			$emailBody = XiText::sprintf(
				'PLG_PAYPLANSREGISTRATION_AUTO_SEND_MSG',
				$data['name'],
				$data['sitename'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
		}

		// Send the registration email.
		$return = JFactory::getMailer()->setSender( array(
														$data['mailfrom'],
														$data['fromname']
													   ))
									   ->addRecipient($data['email'])
									   ->setSubject($emailSubject)
									   ->setBody($emailBody)
									   ->Send();
		
		// 	Send notification to all administrators
		$subject2 = sprintf ( XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_ACCOUNT_DETAILS_FOR' ), $data['name'], $data['sitename']);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		$rows = XiHelperJoomla::getUsersToSendSystemEmail();
		foreach ( $rows as $row ){		
			$message2 = sprintf ( JText::_( 'PLG_PAYPLANSREGISTRATION_AUTO_SEND_MSG_ADMIN' ), $row->name, $data['sitename'], $data['name'], $email, $username);
			$message2 = html_entity_decode($message2, ENT_QUOTES);
			$mail     = JFactory::getMailer()->setSender( array(
														$data['mailfrom'],
														$data['fromname']
													   ))
										     ->addRecipient($row->email)
									         ->setSubject($subject2)
									         ->setBody($message2)
									         ->Send();	
		}
		
		// Check for an error.
		if ($return !== true) {
			$this->_app->enqueueMessage(XiText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'), 'error');

			// Send a system message to administrators receiving system mails
			if (count($rows) > 0) {
				$db		= XiFactory::getDBO();
				$jdate = new XiDate();
				// Build the query to add the messages
				$q = "INSERT INTO `#__messages` (`user_id_from`, `user_id_to`, `date_time`, `subject`, `message`)
					VALUES ";
				$messages = array();
				foreach ($rows as $emailUser) {
					$messages[] = "(".$emailUser->id.", ".$emailUser->id.",".$db->quote($jdate->toMySQL()).",".$db->quote(JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')).",".$db->quote(JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])).")";
				}
				$q .= implode(',', $messages);
				$db->setQuery($q);
				$db->query();
			}
			return $user->id;
		}
		
		// Show what will happen to registration
		$this->_app->enqueueMessage(XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_'.JString::strtoupper($this->params->get('acc_verification', 'always_email'))));
		
		// how to find user id 
		return $user->id;
	}
	
	function _isEmailVerificationRequired()
	{
		// get the plan selected
		$isFreePlan = false;
		$plan = PayplansPlan::getInstance($this->_getPlan());
		if(floatval($plan->getPrice()) == floatval(0)){
			$isFreePlan = true;
		}
	
		return $this->params->get('acc_verification', 'always_email') == 'always_email' || ($this->params->get('acc_verification', 'always_email') == 'manual_acc_active')
					|| ($this->params->get('acc_verification', 'freeplan_email') == 'freeplan_email' && $isFreePlan);
	}
	
	function onPayplansSubscriptionAfterSave($previous, $current)
	{
		if($this->params->get('acc_verification', 'always_email') != 'never_sub_active'){
			return true;
		}
		
		if($current->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE){
			$userid = $current->getBuyer();
			$user = XiFactory::getUser($userid);
			if($user->get('block') == true){
				$user->set('block', 0);
				$user->set('activation', '');
				$user->save();
			}
		}
		
		return true;
	}
	function onAfterRoute()
	{

		if(isset(XiFactory::getConfig()->registrationType) && $this->_name != XiFactory::getConfig()->registrationType){
             return true;
        }
		// call parent's onAfterRoute
		parent::onAfterRoute(); 
		
	
		$vars = $this->_getVars(array('option','view', 'task', 'activation'));
		//if url is not same as given in the activation link then return
        if($vars['option'] != 'com_payplans' || $vars['view'] != 'user' || $vars['task'] != 'activate_user'){
			return;
		}
			
		$mainframe = XiFactory::getApplication();
		// Initialize some variables
		$db			= XiFactory::getDBO();
		$user 		= XiFactory::getUser();
		$document   = XiFactory::getDocument();

	   	$usersConfig = JComponentHelper::getParams( 'com_users' );
	   	$userActivation = $usersConfig->get('useractivation');
		$approved = JRequest::getVar('approved', 0);

		// Check to see if they're logged in, because they don't need activation!
		if (($approved != 'byAdmin') && $user->get('id')) {
			// They're already logged in, so redirect them to the home page
			$mainframe->redirect( 'index.php' );
		}
	
		$activationRedirectUrl = $this->params->get('activation_redirect_url', 'index.php?option=com_payplans&view=dashboard');
		$siteURL	 = JURI::base();
		$activationRedirectUrl = str_replace($siteURL,'',$activationRedirectUrl);
		
		$returl = XiRoute::_($activationRedirectUrl);
		
		// Do we even have an activation string?
		$activation = JRequest::getVar('activation', '', '', 'alnum' );
		$activation = $db->escape( $activation );
		
		if (empty( $activation )){
			$message = XiText::_( 'PLG_PAYPLANSREGISTRATION_AUTO_REG_ACTIVATE_NOT_FOUND' );
		    XiFactory::getApplication()->redirect($returl,$message); 
			return;
		}
			
		// get the user instance which admin is going to activate as per activation token	
		$user = $this->_getUserFromActivation($activation);

		//send email to admin for activation request
		if(($this->params->get('acc_verification', 'always_email') == 'manual_acc_active') && !$approved){
			$this->_sendApprovalEmail($user, $activation, true);
			$message = XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_REGISTRATION_VERIFY_SUCCESS');
			PayplansFactory::getApplication()->redirect($returl,$message); 
			return true;
		}
		
		// Now activate this user
		jimport('joomla.user.helper');
		$message = XiText::_( 'PLG_PAYPLANSREGISTRATION_AUTO_REG_ACTIVATE_NOT_FOUND' );
		if (JUserHelper::activateUser($activation)){
			$message = XiText::_( 'PLG_PAYPLANSREGISTRATION_AUTO_REG_ACTIVATE_COMPLETE' );
			if($approved == 'byAdmin'){
				$message = XiText::_('PLG_PAYPLANSREGISTRATION_AUTO_REGISTRATION_ADMINACTIVATE_SUCCESS');
				$this->_sendApprovalEmail($user, $activation, false);
			}
		}

		// redirect user to $returl and display $message
		PayplansFactory::getApplication()->redirect($returl,$message); 		
	}
	
	function _sendApprovalEmail($user, $activation, $isAdmin = false)
	{
		$mainframe = PayplansFactory::getApplication();

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= JComponentHelper::getParams( 'com_users' );
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();

		$subject 	= XiText::sprintf( 'PLG_PAYPLANSREGISTRATION_AUTO_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT', $name, $sitename);
		$subject 	= html_entity_decode($subject, ENT_QUOTES);
			
		$message    = XiText::sprintf('PLG_PAYPLANSREGISTRATION_AUTO_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
										$sitename,
										$name,
										$email,
										$username,
										$siteURL.'index.php?option=com_payplans&view=user&task=activate_user&activation='.$activation.'&approved=byAdmin');

		$message = html_entity_decode($message, ENT_QUOTES);

		//get all super administrator
		$rows = XiHelperJoomla::getUsersToSendSystemEmail();

		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}
		$mail = new JMail();
		if($isAdmin == false){
			//send email to users, notifying that their account has been activated by admin
			$subject = XiText::sprintf('PLG_PAYPLANSREGISTRATION_AUTO_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT', $username, $sitename);
			$message = XiText::sprintf('PLG_PAYPLANSREGISTRATION_AUTO_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY', $name, $sitename, $username);
			$mail->sendMail($mailfrom, $fromname, $email, $subject, $message);
			return true;
		}

		// send email to admin for activation of user's account 
		foreach ($rows as $row){
			if($row->sendEmail){
				$mail->sendMail($mailfrom, $fromname, $row->email, $subject, $message);
			}
		}
	}
	
	function _getUserFromActivation($activation)
	{
		$db	= PayplansFactory::getDBO();
		$query = new XiQuery();
		$userId = $query->select('id')
					    ->from('#__users')
					    ->where('activation = "'.$activation.'"')
					    ->where('block = 1')
		 			    ->where('lastvisitDate = "'.$db->getNullDate().'"')
		 			    ->dbLoadQuery()
		 			    ->loadResult();
		
		// Check for a valid user id.
		if (!$userId) {
			$this->setError(JText::_('COM_USERS_ACTIVATION_TOKEN_NOT_FOUND'));
			return false;
		}

		$user = PayplansFactory::getUser($userId);
		return $user;
	}
	
}

