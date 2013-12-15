<?php

if(defined('_JEXEC')===false) die();

if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}

$version = new JVersion();

if($version->RELEASE==='1.6') define('PPINSTALLER_16', true);
if($version->RELEASE==='1.7') define('PPINSTALLER_17', true);
if($version->RELEASE==='2.5') define('PPINSTALLER_25', true);
if($version->RELEASE >= '3.0') define('PPINSTALLER_30', true);

define('PPINSTALLER_VERSION','3.0.6');
define('PPINSTALLER_PAYPLANS_VERSION','3.0.6');
define('PPINSTALLER_PAYPLANS_REVISION','4045');
 
define('PPINSTALLER_EXTENSION_PATH',	dirname(__FILE__).DS.'extension');
define('PPINSTALLER_HELPER_PATH',		dirname(__FILE__).DS.'helpers');
define('PPINSTALLER_MODELS_PATH',		dirname(__FILE__).DS.'models');
define('PPINSTALLER_LOGGER_PATH',		JPATH_ROOT.DS.'tmp'.DS.'ppinstaller_logs');
define('PPINSTALLER_MIGRATER_PATH',		dirname(__FILE__).DS.'models'.DS.'migrate');
// Defing Compression type
define('PPINSTALLER_COMPRESSION_TYPE', 'zip');

//PayPlans Kit
define('PPINSTALLER_PAYPLANS24', PPINSTALLER_EXTENSION_PATH.DS.'payplans24');
define('PPINSTALLER_PAYPLANS30', PPINSTALLER_EXTENSION_PATH.DS.'payplans30');
define('PPINSTALLER_TMP_PAYPLANS24', JFactory::getConfig()->get('tmp_path').DS.'payplans24');
define('PPINSTALLER_TMP_PAYPLANS30', JFactory::getConfig()->get('tmp_path').DS.'payplans30');

// media files
define('PPINSTALLER_MEDIA',	JURI::base().'components'.DS.'com_ppinstaller'.DS.'assets');
define('PPINSTALLER_URL_MEDIA',	JURI::base().'components/com_ppinstaller/assets');
define('PPINSTALLER_STYLE',	PPINSTALLER_URL_MEDIA.'/css/');
define('PPINSTALLER_JS',	PPINSTALLER_URL_MEDIA.'/js/');
define('PPINSTALLER_IMG',	PPINSTALLER_URL_MEDIA.'/images/');



// Error Constant 
define('PPINSTALLER_SUCCESS_LEVEL',		0);
define('PPINSTALLER_WARNING_LEVEL', 	10);
define('PPINSTALLER_ERROR_LEVEL', 		20);
define('PPINSTALLER_CRITICAL_LEVEL',	30);

//Migration Constant
define('PPINSTALLER_BACKUP_CREATED',		10);
define('PPINSTALLER_MIGRATION_START', 		20);
define('PPINSTALLER_MIGRATION_IN_PROCESS',	30);
define('PPINSTALLER_MIGRATION_SUCCESS', 	100);

// General purpose const
define('PPINSTALLER_LIMIT', 			500);
define('PPINSTALLER_CRITICAL_LIMIT', 	100);
define('PPINSTALLER_PATCH_LIMIT', 	20);
// name of session variable
define('PPINSTALLER_REQUIRED_PATCHES', 	'required_patches');	

//load controller
require_once dirname(__FILE__).DS.'controller.php';
// Others required library
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');

// Load helper files	
$files	=	JFolder::files(PPINSTALLER_HELPER_PATH,".php$");
foreach($files as $file ){
	$className 	= 'PpinstallerHelper'.JFile::stripExt($file);
	JLoader::register($className, PPINSTALLER_HELPER_PATH.DS.$file);
}

// Load Models files
$files	=	JFolder::files(PPINSTALLER_MODELS_PATH,".php$");
foreach($files as $file ){
	$className 	= 'PpinstallerModel'.JFile::stripExt($file);
	JLoader::register($className, PPINSTALLER_MODELS_PATH.DS.$file);
}

// Import library dependencies
jimport('joomla.filesystem.archive');

//IMP:: use after loaded helper file
$major_version	 = PpinstallerHelperUtils::version_level(PPINSTALLER_PAYPLANS_VERSION , 'major'); 
$minior_version  = PpinstallerHelperUtils::version_level(PPINSTALLER_PAYPLANS_VERSION , 'minor');

define('PPINSTALLER_PAYPLANS_KIT_SUFFIX', $major_version.$minior_version);