<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Migration
* @contact 		payplans@readybytes.in
*/

class Migrate24 extends PpinstallerModelMigrate
{
	protected  $action = Array(
							'migratePlans',
							'migrateSubscriptions',
							'migrateInvoices',
							'migrateOrders',
							'migrateDiscounts',
							'migrateParentchild',
							'migrateAdvancedpricing',
							'migrateGroups',
							'migrateTransactions',
							'migratePayments',
							'migrateApps',
							'migrateUsers',
							'migrateConfig',
							'migrateLogs'
						 );
						 
						 
						 
	function __construct($config=array()) 
	{
		foreach ($this->action as $index=>$fun ){
			$this->action[$fun] = JText::_('COM_PPINSTALLER_MIGRATE_'.strtoupper(substr($fun, 7)));
			unset($this->action[$index]);  
		}
		
		parent::__construct($config);
	}
	
	function before()
	{
		//uninstall premimum plugin
		$plugin_id = PpinstallerHelperInstall::plugin_id('payplanspremium', 'payplans');
			
		if(empty($plugin_id)){
			return parent::before();
		}
		
		PpinstallerHelperInstall::uninstall_extension('plugin',$plugin_id);
				
		return parent::before();
	}
	
	function migratePlans()
	{
		return $this->_migrateRecords('#__payplans_plan', 'plan_id', array('params','details'));
	}
	
	function migrateSubscriptions()
	{
		return $this->_migrateRecords('#__payplans_subscription', 'subscription_id', array('params'));
	}
	
	function migrateInvoices()
	{
		return $this->_migrateRecords('#__payplans_invoice', 'invoice_id', array('params'));
	}
	
	function migrateOrders()
	{
		return $this->_migrateRecords('#__payplans_order', 'order_id', array('params'));
	}
	
	function migrateTransactions()
	{
		return $this->_migrateRecords('#__payplans_transaction', 'transaction_id', array('params'));
	}
	
	function migrateUsers()
	{
		return $this->_migrateRecords('#__payplans_user', 'user_id', array('params','preference'));
	}
	
	function migratePayments()
	{
		return $this->_migrateRecords('#__payplans_payment', 'payment_id', array('params','gateway_params'));
	}
	
	function migrateApps()
	{
		return $this->_migrateRecords('#__payplans_app', 'app_id', array('core_params','app_params'));
	}
	
	
	
	function migrateDiscounts()
	{
		if(!PpinstallerHelperMigrate::isTableExist('#__payplans_prodiscount')){
			return array();
		}
		
		return $this->_migrateRecords('#__payplans_prodiscount', 'prodiscount_id', array('params'));
	}
	
	function migrateParentchild()
	{
		if(!PpinstallerHelperMigrate::isTableExist('#__payplans_parentchild')){
			return array();
		}
		
		return $this->_migrateRecords('#__payplans_parentchild', 'dependent_plan', array('params'));
	}
	
	function migrateAdvancedpricing()
	{
		if(!PpinstallerHelperMigrate::isTableExist('#__payplans_advancedpricing')){
			return array();
		}
		
		return $this->_migrateRecords('#__payplans_advancedpricing', 'advancedpricing_id', array('params'));
	}
	
	function migrateGroups()
	{
		return $this->_migrateRecords('#__payplans_group', 'group_id', array('params'));
	}
	
	function migrateConfig()
	{
		//previous table have column 'config', first check for that
		$db  = JFactory::getDBO();
		$sql = "SHOW COLUMNS FROM `#__payplans_config` LIKE 'config'";
		$db->setQuery($sql);
		$result = $db->loadResult();
		
		if(empty($result)){
			return array();
		}
		
		//select config data from table and convert it to new format
		$query = "select `config` from `#__payplans_config`";
        $db->setQuery($query);
        $result = $db->loadAssocList();
       
        $data      = array();
        $newConfig = '';
        foreach ($result as $config){
            $newConfig .= $config['config'];           
        }
        $data = (array)PpinstallerHelperMigrate::stringToObject($newConfig);
       
        //drop previous table and create new if required
        $query = 'Drop table `#__payplans_config`';
        $db->setQuery($query)->query();
        $query = 'CREATE TABLE IF NOT EXISTS `#__payplans_config` (
                   `config_id` int(11) NOT NULL AUTO_INCREMENT,
                   `key` varchar(255) NOT NULL,
                   `value` text,
                    PRIMARY KEY (`config_id`),
                    UNIQUE KEY `idx_key` (`key`)
                  ) ENGINE=MyISAM  
                  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;';
       
        if($db->setQuery($query)->query()){
            $query  =  "INSERT INTO `#__payplans_config` (`key`, `value`) VALUES ";
            $queryValue = array();
           
            foreach ($data as $key => $value){
                if(is_array($value)){
                    $value  = json_encode($value);
                }
                $queryValue[] = "(".$db->quote($key).",". $db->quote($value).")";
            }
            $query .= implode(",", $queryValue);
           
            $db->setQuery($query)
                         ->query();

			return array();
        }

		return array();
	}
	
	function migrateLogs()
	{
		$db     	= JFactory::getDBO();
		$sql 		= 'show columns from #__payplans_log';
		$db->setQuery($sql);

		$columns 	= $db->loadColumn();

		if(!in_array('position', $columns)){
			$sql 	= "ALTER table ". $db->quoteName('#__payplans_log')
		 		 	."ADD `owner_id`  int(11) NOT NULL,
		 		 	  ADD `position` TEXT NULL,
					  ADD `previous_token` TEXT NULL,
					  ADD `current_token` TEXT NULL";
	     	
		 	$db->setQuery($sql);
			
			if(!$db->query()){
				return array();
			}
		}
		 return array();
	}
	
	function _migrateRecords($tableName, $primaryKey, $updatingColumn)
	{
		$db = JFactory::getDbo();
		
		$this->loadData($tableName, $primaryKey);
		
		$this->query = "SELECT * from `$tableName`";
		$limit       = "LIMIT {$this->start}, ".PPINSTALLER_CRITICAL_LIMIT;
		$db->setQuery($this->query . $limit);
		
		$records = $db->loadObjectList($primaryKey);
		
		//do nothing if no record exist
		if(empty($records)){
			return array();
		}
		
		//create update query for updating all the current records
		list($record_ids, $sql) = $this->_createQuery($records,$updatingColumn,$tableName,$primaryKey);
		
		//if there is no query then do nothing
		if(!$sql){
			return array();
		}
		
		$this->is_success = $db->setQuery($sql)->query();
		
		PpinstallerHelperLogger::log(JText::sprintf('COM_PPINSTALLER_UPDATE_COLUMN "'.implode(',', $updatingColumn).'" of '.$tableName)," $primaryKey => $record_ids ".$db->errorMsg());
			
		$this->msg = $tableName.' migrated';
		
		return array();
	}
	
	function _createQuery($records,$updatingColumns, $tableName, $uniqueColumn)
	{
		$sql = "UPDATE `$tableName` SET ";
		$db  = JFactory::getDBO();
		
		//have to work for each column of the table, which is needed to be migrated
		foreach ($updatingColumns as $updatingColumn){
			$sql .= "`$updatingColumn` = CASE $uniqueColumn ";
			
			foreach ($records as $record_id => $record){
				$iniParams  = $record->$updatingColumn;
				if ((substr($iniParams, 0, 1) != '{') && (substr($iniParams, -1, 1) != '}'))
				{	
					$object = PpinstallerHelperMigrate::stringToObject(addslashes($iniParams));
					$jsonParams[$record_id] = json_encode($object);
				}
				else{
					continue;
				}
				
				$sql      .= "WHEN $record_id THEN".$db->quote($jsonParams[$record_id]);	
			}
			
			$sql .= "END,";
		}
		
		if(!isset($jsonParams)){
			return array(false,false);
		}
		
		//remove last ','
		$sql 		= rtrim($sql,',');
		
		$record_ids = implode(',', array_keys($jsonParams));
		$sql       .= " WHERE $uniqueColumn IN ($record_ids)";
		
		return array($record_ids,$sql);
	}
	
	function after()
	{
		$db		= JFactory::getDBO();
		$query = array();
		
		$query[] = 'UPDATE #__payplans_support'
				  .' SET '. $db->quoteName('value') .' = '.$db->Quote('3.0.6').' WHERE '. $db->quoteName('key') .' = '.$db->Quote('global_version');
		
		$query[]	= 'UPDATE #__payplans_support'
				  .' SET '. $db->quoteName('value') .' = '.$db->Quote('4045').' WHERE '. $db->quoteName('key') .' = '.$db->Quote('build_version');

		foreach($query as $value){
			$db->setQuery($value);
			if(!$db->query())
				return false;
		}
		
		return parent::after();
	}
}