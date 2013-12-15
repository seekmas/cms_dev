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
 * Transaction Class
 * 
 * @author Gaurav Jain
 * @since 2.0
 *
 */
class PayplansTransaction extends XiLib
						  implements PayplansIfaceApptriggerable, PayplansIfaceApiTransaction
{	
	public 	  $_trigger   = true;
	
	protected $transaction_id		= 0;
	protected $invoice_id			= 0;
	protected $current_invoice_id 	= 0;
	protected $user_id				= 0;
	protected $payment_id			= 0;
	protected $gateway_txn_id		= 0;
	protected $gateway_parent_txn	= 0;
	protected $gateway_subscr_id	= 0;
	protected $amount				= 0.00;
	protected $reference			= '';
	protected $message				= '';
	protected $created_date			= null;
	protected $params				= '';

	// skip these tokens in token rewriter
	public  $_blacklist_tokens = array('message','reference','params','gateway_txn_id','gateway_parent_txn','gateway_subscr_id'); 
	
	public function reset(Array $config=array())
	{
		$this->transaction_id 			= 0;
		$this->invoice_id					= 0;
		$this->user_id						= 0;
		$this->payment_id				= 0;
		$this->gateway_txn_id			= 0;
		$this->gateway_parent_txn	= 0;
		$this->gateway_subscr_id		= 0;
		$this->amount						= 0.00;
		$this->reference					= '';
		$this->message						= '';
		$this->created_date				= new XiDate();
		$this->params						= new XiParameter();
		
		return $this;
	}
	
	/**
	 * 
	 * Get instance of Transaction
	 * @param $id
	 * @param $type
	 * @param $bindData
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @return PayplansTransaction
	 * @since 2.0
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('transaction', $id, $type, $bindData);
	}
	
	/**
	 * Gets the buyer of the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getBuyer()
	 * 
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * 
	 *  @return mixed Userid or instance of PayplansUser attached with the transaction
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance){
			return PayplansUser::getInstance($this->user_id);
		}
		
		return $this->user_id;
	}
	
	/**
	 * Gets the amount of the transaction
	 * This amount is the actual amount received from the payment gateway
	 * 
	 * @see PayplansIfaceApiTransaction::getAmount()
	 * 
	 * @return float  Value of the amount
	 */
	public function getAmount()
	{
		return PayplansHelperFormat::price($this->amount);
	}
	
	/**
	 * Gets the wallet record for the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getWallet()
	 * 
	 * @return object PayplansWallet
	 */
	public function getWallet()
	{
		$records = XiFactory::getInstance('wallet','model')
							->loadRecords(array('transaction_id'=>$this->transaction_id));
	    $record = array_shift($records);	
	    
	    if(empty($record)){
	    	return false;
	    }			
		return PayplansWallet::getInstance($record->transaction_id, null, $record);
	}
		
	/**
	 * Gets the invoice attached to the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getInvoice()
	 * 
	 * @param   integer $requireinstance  Optional parameter to get the instance of the Invoice
	 * @return  mixed  InvoiceId or object of PayplansInvoice for InvoiceId
	 */
	public function getInvoice($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansInvoice::getInstance($this->invoice_id);
		}
		
		return $this->invoice_id;
	}
	
	/**
	 * @deprecated 2.2
	 */
	public function getCurrentInvoice($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansInvoice::getInstance($this->current_invoice_id);
		}
		
		return $this->current_invoice_id;
	}
	
	/**
	 * Save the transaction
	 * Credit and debit from wallet if amount of this transaction is not equivalant to 0
	 * 
	 * @see XiLib::save()
	 * @return booloean
	 */
	public function save()
	{
		$isNew = true;
		if($this->getId()){
			$isNew = false; 
		}
		
		if(!parent::save()){
			return false;
		}
		
		// if transaction already exists then do not require to update wallet records
		// else it will convert into infinite loop
		if(!$isNew){
			return true;
		}
		
		$amount = $this->getAmount();
		
		// return when there in no updation in wallet
		if(floatval($amount) == 0){
			return true;
		}
		
		$userid = $this->getBuyer();
		$id 	= $this->getId();
		
		// update the wallet
		if(floatval($amount) > 0){
			$wallet = PayplansHelperWallet::credit($userid, $id, $amount);
		}elseif(floatval($amount) < 0){
			$wallet = PayplansHelperWallet::debit($userid, $id, $amount);
		}
			
		return true;
	}
	
	/**
	 * Gets the payment record attached to the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getPayment()
	 * 
	 * @param boolean $requireinstance  Optional parameter to get the instance of the payment rather than payment id
	 * @return interger|object PaymentId or object of PayplansPayment for PaymentId
	 */
	public function getPayment($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansPayment::getInstance($this->payment_id);
		}
		
		return $this->payment_id;
	}
	
	/**
	 * Gets the payment gateway transaction id of the transaction
	 * Gateway Txn id is the unique identifier(reference) passed from 
	 * payment gateway indicating the transaction record at payment gateway end    
	 * 
	 * @see PayplansIfaceApiTransaction::getGatewayTxnId()
	 * 
	 * @retun string  Unique Identifier
	 */
	public function getGatewayTxnId()
	{
		return $this->gateway_txn_id;
	}
	
	/**
	 * Gets the parent gateway transaction id of the transaction
	 * 
	 * @retun string  Unique Identifier referring to a parent transaction record
	 */
	public function getGatewayParentTxn()
	{
		return $this->gateway_parent_txn;
	}
	
	/**
	 * Gets the gateway subscription id of the transaction
	 * 
	 * This parameter is available in recurring payments only.
	 * Gateway subscription id is the unique identifier referring
	 * to the profile id created at payment gateway end for the recurring subscription
	 * 
	 * @see PayplansIfaceApiTransaction::getGatewaySubscriptionId()
	 * 
	 * @return string
	 */
	public function getGatewaySubscriptionId()
	{
		return $this->gateway_subscr_id;
	}
	
	/**
	 * Gets the message of the transaction
	 * 
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Gets the created date of the transaction
	 * 
	 * @return object  XiDate
	 */
	public function getCreatedDate()
	{
		return $this->created_date;
	}
	
	/**
	 * Modifies a key of the transaction, creating it if it does not already exist.
	 *
	 * @param   string  $key      The name of the key.
	 * @param   mixed   $value    The value of the key to set.
	 *
	 * @return  object  PayplansTransaction
	 */
	public function setParam($key, $value)
	{
		XiError::assert($this);
		$this->getParams()->set($key,$value);
		return $this;
	}
	
	/**
	 * Returns a key of the transaction object or the default value if the key is not set.
	 *
	 * @param string  $key       The name of the property.
	 * @param mixed   $default   The default value.
	 * 
	 * @return  mixed   The value of the key.
	 */
	public function getParam($key,$default=null)
	{
		XiError::assert($this);
		return $this->getParams()->get($key,$default);
	}
	
	/**
	 * Gets all tha parameter of the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getParams()
	 * 
	 * @return object XiParameter
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
 	 * Implementing interface Apptriggerable
 	 * @return array
 	 */
	public function getPlans($requireInstance = false)
	{
		return $this->getInvoice(PAYPLANS_INSTANCE_REQUIRE)->getPlans($requireInstance);
	}
	
	/**
	 * Gets the currency of the transaction
	 * 
	 * @see PayplansIfaceApiTransaction::getCurrency()
	 * 
	 * @param string $format
	 * @return string
	 */
    public function getCurrency($format = null)
	{
         return $this->getInvoice(PAYPLANS_INSTANCE_REQUIRE)->getCurrency($format);
	}
}

class PayplansTransactionFormatter extends PayplansFormatter
{
	function getIgnoredata()
	{
		$ignore = array('_trigger', '_component', '_errors', '_name');
		return $ignore;
	}
}