<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHelperModifier
{
	public static $serials = array(	PayplansModifier::FIXED_DISCOUNTABLE,
								PayplansModifier::PERCENT_DISCOUNTABLE,
								PayplansModifier::FIXED_DISCOUNT,
								PayplansModifier::PERCENT_DISCOUNT,
								PayplansModifier::FIXED_TAX,
								PayplansModifier::PERCENT_TAX,
								PayplansModifier::FIXED_NON_TAXABLE,
								PayplansModifier::PERCENT_NON_TAXABLE);
							
	/**
	 * Creat a modifier with the data provided
	 * @param $data array
	 * @return PayplansModifier
	 * @since 2.0
	 */
	static public function create($data) 
	{
		$modifier = PayplansModifier::getInstance();
		$modifier->bind($data)->save();
		return $modifier;
	}
	
	/**
	 * return all the modifiers applid on this filter
	 * @param $filter array() : contains the key and value
	 * @return array of PayplansModifier
	 * @since 2.0
	 */
	static public function get($filter, $instanceRequire = false)
	{
		// add filter and clean limit
		//XITODO : remove limit filtering
		$modifiers = XiFactory::getInstance('modifier', 'model')
							->loadRecords($filter, array('limit'));
							
		if(count($modifiers) <= 0 ){
			return array();
		}
		
		if($instanceRequire == PAYPLANS_INSTANCE_REQUIRE){
			foreach($modifiers as &$modifier){
				$modifier = PayplansModifier::getInstance($modifier->modifier_id, $modifier);
			}
		}
		
		return $modifiers;
	} 
	
	/**
	 * Re-arrange the modifier according to their serial 
	 * @see PayplansModifier constants
	 * @param $records Array of PayplansModifier
	 * @return Array stdclass
	 * @since 2.0
	 */
	public static function _rearrange($records)
	{
		$results = array();
		
		// arrage according to their serial
		$arrangeOrder = array();
		foreach($records as $record){
			$arrangeOrder[$record->getSerial()][] = $record;			
		}
		
		$arranged = array();
		foreach (self::$serials as $serial){
			if(!isset($arrangeOrder[$serial])){
				continue;
			}
			
			$arranged = array_merge($arranged, $arrangeOrder[$serial]);
		}
		
		return $arranged;
	}
	
	/**
	 * Applies the modifiers on the  Object 
	 * @param subtotal
	 * @param array PayplansModifiers $modifiers
	 * 
	 * @since 2.0
	 */
	static public function getTotal($subtotal, $modifiers)
	{
		// if not an array
		if(!is_array($modifiers)){
			$modifiers = array($modifiers);
		}
		
		$modifiers = PayplansHelperModifier::_rearrange($modifiers);
		
		$total = $subtotal;
		foreach($modifiers as $modifier){
			
			// V.IMP : amount may be positive or negative
			$modificationOf = $modifier->getAmount();
			
			if($modifier->isPercentage() == true){
				$modificationOf = $total * $modificationOf / 100;
			}
			
			// set the modification amount on object so that
			// no need to be calculate again 
			$modifier->_modificationOf = $modificationOf;
			
			$total = $total + $modificationOf;
			
			// XITODO : apply limit of maximum discount
			if($total < 0){
				$total = 0;
				break;
			}
		}

		return $total;
	}
	
	/**
	 * 
	 * Returns the modified amount by the $serials
	 * @param numeric $total
	 * @param array $modifiers
	 * @param array $serials
	 * 
	 * @return float $modifiedBy
	 * @since 2.0
	 */
	static public function getModificationAmount($total, $modifiers, $serials)
	{
		if(!is_array($serials)){
			$serials = array($serials);
		}
		
		$modifiedBy = 0;
		foreach($modifiers as $modifier){		
			if(in_array($modifier->getSerial(), $serials)){
				$modifiedBy += $modifier->_modificationOf;
			}		
		}
		
		return $modifiedBy;
	}
	
	public function applyConditionally($invoice, $referenceInvoice, $modifiers)
	{		
		foreach($modifiers as $modifier){
			switch ($modifier->getFrequency()){
				case PayplansModifier::FREQUENCY_EACH_TIME :
						$newModifier = $modifier->getClone();
						$newModifier->setId(0)
							->set('invoice_id', $invoice->getId())
							->save();
						break;
				
				default : 
						continue;
			}		
		}
		
		return true;
	}
	
	public function getTotalByFrequencyOnInvoiceNumber($modifiers, $subtotal, $invoiceNumber)
	{
		$total = $subtotal;
		foreach($modifiers as $modifier){
			switch ($modifier->getFrequency()){
				case PayplansModifier::FREQUENCY_EACH_TIME :
						$total = self::getTotal($total, $modifier);
						break;
				
				default : 
						continue;
			}		
		}
			
		return $total;
	}
	
	/**
	 * Get total usage of the given reference and type
	 */
	static function getTotalUsage($reference, $type)
	{
		$query = new XiQuery();
		return count($query->select('*')
					 ->from('`#__payplans_modifier` as modifier')
					 ->leftJoin('`#__payplans_invoice` as invoice ON invoice.`invoice_id` = modifier.`invoice_id`')
			         ->where('modifier.`reference` = "'.$reference.'"')
					 ->where('modifier.`type` = "'.$type.'"')
					 ->dbLoadQuery()
					 ->loadObjectList());
	}
	
	/**
	 * Get actual consumption of the given reference and type
	 */
	static function getActualConsumption($reference, $type, $count=true)
	{
		$query  = new XiQuery();
		$result = $query->select('*')
					 ->from('`#__payplans_modifier` as modifier')
					 ->leftJoin('`#__payplans_invoice` as invoice ON invoice.`invoice_id` = modifier.`invoice_id`')
			         ->where('modifier.`reference` = "'.$reference.'"')
					 ->where('modifier.`type` = "'.$type.'"')
					 ->where('(invoice.`status` = "'.PayplansStatus::INVOICE_PAID.'")')
					 ->dbLoadQuery()
					 ->loadObjectList();
		//check if all records are needed
		if($count == false){
			return $result;
		}
		
		return count($result);
	}
}