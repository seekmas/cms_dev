<?php
class PpinstallerHelperPrecheck {
	
	/**
	 * Joomla version must be 
	 * (1.5.0 or upper) or (1.7 or 1.7.x) or (2.5 or 2.5.x)  
	 */
	static function checkJoomlaVersion($version = JVERSION) {
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_JOOMLA_VERSION_RECOMMENDED';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL;
		$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_JOOMLA_VERSION';

		if(-1 == version_compare($version, '1.5.0')){ return $return; }	
		//min limit
		$return['status']  = PPINSTALLER_WARNING_LEVEL;
		// recommended limit
		if( 0 <= version_compare($version, '1.5.20')){
			$return['status']  = PPINSTALLER_SUCCESS_LEVEL;
		}
			
		return $return;
	}
	
	/**
	 * PHP version must be 5.2.x or upper 
	 */
	static function checkPHPVersion($version = PHP_VERSION) {
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_PHP_VERSION_RECOMMENDED';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL; 
		$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_PHP_VERSION'; 
		
		
		if(-1 == version_compare($version, '5.2.0')) return $return;	
		//min limit
		$return['status']  = PPINSTALLER_WARNING_LEVEL;
		// recommended limit
		if( 0 <= version_compare($version, '5.2.9')){
			$return['status']  = PPINSTALLER_SUCCESS_LEVEL;
		}
		
		return $return;
	}
	
	/**
	 * MySQL version must be 5.0 or upper
	 */
	static function checkMySqlVersion($version = '') {
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_MYSQL_VERSION_RECOMMENDED';
		$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_MYSQL_VERSION';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL; 
				
		if(!$version){
			$version	= JFactory::getDbo()->getVersion();
		}
		$version    = explode('.', $version);
		
		if( 5 > (int)$version[0]) return $return;
		 
		
		$return['status']  = PPINSTALLER_SUCCESS_LEVEL; 
		
		return $return;
	}
	
	/**
	 * CURL module is installed or not
	 */
	static function checkCurlModule() {
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_CURL_MODULE_RECOMMENDED';
		$return['msg']    		 = 'COM_PPINSTALLER_COMPATIBILITY_WITH_CURL_MODULE';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL; 
				
		if  (!in_array('curl', get_loaded_extensions())) return $return;
		 
		$return['status']  = PPINSTALLER_SUCCESS_LEVEL; 
		
		return $return;
	}
	
	static function checkMemoryLimit($memoryLimit = '') 
	{
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_MEMORY_LIMIT_RECOMMENDED';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL; 
		$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_MEMORY_LIMIT';
					
		if(!$memoryLimit){
			$memoryLimit = ini_get('memory_limit');
		}
		$unit		 = substr ($memoryLimit, -1);
		
	 	switch ($unit) {
	        case 'M': case 'm': $memoryLimit = (int) $memoryLimit * 1048576; break;
	        case 'K': case 'k': $memoryLimit = (int) $memoryLimit * 1024; break;
	        case 'G': case 'g': $memoryLimit = (int) $memoryLimit * 1073741824; break;
	        default: $memoryLimit = (int) $memoryLimit;  	// already in bytes
	    }
		// min limit 32M
	    if( 33554432 <= $memoryLimit ){
	    	$return['status']  = PPINSTALLER_WARNING_LEVEL;
	    } 
		// recommended limit 64M
	    if( 67108864 <= $memoryLimit){
	    	$return['status']  = PPINSTALLER_SUCCESS_LEVEL;	    	
	    } 
		
		return  $return;
	}
	
	static function executionTime($exeTime = '') 
	{
		$return['recommended']  = 'COM_PPINSTALLER_COMPATIBILITY_WITH_MAX_EXECUTION_TIME_RECOMMENDED';
		$return['status']  		= PPINSTALLER_ERROR_LEVEL; 
		$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_MAX_EXECUTION_TIME';

		if(!$exeTime){
			$exeTime	 = ini_get('max_execution_time');
		}
		
		// recommended limit 30
	    if( 30 <= $exeTime || $exeTime== 0){
	    	$return['status']  = PPINSTALLER_SUCCESS_LEVEL;
	    } 
		return  $return;
	}
	
	/**
	 * If you already have payplans then you must be switch to latest global version of installed version  
	 */
/*	static function old_version()
	{
		//Checking Payplans exist or not
		$isPayPlansExist = PpinstallerHelperBackup::getTables();			
		if(empty($isPayPlansExist)){
			return false;
		}
			
		$old_version = PpinstallerHelperMigrate::getKeyValue('global_version');
		list($major_version,$minor_version,$patch) = explode('.',$old_version);
		$version_tree = PpinstallerHelperUtils::get_version_tree($major_version);
		
		//XiTODO:: patch must not be equal
		if(  $minor_version != $version_tree['minor'] || 
			 ($minor_version == $version_tree['minor'] && $patch < $version_tree['patch'] )
		  ){
		  	$required_version		= "$major_version.{$version_tree['minor']}.{$version_tree['patch']}";
		  	
			$return['msg']     		= 'COM_PPINSTALLER_COMPATIBILITY_WITH_PAYPLANS_VERSION';
			$return['status']  		= PPINSTALLER_ERROR_LEVEL;
			$return['recommended']  = JText::sprintf('COM_PPINSTALLER_COMPATIBILITY_WITH_PAYPLANS_VERSION_RECOMMENDED',$required_version);
			
			return $return;
		}
		
		return false;
	}
*/
	
}
