<?php

/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @contact		shyam@joomlaxi.com
* @package		Payplans
* @subpackage		Plugin
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgPayplansmigrationSample extends XiPluginMigration
{	
	protected $_location   = __FILE__;
	
	// component name
	protected $_component 	= 'sample';
	protected $_title		= 'sample';
	
	//payment from these apps will be migrated
	protected $_appMapper = array();

	public function _isAvailable(Array $options= array())
	{
		return true;
	}
	
	protected function _estimateRecords()
	{
		$records = 50;
		$this->_helper->write('record_count',$records);
		
		return $records;
	}

	protected function _migrateTables()
	{
		$query	= new XiQuery();
					
		//truncate tables
		$query->truncate('#__payplans_plan')->dbLoadQuery()->query();
		$query->truncate('#__payplans_app')->dbLoadQuery()->query();
		$query->truncate('#__payplans_planapp')->dbLoadQuery()->query();
		$query->truncate('#__payplans_order')->dbLoadQuery()->query();
		$query->truncate('#__payplans_subscription')->dbLoadQuery()->query();
		$query->truncate('#__payplans_payment')->dbLoadQuery()->query();
		$query->truncate('#__payplans_group')->dbLoadQuery()->query();
		$query->truncate('#__payplans_plangroup')->dbLoadQuery()->query();
		$query->truncate('#__payplans_config')->dbLoadQuery()->query();
		$query->clear();
		
		$dir = dirname($this->_location).DS.'sample'.DS;
		XiHelperPatch::changePluginState('parentchild', 1, 'payplans');
		XiHelperPatch::changePluginState('upgrade', 1, 'payplans');
		XiHelperPatch::changePluginState('userdetail', 1, 'payplans');
		XiHelperPatch::changePluginState('discount', 1, 'payplans');
		XiHelperPatch::changePluginState('basictax', 1, 'payplans');
		
		$sampleData = XiFactory::getSession()->get('sampleDataType', 'Bs');
		
        XiHelperPatch::applySqlFile($dir.'install'.ucfirst($sampleData).'_16.sql');


		// setup next functions
		$this->_scheduleNextFunction('_migrationComplete');
		return 50;
	}
	
	protected function _doMigration(){
		$sampleData = JRequest::getVar('sampleData',null);
		if($sampleData !== null){
			XiFactory::getSession()->set('sampleDataType', $sampleData);
		}
		parent::_doMigration();
	}
	protected function _postMigration()
	{
		XiFactory::getSession()->clear('sampleDataType');
		parent::_postMigration();
	}
}
