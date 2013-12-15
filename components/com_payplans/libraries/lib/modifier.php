<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

/**
 * Lib class for Modifiers
 * it include the tax, discount, addition, substractions
 * @since 2.0
 *
 */
class PayplansModifier extends XiLib
{
	// Modifiers should not be triggered
	public 		$_trigger   		= false;
	
	/**
	 * Discountable Modifier means any addition or substraction
	 * which should be applied before discount and tax are being applied
	 *
	 * FIXED amount will be applied before PERCENTAGE amount
	 * @var constant int
	 */
	const FIXED_DISCOUNTABLE 	= 10;
	const PERCENT_DISCOUNTABLE 	= 15;
	
	/**
	 * Discount Modifier means discount on order/invocie
	 * which should be applied after Discountable modifier
	 *
	 * FIXED discount will be applied before PERCENTAGE discount
	 * @var constant int
	 */
	const FIXED_DISCOUNT		= 20;
	const PERCENT_DISCOUNT		= 25;
	
	/**
	 * Tax Modifier means tax on order/invocie
	 * which should be applied after Discount modifier
	 *
	 * FIXED tax will be applied before PERCENTAGE tax
	 * @var constant int
	 */
	const FIXED_TAX				= 30;
	const PERCENT_TAX			= 35;
	
	/**
	 * TAXABLE Modifier means any addition or substraction
	 * which should be applied after applying discount and tax
	 *
	 * FIXED amount will be applied before PERCENTAGE amount
	 * @var constant int
	 */
	const FIXED_NON_TAXABLE 	= 40;
	const PERCENT_NON_TAXABLE 	= 45;
	
	/**
	 * Constants for frequency of modifire on invoice
	 */
	const FREQUENCY_ONE_TIME 	= 'ONE TIME';
	const FREQUENCY_EACH_TIME 	= 'EACH TIME';
	
	protected $modifier_id = 0;
	protected $user_id = 0;
	protected $invoice_id = 0;
	protected $amount = 0.00;
	protected $type = '';
	protected $reference = '';
	protected $message = '';
	protected $percentage = 1;
	protected $serial = null;
	protected $frequency = 0;
	
	/**
	 * @return PayplansModifier
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @param string $dummy1 is added just for removing warning with development mode
	 * @since 2.0
	 */
	static public function getInstance($id=0, $bindData=null, $dummy=null, $dummy1=null)
	{
		return parent::getInstance('modifier', $id, null, $bindData);
	}

	// Reset to construction time.
	public function reset(Array $config=array())
	{
		$this->modifier_id = 0;
		$this->user_id = 0;
		$this->order_id = 0;
		$this->invoice_id = 0;
		$this->amount = 0.00;
		$this->type = '';
		$this->reference = '';
		$this->message = '';
		$this->percentage = 1;
		$this->serial = null;
		
		return $this;
	}
	
	public function getAmount()
	{
		return $this->amount;
	}
	
	public function isPercentage()
	{
		return $this->percentage;
	}
	
	public function getSerial()
	{
		return $this->serial;
	}
	
	public function getFrequency()
	{
		return $this->frequency;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function getReference()
	{
		return $this->reference;
	}
	
	public function getInvoice($requireinstance = false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansInvoice::getInstance($this->invoice_id);
		}
		
		return $this->invoice_id;
	}
}
