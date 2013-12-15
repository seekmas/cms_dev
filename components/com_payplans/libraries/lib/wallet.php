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
 * Wallet Class
 * 
 * @author Gaurav Jain
 * @since 2.0
 *
 */
class PayplansWallet extends XiLib
{
	/**
	 * Auto Increment Primary Key of Table
	 * @var integer
	 * @since 2.0
	 */
	protected $wallet_id = 0;
	
	/**
	 * User id of user for which current wallet transaction is being processed
	 * @var integer
	 * @since 2.0
	 */
	protected $user_id = 0;
	
	/** 
	 * This will contain the transaction record id. 
	 * @var integer
	 * @since 2.0
	 */
	protected $transaction_id = 0;
	
	/**
	 * This field contians the amount of wallet transaction.
	 * This will be positive when it is a credit in wallet.
	 * This will be negative when it is a debit in wallet.
	 * @var float
	 * @since 2.0
	 */
	protected $amount = 0.00;
	
	/**
	 * Invoice id for which wallet instance has been created
	 * @var integer
	 * @since 2.0
	 */
	protected $invoice_id = 0;
	
	/**
	 * This field contains the message related to wallet transaction.
	 * This field is optional.
	 * @var string
	 * @since 2.0
	 */
	protected $message = '';
	
	/**
	 * Wallet transaction creation date
	 * @var XiDate
	 * @since 2.0
	 */
	protected $created_date = null;
	
	/**
	 * Wallet transaction modification date
	 * @var XiDate
	 * @since 2.0
	 */
	protected $modified_date = null;
	
	public function reset(Array $config=array())
	{
		$this->wallet_id 		= 0;
		$this->user_id			= 0;
		$this->transaction_id	= 0;
		$this->amount			= 0.00;
		$this->message			= '';
		$this->invoice_id		= 0;
		$this->created_date		= new XiDate();
		
		return $this;
	}
	
	/**
	 * 
	 * Get instance of Transaction
	 * @param $id
	 * @param $type
	 * @param $bindData
	 * @param string $dummy is added just for removing warning with development mode(XiLib::getInstance is having 4 parameters)
	 * @return PayplansWallet
	 * @since 2.0
	 */
	static public function getInstance($id=0, $type=null, $bindData=null, $dummy=null)
	{
		return parent::getInstance('wallet', $id, $type, $bindData);
	}
	
	/**
	 * Gets the attached transaction of the wallet
	 * 
	 * @param boolean $instanceRequire  Optional paramter to get the instance of the attached transaction
	 * @return mixed TransactionId or object of PayplansTransaction for TransactionId
	 */
	public function getTransaction($instanceRequire = false)
	{
		if($instanceRequire == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansTransaction::getInstance($this->transaction_id);
		}
		
		return $this->transaction_id;
	}
	
	/**
	 * Gets the amount of the wallet
	 * @return float  Value of the amount
	 */
	public function getAmount()
	{
		return PayplansHelperFormat::price($this->amount);
	} 

	/**
	 * Gets the buyer/user of the wallet
	 * @param boolean $requireinstance  If True return PayplansUser instance else Userid 
	 * 
	 *  @return mixed Userid or instance of PayplansUser attached with the user
	 */
	public function getBuyer($requireinstance=false)
	{
		if($requireinstance == PAYPLANS_INSTANCE_REQUIRE){
			return PayplansUser::getInstance($this->user_id);
		}
		
		return $this->user_id;
	}
	
	/**
	 * Gets the invoice attached to the wallet
	 * 
	 * This invoice id is the one which has consumed the amount from wallet 
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
	 * Save the wallet
	 * 
	 * Triggers the event if there is any amount +/- exists on the attached transaction 
	 * @see XiLib::save()
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
		
		// if wallet already exists then do not require to update wallet records
		if(!$isNew){
			return true;
		}
		
		// get the transaction 
		$transaction = $this->getTransaction(PAYPLANS_INSTANCE_REQUIRE);
		$amount = $this->getAmount();
		
		if(floatval($amount) != floatval(0)){
			// trigger the event 
			$args = array($transaction, $amount);
			PayplansHelperEvent::trigger('onPayplansWalletUpdate', $args);
		}
	}
	
	public function getObjectLink()
	{
		return __CLASS__;
	}
}