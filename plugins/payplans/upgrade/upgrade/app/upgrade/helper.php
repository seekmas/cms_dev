<?php 

class PayplansHelperUpgrade
{
	const APPLY_TRIAL_ALWAYS   =  1;
	const APPLY_TRIAL_NEVER    =  0;
	
	static function calculateUnutilizedValue(PayplansSubscription $oldSub)
	{
		//IMP : If subscription is no more active, value as 0
		if($oldSub->getStatus() != PayplansStatus::SUBSCRIPTION_ACTIVE){
			return array('paid' => 0, 'unutilized'=> 0);
		}
		//$oldPlan  = array_shift($oldSub->getPlans(true));
		
		// find value utilized by old subscription
		$start   = intval($oldSub->getSubscriptionDate()->toUnix());
		$expires = intval($oldSub->getExpirationDate()->toUnix());
		$now     = intval(XiDate::getInstance()->toUnix());
		
		$totalTime	= $expires - $start;
	
		$oldOrder = $oldSub->getOrder(true);
		// calculate sum of payments made during previous upgradation
		$totalValue = self::_calculatePaymentsDuringPreviousUpgradations($oldOrder);
		
		
		// free subscription OR life time subscription
		if($totalValue ==0 || $expires == 0){
			$usedValue = 0;	
		}else{
			$used  		= $now - $start;
			// if total time is not in hours, then calculate as per days
			$oneday = 24*60*60;
			
			if($totalTime > 3*$oneday){
				$used 		= intval($used/$oneday);
				$totalTime 	= intval($totalTime/$oneday);		
			}
			
			$usedValue  = $totalValue * $used / $totalTime;
		}
		
		// the value which is not utilized, and will be added into discount
		$unutilizedValue = $totalValue - $usedValue;
		
		return array('paid' => $totalValue, 'unutilized'=>$unutilizedValue);
	}
	
	//return the plans to which upgrade is available from the provided subscription plan 
	static function findAvailableUpgrades($subKey)
	{
		$subId = XiHelperUtils::getIdFromKey($subKey);
		$plan  = array_shift(PayplansSubscription::getInstance($subId)->getPlans(true));
		// should return plans' instances
		$args = array();
		$results = PayplansHelperEvent::trigger('onPayplansUpgradeTo', $args, '', $plan);
		
		$plans = array();
		foreach($results as $result){
			foreach($result as $plan){
				$plans[$plan->getId()] = $plan;
			}
		}
		
		return $plans;
	}
	
	protected static function _calculatePaymentsDuringPreviousUpgradations($order)
	{
		$upgradedFrom = $order->getParam('upgrading_from',0);
		
		// get payments
		$invoices = $order->getInvoices(PayplansStatus::INVOICE_PAID);
		if(count($invoices) == 0){
			// none of payment were completed
			$totalValue  = 0;
		}
		else{
			// pick last invoice
			$invoice    = array_pop($invoices);
			$totalValue  = $invoice->getTotal();
		}
		
		// if upgraded from some other subscription then also add payment done during that subscription
		while($upgradedFrom)
		{
			$sub      = PayplansSubscription::getInstance($upgradedFrom);
			$order    = $sub->getOrder(true);
			$invoices = $order->getInvoices(PayplansStatus::INVOICE_PAID);
			if(count($invoices) == 0){
				// none of payment were completed
				$upgradedFrom = $order->getParam('upgrading_from',0);
				continue;
			}
			// pick last invoices
			$invoice    = array_pop($invoices);
			$totalValue = $totalValue + $invoice->getTotal();
			$upgradedFrom = $order->getParam('upgrading_from',0);
			
		}
		
		return $totalValue;
	}
	
	static function willTrialApply($oldPlan, $newPlan)
	{
		$upgradeApps 	= PayplansHelperApp::getAvailableApps('upgrade');
		foreach($upgradeApps as $app)
		 {
		 	$upgradeTo = $app->getAppParam('upgrade_to',array());
		 	$upgradeTo = is_array($upgradeTo) ? $upgradeTo : array($upgradeTo);
		 	$willTrialApply = $app->getAppParam('willTrialApply','always');
			if($app->getParam('applyAll') && in_array($newPlan->getId(),$upgradeTo) ){
				if($willTrialApply == 'always'){
					return self::APPLY_TRIAL_ALWAYS;
				}
			}	
			elseif(in_array($oldPlan->getId(), $app->getPlans()) && in_array($newPlan->getId(),$upgradeTo))
			{
				if($willTrialApply == 'always'){
					return self::APPLY_TRIAL_ALWAYS;
				}
			}
		 }
		 return self::APPLY_TRIAL_NEVER;
	}
	

	//update new invoice as per trial if applicable 
	static function updateInvoiceParams($newInvoice, $willTrialApply)
	{
		$isRecurring = $newInvoice->isRecurring();
		
		if($willTrialApply == self::APPLY_TRIAL_NEVER && ($isRecurring == PAYPLANS_RECURRING_TRIAL_1
			|| $isRecurring == PAYPLANS_RECURRING_TRIAL_2))
		{
			$newInvoice->setParam('expirationtype', 'recurring');
			$newInvoice->setParam('trial_price_1', '0.00');
			$newInvoice->setParam('trial_time_1', '000000000000');
			$newInvoice->setParam('trial_price_2', '0.00');
			$newInvoice->setParam('trial_time_2', '000000000000');
			$newInvoice->refresh()->save();
		}
		
		// change new invoice to trial so that to apply discounted price only once
		if($newInvoice->isRecurring() == PAYPLANS_RECURRING)
		{	$params = $newInvoice->getParams()->toArray();
			$newInvoice->setParam('expirationtype', 'recurring_trial_1');
			$newInvoice->setParam('recurrence_count', $params['recurrence_count']-1);
			$newInvoice->setParam('trial_price_1', $params['price']);
			$newInvoice->setParam('trial_time_1', $params['expiration']);
			//subtotal does not modified by params value automatically
			$newInvoice->set('subtotal', $params['price']);
			$newInvoice->refresh()->save();
		}
		
		return $newInvoice;
		
	}
}