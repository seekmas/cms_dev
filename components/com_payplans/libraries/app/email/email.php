<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class  PayplansAppEmail extends PayplansApp
{
	protected $_location	= __FILE__;
	protected $_mailer		= null;
	
	public function isApplicable($refObject = null, $eventName='')
	{
		// if not with reference to payment then return
		if($eventName === 'onPayplansCron' || $eventName == 'getTemplatedata'){
			return true;
		}
		
		return parent::isApplicable($refObject, $eventName);
	}
	
	public function collectAppParams(array $data)
	{
		// encode editor content
		if(isset($data['app_params']) && isset($data['app_params']['content'])){
			$data['app_params']['content'] = base64_encode($data['app_params']['content']);
		}

		return parent::collectAppParams($data);
	}

	// Do not send false
	public function onPayplansOrderAfterSave($prev, $new)
	{
		return $this->_triggerEmail($prev,$new);
	}

	public function onPayplansInvoiceAfterSave($prev, $new)
	{
		return $this->_triggerEmail($prev,$new);
	}

	public function onPayplansSubscriptionAfterSave($prev, $new)
	{
		return $this->_triggerEmail($prev,$new);
	}


	protected function _triggerEmail($prev, $new)
	{
		// we need to send pre-expiry email
		if($this->getAppParam('when_to_email') == 'on_preexpiry'){
			return true;
		}
		
		// 	we need to send post-expiry email
		if($this->getAppParam('when_to_email') == 'on_postexpiry'){
			return true;
		}
		
		//we need to send post-activation email
		if($this->getAppParam('when_to_email') == 'on_postactivation'){
			return true;
		}
		
		if($this->getAppParam('when_to_email') == 'on_cart_abondonment'){
			return true;
		}
		
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			return true;
		}

		// check the status
		if($new->getStatus() != $this->getAppParam('on_status', PayplansStatus::NONE)){
			return true;
		}

		// now try to findout the data and process the email
		$this->_sendEmail($new);

		//
		return true;
	}

	protected function _sendEmail($object)
	{
		// V. Imp. always get it, else everyone added to list will get emails.
		$this->_mailer = XiFactory::getMailer();

		// object is of payment/subscription/order type
		$userId = $object->getBuyer();

		//$mail->setSender(array($from, $fromname));
		//$mail->addAttachment($attachment);

		$subject = $this->getAppParam('subject', '');
		$subject = $this->_replaceToken($subject, $object);
		$this->_mailer->setSubject($subject);

		$this->_mailer->addRecipient(XiFactory::getUser($userId)->email);

		$this->_addEmailAddress($this->getAppParam('send_cc', ''),'addCC');
		$this->_addEmailAddress($this->getAppParam('send_bcc', ''),'addBCC');
		$attachment = $this->getAppParam('attachment','');
		if(!empty($attachment) && $attachment !=-1)
		{
        	$this->_mailer->addAttachment(JPATH_ROOT.DS.'media'.DS.'payplans'.DS.'app'.DS.'email'.DS.$attachment);
		}
		
		$sendInvoice = $this->getAppParam('send_invoice', 0);
		if(($sendInvoice == true)){
			$pdfInvoice = $this->_attachInvoice($object);
		}
		
		// restrict to send email in unit test case
		// in selenium test case mail will not be send because mail setting are not correct
		if(defined('PAYPLANS_UNIT_TEST_MODE')===true){
			return true;
		}
		$template = $this->getAppParam('email_template','custom');
		
		if($template == 'custom'){
			$body = base64_decode($this->getAppParam('content', ''));
			$body = $this->_replaceToken($body, $object);
			$this->_mailer->setBody($body);
	
			$htmlFormat = $this->getAppParam('html_format', 1);
			$this->_mailer->IsHTML($htmlFormat);
	
			//in case of text email format, remove all html and php tags from message content
			if(!$htmlFormat){
				$body = strip_tags($body);
				$this->_mailer->setBody($body);
			}
		}
		else{
			$email_template = $this->getAppParam('choose_template');
			$body = $this->_render($email_template);
			$body = $this->_replaceToken($body, $object);
			$this->_mailer->setBody($body);	
			$this->_mailer->IsHTML(true);	
		}

		$content = array( 'user_id'=>$userId, 'subject'=>$subject, 'body'=>$body);
		$emailSend = true;
		//XITODO : we need to apply it everywhere
		if($this->_mailer->Send()){
			$emailSend = true;
			$message=XiText::_('COM_PAYPLANS_EMAIL_SEND_SUCCESSFULLY');
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $this, $content);
		}else
		{
			$emailSend = false;
			$message=XiText::_('COM_PAYPLANS_EMAIL_SENDING_FAILED');
			$content['current'] = $this->toArray();
			PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $this, $content);
		}
		
		//delete the file created for attachment after sending email
		if(isset($pdfInvoice) && ($pdfInvoice instanceof plgPayplansPdfinvoice)){
			$pdfInvoice->deleteUserFiles($object->getBuyer());
			JFactory::getSession()->set('pdfinvoice_lock',JFactory::getSession()->get('pdfinvoice_lock',0)-1);
		}
			
		return $emailSend;
	}

	protected function _attachInvoice($object)
	{
		$invoiceObject = $object;
		
		//get invoice from order
		if($object instanceof PayplansOrder){
			$invoiceObject = array_pop($object->getInvoices());
		}
		
		//get the invoice object from subscription
		if($object instanceof PayplansSubscription){
			$invoiceObject = array_pop($object->getOrder(PAYPLANS_INSTANCE_REQUIRE)->getInvoices());
		}
		
		if(!($invoiceObject instanceof PayplansInvoice)){
			return true;
		}
		
		$plgEnable = XiHelperPlugin::getStatus('pdfinvoice','payplans');

		//work only when plugin is enable
		if($plgEnable == true){
			//get instance of pdfinvoice plugin
			$pluginInst = XiHelperPlugin::getPluginInstance('payplans', 'pdfinvoice');
			
			JFactory::getSession()->set('pdfinvoice_lock',JFactory::getSession()->get('pdfinvoice_lock',0)+1);
			
			$pdfObject = $pluginInst->doSiteAction($invoiceObject->getKey());
			$pluginInst->createFolder($pdfObject, $invoiceObject->getKey(), $object->getBuyer());

			$filePath = XiHelperJoomla::getPluginPath($pluginInst).DS.'pdfinvoices'.$object->getBuyer();
			$filename = 'invoice'.$invoiceObject->getKey().'.pdf';
			
			//check whether file exists or not
			if(file_exists($filePath.DS.$filename)){
				$this->_mailer->addAttachment($filePath.DS.$filename);
			}
			
			return $pluginInst;
		}
		
		return false;
	}

	public function _replaceToken($content, $object)
	{
		return PayplansFactory::getRewriter()->rewrite($content, $object);
	}

	/**
	 * Add given emails to TO/CC/BCC
	 *
	 * @param unknown_type $str Emails in Comma Seperated format
	 * @param unknown_type $function addRecipient / addCC / addBCC
	 */
	public function _addEmailAddress($str, $function='addRecipient')
	{
		// string is empty
		if(isset($str)==false || empty($str)){
			return false;
		}

		// explode and add one by one
		$emails = explode(',', $str);
		$count = 0;
		foreach($emails as $email){
			// no need to get mailer, as we have just added it in sendEmail
			$this->_mailer->$function($email);
			$count++;
		}

		return $count;
	}
	
	public function onPayplansCron()
	{
		$sentmail = 0;
		$subscriptions = array();

		$plans = $this->getPlans();
		
		$onAllPlan = ($this->getParam('applyAll',false) == true) ? true : false;
		if($onAllPlan == false && empty($plans))
		{
				return false;
		}
		
		//get the parameter when to email to check whether 
		//pre -expiry or post expiry email is to be send
		$whenToEmail = $this->getAppParam('when_to_email', 'on_status');
		$expiry  = $this->getAppParam($whenToEmail);
	
		if($whenToEmail == 'on_preexpiry'){
			$event = 'preExpiry';
				$subscriptions  = XiFactory::getInstance('subscription','model')->getPreExpirySubscriptions($plans, $expiry, $onAllPlan);
				
				//check for each subscription: if it is of recurring type and 
				//email is allowed to send only for last cycle of recurring then
				//unset the subscription which is not the last subscription of recurring cycle
				foreach ($subscriptions as $sub_id => $sub){
					$subscription  = PayplansSubscription::getInstance($sub_id, null, $sub);
					$recurringType = $subscription->isRecurring();

					if($recurringType && $this->getAppParam('on_lastcycle')){
						$order            = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
						$recurrence_count = $subscription->getRecurrenceCount();
						
						if( $recurringType == PAYPLANS_RECURRING_TRIAL_1 ){
							$recurrence_count += 1;
						}
						
						if( $recurringType == PAYPLANS_RECURRING_TRIAL_2 ){
							$recurrence_count += 2;
						}
						
						$invoiceCount = $order->getInvoices(PayplansStatus::INVOICE_PAID) + $order->getInvoices(PayplansStatus::INVOICE_REFUNDED);
						if( count($invoiceCount) != $recurrence_count ){
							unset($subscriptions[$sub_id]);
						}
					}
				}
		}
		
		if($whenToEmail == 'on_postexpiry'){
			$event = 'postExpiry';
			$subscriptions = XiFactory::getInstance('subscription','model')->getPostExpirySubscriptions($plans, $expiry, $onAllPlan);
		}
		
		if($whenToEmail == 'on_postactivation'){
			$event = 'postActivation';
			$subscriptions = XiFactory::getInstance('subscription','model')->getPostActivationSubscriptions($plans, $expiry, $onAllPlan);
		}
		if($whenToEmail == 'on_cart_abondonment'){
		        $invoices = $this->getAbondonedInvoices($expiry);

			if(count($invoices)>0){
		        $event = 'oncartabondonment';
				foreach($invoices as $invoice_id => $invoice){
					$invoiceInstance = PayplansInvoice::getInstance($invoice_id, null, $invoice);
				
					// if mail is not send 
					if($invoiceInstance->getParams()->get($event.$expiry,false) == false)
					{
						$this->_sendEmail($invoiceInstance);
						$invoiceInstance->getParams()->set($event.$expiry,true);
						$invoiceInstance->save();
						
						$sentmail++;
					}
				}
			}
		}
		
		if(count($subscriptions)>0){
			foreach($subscriptions as $sub_id => $sub){
				$subscription = PayplansSubscription::getInstance($sub_id, null, $sub);
				
				// if current preexpiry was not applied
				if($subscription->getParams()->get($event.$expiry,false) == false){
					$this->_sendEmail($subscription);
					//mark that we have triggered preexpiry event
					//XITODO : it should be an event so other app can work on it
					$subscription->getParams()->set($event.$expiry,true);
					$subscription->save();
					
					$sentmail++;
				}
			}
		}
		
		return $sentmail;
	}
	
	public function getAbondonedInvoices($expiry)
	{
		$e1 = new XiDate(XiFactory::getConfig()->cronAcessTime);
		$e2	= new XiDate('now');
		$e1->subtractExpiration($expiry);
		$e2->subtractExpiration($expiry);
		$query = new XiQuery();
		$invoices = $query->select('*')
							   ->from('`#__payplans_invoice`')
							   ->where('`status` = '.PayplansStatus::INVOICE_CONFIRMED)
							   ->where("`created_date` > '".$e1->toMySQL()."' AND `created_date` < '".$e2->toMySQL()."'")
							   ->dbLoadQuery()
							   ->loadObjectList('invoice_id');
		return $invoices;
	}
	
	public function getTemplatedata()
	{
		$template = JFactory::getApplication()->input->get('template');
		
		$templatePath = dirname(__FILE__).DS.'tmpl'.DS.$template.'.php';
		
		echo file_get_contents($templatePath);
		echo "<html><br><br><div style='font-size:0.7em; color: #888888; font-family: 'Verdana';>".sprintf(XiText::_('COM_PAYPLANS_APP_EMAIL_TEMPLATE_CHANGE_PATH'),$templatePath)."</html>";
		exit();
	}

}


class PayplansAppEmailFormatter extends PayplansAppFormatter
{
	// get Ignore data 
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_tplVars', '_mailer', '_location', '_errors', '_component');
		return $ignore;
	}
	
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppEmailFormatter',
										       'function' => 'getFormattedContent'));
		return $rules;
	}

	// format email app content,status, expiration time 
	function getFormattedContent($key, $value, $data)
	{
		$params = PayplansHelperParam::iniToArray($value);
		foreach ($params as $param=>$v)
		{
			if($param == 'content'){
				$params[$param] = base64_decode($v);
			}
			if($param == 'on_status'){
				$params[$param] = PayplansStatus::getName($v);
			}
			if(($param == 'on_preexpiry' || $param == 'on_postexpiry' || $param == 'on_postactivation')
				 && !empty($v)){
				$rawTime = PayplansHelperPlan::convertIntoTimeArray($v);
				$params[$param] =  PayplansHelperFormat::planTime($rawTime);
			}
		}
		$value = PayplansHelperParam::arrayToIni($params);
	}
}


