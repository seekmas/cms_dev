<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


class PayplansModelOrder extends XiModel
{
	//protected $_hasone  = array('orderfield' => array('foreignKey'=>'order_id'));
	protected $_hasmany = array('orderitem' => array('foreignKey'=>'order_id'));

	public $filterMatchOpeartor = array(
										'status'	=> array('='),
										'total'		=> array('>=', '<=')
										);
	
	// XITODO : Apply validation when it is applied all over
	function validate(&$data, $pk=null,array $filter = array(),array $ignore = array())
	{
		return true;
	}
	
/*
	 * Count number of total records as per current query
	 * clean the query element
	 */
	public function getTotal($queryClean = array('select','limit','order'))
	{
		//for pagination in frontend
		//order total is calculated for the user logged in
		if(XiFactory::getApplication()->isSite()){
			$userId =  XiFactory::getUser()->id;
			$query = $this->getQuery();
	
			//Support query cleanup
			$tmpQuery = $query->getClone();
	
			foreach($queryClean as $clean){
				$tmpQuery->clear(JString::strtolower($clean));
			}
	     
	        $tmpQuery->select('COUNT(*)')				 
					 ->where('buyer_id = '.$userId);
	        $this->_total 	= $tmpQuery->dbLoadQuery()->loadResult();
	        
			return $this->_total;
		}
		else
			return parent::getTotal();
	}
	
	/**
	 * find all orders 
	 * which are older from given time
	 */
	public function getDummyOrders(XiDate $time=null, $status=PayplansStatus::NONE, $subStatus = PayplansStatus::NONE)
	{
		$query = $this->getQuery();

		//there might be no table and no query at all
		if($query === null )
			return array(null);

		if($time === null){
		 	$time = new XiDate();
		}
		
		$valueOfStatus = implode(',', $status);
		
		$tmpQuery = $query->getClone();
		$tmpQuery->clear('where')
				 ->innerJoin('`#__payplans_subscription` as s on s.`order_id` = tbl.`order_id`')
				 ->where('s.`status`= '.$subStatus)
				 ->where("`tbl`.`modified_date` < '".$time->toMySQL()."'")
				 ->where('`tbl`.`status` IN ('.$valueOfStatus.')');
		
		return $tmpQuery->dbLoadQuery()->loadObjectList($this->getTable()->getKeyName());
	}
}

class PayplansModelformOrder extends XiModelform {}