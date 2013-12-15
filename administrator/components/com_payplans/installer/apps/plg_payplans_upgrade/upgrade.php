<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* @package	Payplans
* @subpackage	Upgrade Plugin
* @contact	payplans@readybytes.in
*/
// no direct access
if(defined( '_JEXEC' )==false) {
	die( 'Restricted access' );
} 


class plgPayplansUpgrade extends XiPlugin
{

	public function onPayplansSystemStart()
	{
		//add discount app path to app loader
		$appPath = dirname(__FILE__).DS.'upgrade'.DS.'app';
		PayplansHelperApp::addAppsPath($appPath);
		PayplansHelperLoader::addAutoLoadFile($appPath.DS.'upgrade'.DS.'helper.php', 'PayplansHelperUpgrade');

		return true;
	}
	
	public function onPayplansViewBeforeRender(XiView $view, $task)
	{
		// handle order confirmation
		if(($view instanceof PayplanssiteViewInvoice)==true && $task == 'confirm'){
			return $this->_updateOrderBeforeConfirmation($view, $task);
		}
		
		if(($view instanceof PayplansadminViewSubscription) && ($task == 'edit')){	
			$document	= XiFactory::getDocument();
			$model = $view->getModel();
			//	don't display upgrade button when creating new order
			if($model->getId() != null){		   
			   $subscription = PayplansSubscription::getInstance($model->getId());
			   $order = $subscription->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		   	   $upgrade = PayplansHelperApp::getApplicableApps('upgrade', $order);
		   
			   //display upgrade option only when it is applicable and order is complete
			   if($upgrade && $subscription->getStatus() == PayplansStatus::SUBSCRIPTION_ACTIVE){
			   		XiHelperToolbar::custom('upgrade', 'upgrade.png', 'upgrade.png', 'COM_PAYPLANS_UPGRADE_TOOLBAR', true );
			   		XiHelperToolbar::divider();
			   		
				    //Get dynamic java script
					$jsScript	=	$this->_getUpgradeJavascript($order);
					if($jsScript){
						$document->addScriptDeclaration($jsScript);
					}
			   }
	        }
	        
			//add upgrade javascript
	        $path 		= PayplansHelperTemplate::mediaURI(dirname(__FILE__).DS.'upgrade'.DS.'app'.DS.'upgrade'.DS.'tmpl'.DS.'upgrade.js', false);
			$document->addScript($path, 'text/javascript');
		}

		return true;
	}
	
	protected function _getUpgradeJavascript($order)
	{
		$url	=	"index.php?option=com_payplans&view=order";
		$itemId = $order->getId();
		// if has no item id then set item id to 0
		if(!$itemId){
			$itemId = 0;
		}
		ob_start(); ?>

		payplansAdmin.subscription_upgrade = function()
		{
			var userid = <?php echo $order->getBuyer();?>;
			var args   = { 'event_args' : {'userid' : userid, 'order_id' : <?php echo $itemId;?>} };
			var theurl = 'index.php?option=com_payplans&view=order&task=trigger&event=onPayplansUpgradeFromRequest';
			
			xi.ui.dialog.create(
				{url:theurl, data:args, iframe:false, id:'pp-admin-order-upgrade'},
					'<?php echo XiText::_('COM_PAYPLANS_ORDER_UPGRADE_LINK');?>',
				550, 350
			);
			
    		return false;
		}
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}
	
	public function onPayplansUpgradeFromRequest($userid = null, $orderId = null)
	{
		$userid = ($userid == null)? XiFactory::getUser()->id : $userid;
		if(!$userid){
			// XITODO : show a proper message here, that you are not logged in
			return true;
		}
		
		$user = PayplansUser::getInstance($userid);
		$this->_assign('subscriptions', $user->getSubscriptions(PayplansStatus::SUBSCRIPTION_ACTIVE));
		
		$html		 = $this->_render('upgrade_from');	
        $title 		 = XiText::_('PLG_PAYPLANS_UPGRADE_SELECT_ACTIVE_SUBSCRIPTION');
		$domObject   = 'xiWindowBody';
		$domProperty = 'innerHTML';

		$response	 = XiFactory::getAjaxResponse();
		$response->addAssign( $domObject , $domProperty , $html );
		$response->addScriptCall('xi.ui.dialog.title',$title);
		$response->addScriptCall('xi.ui.dialog.height','auto');
		$response->addScriptCall('xi.ui.dialog.width','700px');
		$response->sendResponse();
	}
	

	public function onPayplansUpgradeToRequest($subKey)
	{
		// find available plans
		$plans = PayplansHelperUpgrade::findAvailableUpgrades($subKey);
		
		$this->_assign('upgrade_to', $plans);
		$this->_assign('sub_key', $subKey);
		$html= $this->_render('upgrade_to');	

		$response	= XiFactory::getAjaxResponse();
		$response->addScriptCall('payplans.jQuery(\'#payplans-upgrade-'.$subKey.'-to\').html', $html);
		$response->addScriptCall('payplans.jQuery(\'#payplans-upgrade-'.$subKey.'-to\').show');
		$response->sendResponse();
	}
	
	public function onPayplansUpgradeDisplayConfirm($newPlanId, $subKey, $userid = null)
	{
		$userid = ($userid== null) ? XiFactory::getUser()->id : $userid;
		// XITODO : show a proper message here, that you are not logged in
		if(!$userid){
			return true;
		}
		
		// find available plans for this subscription
		$availablePlans = PayplansHelperUpgrade::findAvailableUpgrades($subKey);
		
		if(isset($availablePlans[$newPlanId]) == false){
			//XITODO : Plan is not avilable for upgrade, show Error Message
			return true;
		}
		
		// plan can be upgraded
		$subId 	  = XiHelperUtils::getIdFromKey($subKey);
		$oldSub   = PayplansSubscription::getInstance($subId);
		$oldPlan  = array_shift($oldSub->getPlans(true));
		$newPlan  = PayplansPlan::getInstance($newPlanId);
		$oldOrder = $oldSub->getOrder(true);
		$oldInvoices = $oldOrder->getInvoices(PayplansStatus::INVOICE_PAID);
		if(count($oldInvoices))
		{
			$oldInvoice = array_pop($oldInvoices);
		}
		else{
			$oldInvoice = $oldOrder->createInvoice();
		}
		$result   = PayplansHelperUpgrade::calculateUnutilizedValue($oldSub);
		$paidAmount = $result['paid'];
		$unutilized = $result['unutilized'];
		
		$newOrder = $this->_createUpgradeOrder($oldOrder, $oldSub, $newPlan);
		$newInvoice = $newOrder->createInvoice();

		// check whether trail is applicable or not
		// and then update invoice params accordingly
		$willTrialApply	= PayplansHelperUpgrade::willTrialApply($oldPlan, $newPlan);
		$newInvoice = PayplansHelperUpgrade::updateInvoiceParams($newInvoice, $willTrialApply);
				
		$modifierParams 			= new stdClass();
		$modifierParams->type 		= 'upgrade';
		$modifierParams->reference	= $oldInvoice->getKey();
		$modifierParams->percentage	= false;
		$modifierParams->amount		= -$unutilized;
		$modifierParams->serial		= PayplansModifier::FIXED_NON_TAXABLE;
		$modifierParams->message	= 'COM_PAYPLANS_APP_UPGRADE_MESSAGE';
		
		$modifier = $newInvoice->addModifier($modifierParams);
		$newInvoice->save();
		
		// should return plans' instances
		$args = array($newOrder, $newPlan, $unutilized, $oldPlan, $oldOrder,$newInvoice);
		$results = PayplansHelperEvent::trigger('onPayplansUpgradeBeforeDisplay', $args, '', $oldPlan);
		
		//render html
		$this->_assign('new_invoice', $newInvoice);
		$this->_assign('new_order', $newOrder);
		$this->_assign('new_sub', $newOrder->getSubscription());
		$this->_assign('new_plan', $newPlan);
		
		$this->_assign('old_plan', $oldPlan);
		$this->_assign('old_order', $oldOrder);
		$this->_assign('old_sub',  $oldSub);
		
		$this->_assign('unutilized_amount', $unutilized);
		$this->_assign('paid_amount',  $paidAmount);
		$this->_assign('user', $newInvoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE));
			
		$html           = $this->_render('upgrade_details');

		$this->addAjaxOnDisplayOrder($html, $newInvoice, $oldSub);

	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param PayplansSubscription $oldSub
	 * @param PayplansPlan $newPlan
	 * @return PayplansOrder
	 */
	protected function _createUpgradeOrder(PayplansOrder $oldOrder, PayplansSubscription $oldSub, PayplansPlan $newPlan)
	{
		//Create a NEW Order		
		$newOrder = $newPlan->subscribe($oldSub->getBuyer());
		
		//XITODO : Currently we do not support trial prices in new plan, We will do it in future
		// update order and subscription
		$newOrder->setParam('upgrading_from', $oldSub->getId())->save();

		$oldOrder->setParam('upgraded_to', $newOrder->getSubscription()->getId())->save();
		
		// return order
		return $newOrder;
	}
	
	protected function onPayplansUpgradeToCancel($order_key)
	{
		// XITODO : show a proper message here, that you are not logged in
		$userid = XiFactory::getUser()->id;
		if(!$userid){
			return true;
		}
		
		// find order
		$orderId  = XiHelperUtils::getIdFromKey($order_key);
		$order = PayplansOrder::getInstance($orderId);
		
		// check if order is from same person
		if($order->getBuyer() != $userid){
			return true;
		}
		
		$order->delete();
	}
	
	/**
	 * When the upgrade subscription is getting active, 
	 * we need to 
	 *  - mark existing subscription and order expired
	 *  - XITODO : mark existing order expired just before the new subscription is about to active(it not support upgrades for one out of multiple-subscription in a single order)
	 *  - XITODO : cancel recurring payments for previous subscription
	 *  - if normal payment then  
	 * 
	 * @param PayplansSubscription $previous
	 * @param PayplansSubscription $current
	 */
	function onPayplansSubscriptionBeforeSave($previous=null, $current=null)
	{
		// Consider Previous State also
		if(isset($previous) && $previous->getStatus() == $current->getStatus()){
			return true;
		}

		// if there is change in status of order
		if($current->getStatus() != PayplansStatus::SUBSCRIPTION_ACTIVE){
			return true;
		}
		
		$order = $current->getOrder(PAYPLANS_INSTANCE_REQUIRE);
		
		// is it upgrading from some plan ?
		$upgradingFrom = $order->getParam('upgrading_from',0);
		if(!$upgradingFrom){ // not upgrading
			return true;
		}
		
		// user is upgrading, cancel his previous subscription and order
		$oldSub = PayplansSubscription::getInstance($upgradingFrom);
		$oldOrder = $oldSub->getOrder(true);
		$oldOrder->setStatus(PayplansStatus::ORDER_EXPIRED)->save();
		$oldInvoices = $oldOrder->getInvoices(PayplansStatus::INVOICE_PAID);
		$oldInvoice = array_shift($oldInvoices);
		if(!$oldInvoice->isRecurring()){
			return true;
		}
		$oldPayment = $oldInvoice->getPayment();
		$paymentApp = $oldPayment->getApp(PAYPLANS_INSTANCE_REQUIRE);
		$supportPaymentCancel = $paymentApp->isSupportPaymentCancellation($oldInvoice);
		if($supportPaymentCancel)
		{
				$result = $oldOrder->terminate();
				$message=XiText::_('COM_PAYPLANS_UPGRADES_PAYMENT_CANCELLATION_IS_PROCESSESD');
				$content = array( 'user'=>$oldInvoice->getBuyer() , 'invoice_key' => $oldInvoice->getKey() ,'result'=>$result);
				PayplansHelperLogger::log(XiLogger::LEVEL_INFO, $message, $oldOrder,$content);
		}
		else
		{
			$message=XiText::_('COM_PAYPLANS_UPGRADES_PAYMENT_CANCELLATION_CANNOT_PROCESSESD');
			$result = XiText::_('COM_PAYPLANS_UPGRADES_PAYMENT_CANNOT_BE_CANCELLED');
			$content = array( 'user'=>$oldInvoice->getBuyer() , 'invoice_key' => $oldInvoice->getKey(),'result'=>$result);;
			PayplansHelperLogger::log(XiLogger::LEVEL_ERROR, $message, $oldOrder,$content);

			//send email to user for order cancellation
			$this->_sendEmail($oldOrder, $oldInvoice, $oldSub);
		}
		
		// We are marking ORDER expired, 
		// so for recurring payments will not able to enable the order again 
		
		return true;
	}
	
	protected function _sendEmail($oldOrder, $oldInvoice, $oldSub)
	{	
		$mailer  	= XiFactory::getMailer();
		$subject 	= XiText::_('COM_PAYPLANS_UPGRADES_ORDER_CANCEL_SUBJECT');
		$mailer->setSubject($subject);

		$message	= XiText::_('COM_PAYPLANS_UPGRADES_ORDER_CANCEL_MESSAGE');
		$parameters = array('order_key'=>$oldOrder->getKey(), 'invoice_key'=>$oldInvoice->getKey(), 'subscription_key'=>$oldSub->getKey());
		$args   	= array('message' => $message,'parameters' => $parameters);

		//render the email content
		$output 	= $this->_render('order_cancel_email', $args);

		$mailer->setBody($output);
		$mailer->IsHTML(1);
			
		$mailer->addRecipient($oldOrder->getBuyer(PAYPLANS_INSTANCE_REQUIRE)->getEmail());
		$mailer->Send();
		
	}

	/**
	 * XITODO : When user confirms upgrade-order, we need to make sure everything is up-to-date
	 * because -
	 * 
	 * I can make a upgrade request today, if I do not complete order
	 * and complete this after some months, I will get the same discount
	 * calculated on the time of creation of upgrade-request. 
	 */
	protected function _updateOrderBeforeConfirmation(XiView $view, $task)
	{	
		$newInvoice = PayplansInvoice::getInstance($view->getModel()->getId());
		$newOrder   = $newInvoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);
		
		if(!is_a($newOrder, 'PayplansOrder')){
			return true;
		}

		// is it upgrading from some plan ?
		$upgradingFrom = $newOrder->getParam('upgrading_from',0);
		if(!$upgradingFrom){ // not upgrading
				return true;
		}

		$oldSub   = PayplansSubscription::getInstance($upgradingFrom);
		
		$result = PayplansHelperUpgrade::calculateUnutilizedValue($oldSub);
		$unutilizedValue = $result['unutilized'];
		
		$oldOrderKey = $oldSub->getOrder(true)->getKey();
		
		$key 	= '<a href="'. XiRoute::_('index.php?option=com_payplans&view=order&order_key='.$oldOrderKey ).'">'.$oldOrderKey.'</a>';
		$amount = PayplansHelperFormat::amount($unutilizedValue, $oldSub->getCurrency()); 
		$message = XiText::sprintf('COM_PAYPLANS_UPGRADE_UPGRADING_FROM_KEY_DISCOUNTED_AMOUNT', $key, $amount);
        XiFactory::getApplication()->enqueueMessage($message);
		return true;
	}
	
	
	function onPayplansUpgradeFromBackend($upgradeType = null, $invoiceKey)
	{
		//only super admin is allowed to use backend upgrades
		if(!PayplansUser::getInstance(XiFactory::getUser()->id)->isAdmin()){
			return  true;
		}
		
		//when there is no invoiceKey or no upgradetype selected  
		if(($upgradeType == null) || (!$invoiceKey)){
			$error = XiText::_('COM_PAYPLANS_ERROR_OCCURED_WHILE_UPGRADING_ORDER');
		}
		
		else {
			$invoice   = PayplansInvoice::getInstance(XiHelperUtils::getIdFromKey($invoiceKey));
			
			if($upgradeType == 'free'){
				$this->_upgradeFree($invoice);
				$msg = XiText::_('COM_PAYPLANS_UPGRADE_SUBSCRIPTION_SUCCESSFULLY_UPGRADED_FROM_FREE_TYPE');
			}
			elseif($upgradeType == 'offline'){
				$this->_upgradeOffline($invoice);
				$msg = XiText::_('COM_PAYPLANS_UPGRADE_SUBSCRIPTION_SUCCESSFULLY_UPGRADED_FROM_OFFLINE_TYPE');
			}
			elseif($upgradeType == 'wallet'){
				$result = $this->_upgradeUsingWallet($invoice);
				if($result !== true){
					$error = $result;
				}
				$msg = XiText::_('COM_PAYPLANS_UPGRADE_SUBSCRIPTION_SUCCESSFULLY_UPGRADED_FROM_WALLET_TYPE');
			}
			
			else {
				//XITODO :handle partial upgrade
				$this->_upgradePartial($invoice);
				$msg = XiText::_('COM_PAYPLANS_UPGRADE_SUBSCRIPTION_SUCCESSFULLY_UPGRADED_FROM_PARTIAL_TYPE');
			}
		}
		
		$title 		 = XiText::_('PLG_PAYPLANS_UPGRADE_SUBSCRIPTION_UPGRADED');
		//if no upgrade type selected or no invoicekey send then set the error message
		if(isset($error)){
			$title 	 = XiText::_('PLG_PAYPLANS_UPGRADE_SUBSCRIPTION_UPGRADE_REQUEST_ERROR');
			$msg = $error;
		}
		
		$this->_assign('message', $msg);
		$html = $this->_render('upgrade_success');

		$domObject   = 'xiWindowBody';
		$domProperty = 'innerHTML';

		$response	 = XiFactory::getAjaxResponse();
		$response->addAssign( $domObject , $domProperty , $html );
		$response->addScriptCall('xi.ui.dialog.title',$title);
		$response->addScriptCall('payplans.jQuery(\'#button-upgrade-now\').hide');
		$response->addScriptCall('payplans.jQuery(\'#upgrade-info-back\').hide');
		$response->addScriptCall('payplans.jQuery(\'#button-upgrade-cancel\').hide');
		$response->addScriptCall('xi.ui.dialog.autoclose',15000);
		$response->sendResponse();
	}
	
	protected function _upgradeFree($invoice)
	{
		$newOrder    = $invoice->getReferenceObject(PAYPLANS_INSTANCE_REQUIRE);

		if(is_a($newOrder, 'PayplansOrder')){
			$reference = $newOrder->getParam('upgrading_from');
		}
		
		//set the modifier reference to the old subscription key
		$reference = isset($reference)? $reference : 'order_upgrade';
		
		$modifierParams = new stdClass();
		$modifierParams->type ='free_upgrade';
		$modifierParams->percentage=true;
		$modifierParams->serial = PayplansModifier::PERCENT_DISCOUNTABLE;
		$modifierParams->amount=-100;
		$modifierParams->message='COM_PAYPLANS_FREE_UPGRADE_MESSAGE';
		$modifierParams->reference=$reference;
		
		$invoice->addModifier($modifierParams);
		$invoice->save();
	
		//transaction added for free upgrade
		$transaction = PayplansTransaction::getInstance();
		$transaction->set('user_id', $invoice->getBuyer())
					->set('invoice_id', $invoice->getId())
					->set('payment_id', 0)
					->set('message', 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_FREE_UPGRADE')
					->save();

		//trigger the event 
		$args = array($transaction, 0);
		PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
		return true;
	}
	
	protected function _upgradeOffline($invoice)
	{	
		$params     = new stdClass();
		$params->transaction_amount     = $invoice->getTotal();
		$params->transaction_message    = 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_OFFLINE_UPGRADE';
		
		$transaction = $invoice->addTransaction($params);
		return true;
	}
	
	protected function _upgradePartial($invoice)
	{
		$invoice->emaillink();
		return true;
	}
	
	protected function _upgradeUsingWallet($invoice)
	{
		$user     = $invoice->getBuyer(PAYPLANS_INSTANCE_REQUIRE);
		$walletBalance = $user->getWalletBalance();
		
		if(floatval($invoice->getTotal()) > floatval($walletBalance)){
			return XiText::_('COM_PAYPLANS_UPGRADES_INSUFFICIENT_BALANCE_IN_WALLET');
		}
		
		$params     = new stdClass();
		$params->transaction_amount     = 0;
		$params->transaction_message    = 'COM_PAYPLANS_TRANSACTION_CREATED_FOR_UPGRADE_USING_WALLET';
		
		$transaction = $invoice->addTransaction($params);
		
		$args = array($transaction, $invoice->getTotal());
		PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
		return true;
	}
	
	function addAjaxOnDisplayOrder($html, $newInvoice, $oldSub)
	{
		$title          =  XiText::_('COM_PAYPLANS_UPGRADES_REQUEST_DETAILS');
        $url            = XiRoute::_('index.php?option=com_payplans&view=invoice&task=confirm&invoice_key='.$newInvoice->getKey());
        
		$object1        = new stdClass();
		$object1->id    = "button-upgrade-now";
		$object1->click = 'payplans.url.redirect("'.$url.'")';
		$object1->text 	= XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_UPGRADE_NOW');
		$object1->classes = "btn btn-primary";
		
		$object2 		= new stdClass();
		$object2->id    = "button-upgrade-cancel";
		$object2->click = 'payplans.apps.upgrade.setPlansUpgradeToCancel("'.XiHelperUtils::getKeyFromId($newInvoice->getObjectId()).'","'.$oldSub->getKey().'")';
		$object2->text 	= XiText::_('COM_PAYPLANS_UPGRADES_DETAILS_CANCEL');
		$object2->classes = "btn";

		$domObject   = 'payplans-popup-upgrade-details';
		$domProperty = 'innerHTML';

		$response	= XiFactory::getAjaxResponse();
		$response->addAssign( $domObject , $domProperty , $html );
		$response->addScriptCall('xi.ui.dialog.title',$title);
		
		//below objects are required only in the backend
		if(XiFactory::getApplication()->isAdmin()){
        	$object1->click = 'payplans.apps.upgrade.upgradeOrder("'.$newInvoice->getKey().'")';
        	$object1->attr = 'style="display:none"';
        	$object2->attr = 'style="display:none"';
        	
        	//free upgrade button
        	$object3 = new stdClass();
        	$object3->classes = 'upgrade-options btn';
        	$object3->click = "payplans.apps.upgrade.displayInfoButtons('free')";
        	$object3->text  = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_FREE_UPGARDE');
        	$object3->title = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_FREE_UPGARDE_TOOLTIP');
        	
        	//offline upgrade button
        	$object4 = new stdClass();
        	$object4->classes = 'upgrade-options btn btn-primary';
        	$object4->click = "payplans.apps.upgrade.displayInfoButtons('offline')";
        	$object4->text  = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_OFFLINE_UPGARDE');
        	$object4->title = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_OFFLINE_UPGARDE_TOOLTIP');
			
        	
        	//partial upgrade button
        	$object5 = new stdClass();
        	$object5->classes = 'upgrade-options btn btn-primary';
        	$object5->click = "payplans.apps.upgrade.displayInfoButtons('partial')";
        	$object5->text  = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_PARTIAL_UPGARDE');
        	$object5->title = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_PARTIAL_UPGARDE_TOOLTIP');
        	
        	//back button
        	$object6 = new stdClass();
        	$object6->id    = "upgrade-info-back";
        	$object6->classes = 'upgrade-info btn btn-primary';
        	$object6->click = "payplans.apps.upgrade.showUpgradeButtons()";
        	$object6->text  = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_BACK');
        	$object6->style = 'display:none';
        	
        	//utilize-wallet upgrade button
        	$object7 = new stdClass();
        	$object7->classes = 'upgrade-options btn btn-primary';
        	$object7->click = "payplans.apps.upgrade.displayInfoButtons('wallet')";
        	$object7->text  = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_WALLET_UPGARDE');
        	$object7->title = XiText::_('COM_PAYPLANS_UPGRADES_FROM_BACKEND_WALLET_UPGRADE_TOOLTIP');

        	$response->addScriptCall('xi.ui.dialog.button',array($object6, $object3, $object4, $object5, $object7, $object1,$object2));
        	$response->sendResponse();
        }
        
        $response->addScriptCall('xi.ui.dialog.button',array($object1,$object2));
        $response->sendResponse();
	}
		
}
