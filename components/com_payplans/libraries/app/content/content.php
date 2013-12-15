<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		Payplans
* @subpackage	CoreApps
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


/**
 * Content App
 * @author shyam
 */
class PayplansAppContent extends PayplansApp
{
	//inherited properties
	protected $_location	= __FILE__;
	
	/* some constants */
    const PREFIX  = 'prefix';
    const SUFFIX  = 'suffix';
    const REPLACE = 'replace';
    
	/**
	 * overirde isApplicable 
	 * @param $refObject must be an instance of XiView
	 */
	public function isApplicable($refObject = null, $eventName='')
	{
		// not applicable in admin panel
		if(XiFactory::getApplication()->isAdmin()){
			return false;
		}
		
		// if not with reference to payment then return
		if($refObject === null || (!($refObject instanceof XiView) && !($refObject instanceof XiController))){
			return false;
		}
		
		//For Checking to show content according to group id on plan subscribe screen.
		if(($this->getAppParam('defined_locations') == 'view=plan&task=subscribe')
					&& (isset(PayplansFactory::getConfig()->useGroupsForPlan) && PayplansFactory::getConfig()->useGroupsForPlan))
		{
			$requestGroupId = JRequest::getVar('group_id',false);
			$groupIds = $this->getAppParam('group_id', array());

			if((false == $requestGroupId) && empty($groupIds)){
				return true;
			}
				
			$groupIds = is_array($groupIds) ? $groupIds : array($groupIds);	
					
			return in_array($requestGroupId, $groupIds);			
		}

		//if applicable to all is false then check plan v/s apps
		if($this->getParam('applyAll',false) == false){			

			$model = $refObject->getModel();
			if(!$model){
				// for displaying content as per plan on dashboard
				if(($refObject instanceof PayplanssiteViewDashboard) || ($refObject instanceof PayplanssiteControllerDashboard)){
					
					//get the instance of PayplansUser so as to check the subscribed plans
					$user = XiFactory::getUser();
					$libInstance = PayplansUser::getInstance($user->id);
					
					return $this->_checkPlanIntersection($libInstance);
				}
				return false;
			}
			
			$id = $model->getId();
			$libInstance = XiLib::getInstance($refObject->getName(),$id);

			return $this->_checkPlanIntersection($libInstance);
		}
		return true;
	}

	private function _checkPlanIntersection($libInstance)
	{
		if(!($libInstance instanceof PayplansIfaceApptriggerable)){
			return false;
		}

		//when app is set for non-subscriber dashboard then no need to check plans
		$location = $this->getAppParam('defined_locations', 0);
		if(strpos($location, 'noaccess') !== false){
			return true;
		}
		
		$plan		= $libInstance->getPlans();
		$appPlan 	= $this->getPlans();
		
		// if object is of interest as per plans selected
		$ret = array_intersect($appPlan, $plan);
		if(count($ret) > 0 ){
			return true;
		}
		
		return false;
	}

	public function collectAppParams(array $data)
	{
		// encode editor content
		if(isset($data['app_params']) && isset($data['app_params']['custom_content'])){
			$data['app_params']['custom_content'] = base64_encode($data['app_params']['custom_content']);
		}

		return parent::collectAppParams($data);
	}
	
	protected function _processLocations(Array $locations)
	{
		foreach($locations as $location)
		{
			if(empty($location)){
				continue;
			}
			
			$temp = explode("&", $location);
			if(empty($temp[0])){
				continue;
			}	
			
			$flag = true;
			foreach($temp as $value){
				list($key, $val) = explode('=', $value);
				// IMP : using get method only
				//if key is not present in url then set default value of key
				// if deafult value is not set then set BLANK
				$defaultValue = isset($this->defaultValues[$key]) ? $this->defaultValues[$key] : 'BLANK';
				$actualValue = JRequest::getVar($key, $defaultValue);

				//exact match
				if($actualValue == $val){
					continue;
				}

				//when data is expected, any value will work except NON VALUE
				if($val === 'RANDOM' && $actualValue != 'BLANK'){
					continue;
				}
				
				$flag = false;
				break;				
			}
			
			// if flag is true, means match found return true
			if($flag){
				return true;
			}			
		}
		
		return false;
	}
	
	public function onPayplansViewAfterRender(XiView $view, $task, &$output)
	{
		$locations = array();
		$definedLocations = $this->getAppParam('defined_locations', null);
		if(!empty($definedLocations)){
			$locations[] = $definedLocations;
		}

//		$locations = array_merge($locations, explode(';', $this->getAppParam('custom_locations', null)));
		
		// if still locations are empty then return false
		if(empty($locations)){
			return false;
		}
		
		if(!$this->_processLocations($locations)){
			return false;
		}
		
		
		// add custom content  to output as 
		switch($this->getAppParam('position', self::PREFIX))
		{
			case self::SUFFIX :
				$output .= '<div class="payplansContentSuffix">'.$this->_getContent().'</div>';
				break;
					
			case self::REPLACE :
				$output = '<div class="payplansContentReplace">'.$this->_getContent().'</div>';
				break;
				
			case self::PREFIX :
			default : 
				$output = '<div class="payplansContentPrefix">'.$this->_getContent().'</div>' . $output;
		}
		
		$output = $this->_replaceToken($output);
	}
	
	protected function _getContent()
	{
		// get joomla content if applicable
		//XITODO : check compatible with Joomla 1.6
		$articleId = $this->getAppParam('joomla_article', false);
		// find the filter applied
        $content = new stdClass();
        $content->text = '';
		if($this->getAppParam('filter', false) == 'custom_content'){
			$content->text =  base64_decode($this->getAppParam('custom_content', ''));
		}
		else {	
			//  Build Query
		    $query = "SELECT * FROM #__content WHERE id = $articleId";

		    //  Load query into an object
		    $db = JFactory::getDBO();
		    $db->setQuery($query);
		    $article = $db->loadObject();
		        
		    if(!$article){
		    	return '';
		    }
			$content->text = $article->introtext;
			if (JString::strlen($article->fulltext) > 1) {
				$content->text =  $article->introtext."<br/>".$article->fulltext;
			}
		}
        $param   = null;
        $args    = array('com_payplans.content', &$content, &$param, 0);
        XiHelperPlugin::trigger('onContentPrepare', $args);
         
	 return $content->text;
	}
	
	protected function _replaceToken($output)
	{	
		// get key of order/payment/subscription
		$orderKey 			= JRequest::getVar('order_key', false);
		$paymentKey 		= JRequest::getVar('payment_key', false);
		$subscriptionKey 	= JRequest::getVar('subscription_key', false);
		$invoiceKey 		= JRequest::getVar('invoice_key', false);
		
		$encryptor = XiFactory::getEncryptor();
		
		//get only one object, other will be calculated by rewriter itself
		if($orderKey != false){
			$object = PayplansOrder::getInstance($encryptor->decrypt($orderKey));
		}
		elseif($paymentKey != false){
			$object = PayplansPayment::getInstance($encryptor->decrypt($paymentKey));
		}
		elseif($subscriptionKey != false){
			$object = PayplansSubscription::getInstance($encryptor->decrypt($subscriptionKey));
		}
		elseif($invoiceKey != false){
			$object = PayplansInvoice::getInstance($encryptor->decrypt($invoiceKey));
		}
		else{
			$object = null;
		}
		
		if(!isset($object)){
			// get user id
			$user   = PayplansFactory::getUser();
			// rewrite only plan object
			if($user->id != false){
				$userObject = PayplansUser::getInstance($user->id);
				return PayplansFactory::getRewriter()->rewrite($output, $userObject, false);
			}
			
			return $output;
		}
		
		return PayplansFactory::getRewriter()->rewrite($output, $object);
	}
	
	public function onPayplansControllerAfterExecute($controller, $task)
	{
		$view = $controller->getView();
		if(!$view){
			return false;
		}
		
		//default view and task is saved in array 
		$this->defaultValues['view']   = $controller->getView()->getName();			
		$tempTask   = $task;
		if(empty($task)){
			$tempTask = $controller->getdoTask();
		}		
		$this->defaultValues['task']  = $tempTask;		
	}
}

class PayplansAppContentFormatter extends PayplansAppFormatter
{
	// get rules
	function getVarFormatter()
	{
		$rules = array('_appplans'       => array('formatter'=> 'PayplansAppFormatter',
										       'function' => 'getAppPlans'),
					   'app_params'      => array('formatter'=> 'PayplansAppContentFormatter',
										       'function' => 'getFormattedContent'));
		return $rules;
	}
	
	// format content app content
	function getFormattedContent($key, $value, $data)
	{
		$params = PayplansHelperParam::iniToArray($value);
		$articles   = XiHelperJoomla::getJoomlaArticles();
		foreach ($params as $param=>$v)
		{
			if($param == 'custom_content'){
				$params[$param] = base64_decode($v);
			}
			if($param == 'joomla_article'){
					$params[$param] = $articles[$v]->title;
				}
		}
		$value = PayplansHelperParam::arrayToIni($params);
	}
}
