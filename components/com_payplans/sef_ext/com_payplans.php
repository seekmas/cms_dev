<?php
/**
 * sh404SEF support for Payplans component.
 * @author      $Author: Gaurav
 * @copyright   JPayplans.com 2011
 * @package     payplans
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
if (class_exists('shRouter')) {
    $sefConfig = shRouter::shGetConfig();
} 
else {
    $sefConfig = Sh404sefFactory::getConfig();
}
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin($lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------


// load JS language strings. If we are creating urls on the
// fly, after an automatic redirection, they may not be loaded yet
$lang =& JFactory::getLanguage();
$lang->load('com_payplans');

// real start
$Itemid = isset($Itemid) ? $Itemid : null;
$limit = isset($limit) ? $limit : null;
$limitstart = isset($limitstart) ? $limitstart : null;

// main vars
$option = isset($option) ? $option : null;
$view = isset($view) ? $view : 'plan';
$task = isset($task) ? $task : null;

$group_id = isset($group_id) ? $group_id : null;
$plan_id = isset($plan_id) ? $plan_id : null;

// insert component name from menu
$shPPName = shGetComponentPrefix($option); 
$shPPName = empty($shSampleName) ?  
    getMenuTitle($option, $task, $Itemid, null, $shLangName) : $shPPName;
$shPPName = (empty($shPPName) || $shPPName == '/') ? 'PP':$shPPName;



// item id work
if(!function_exists('_getPayplansMenus')) {
	function _getPayplansMenus(){
		static $menus = null;

		if($menus ===null){
            $menus 	= JSite::getMenu()->getItems('component_id',JComponentHelper::getComponent('com_payplans')->id);
		}
	
		return $menus;
	}
}

if(!function_exists('_getPayplansUrlVars')) {
	function _getPayplansUrlVars()
	{
		return array('view', 'task', 'plan_id', 'order_id', 'payment_id', 'app_id', 'subscription_id', 'user_id');
	}	
}

if(!function_exists('_findPayplansMatchCount')) {
	function _findPayplansMatchCount($menu, $query)
	{
		$vars = _getPayplansUrlVars();
		$count = 0;
		foreach($vars as $var)
		{
			//variable not requested
			if(!isset($query[$var]))
				continue;
	
			//variable not exist in menu
			if(!isset($menu[$var]))
				continue;
	
			//exist but do not match
			if($menu[$var] !== $query[$var]){
				/* 
				 * return 0, because if some variables are in conflict
				 * then variable appended in query will be desolved during parsing 
				 * e.g.
				 * 
				 * index.php?option=com_payplans&view=plan
				 * index.php/subscribe
				 * 
	 			 * index.php?option=com_payplans&view=plan&task=subscribe&plan_id=1
				 * index.php/subscribe1
				 * 
				 * index.php?option=com_payplans&view=plan&task=subscribe&plan_id=2
				 * index.php/subscribe1?plan_id=2   <== *** WRONG ***
				 * index.php/subscribe?task=subscribe&plan_id=2   <== *** RIGHT ***
				 */ 
				return 0;
			}
	
			$count++;
		}
		return $count;
	}
}

$ppmenus = _getPayplansMenus();

	//If item id is not set then we need to extract those
	$selMenu = null;
	
	$query = array();
	$query['task'] = $task;
	$query['view'] = $view;
	$query['option'] = $option;
	
	if (empty($Itemid) && $ppmenus)
	{
		$count 		= 0;
		$selMenu 	= $ppmenus[0];

		foreach($ppmenus as $menu){
			//count matching
			$matching = _findPayplansMatchCount($menu->query,$query);

			if($count >= $matching)
				continue;

			//current menu matches more
			$count		= $matching;
			$selMenu 	= $menu;
		}

		//assig ItemID of selected menu
		$Itemid = $selMenu->id;
	}

	if(!empty($Itemid)){
		$shAppendString = '&Itemid='.$Itemid;  // append current Itemid
    	shAddToGETVarsList('Itemid', $Itemid);
	}
    
    
    
// build url first based on view, but make use of other vars ($task,..) as needed

if( !empty( $view)) {
	$title[] = $view;
}
// add more details based on $task
if( !empty( $task)) {
	$title[] = $task;
	}

shRemoveFromGETVarsList('view');
shRemoveFromGETVarsList('task');


shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if(!empty($Itemid)) {
  shRemoveFromGETVarsList('Itemid');
}
if(!empty($limit)) {
  shRemoveFromGETVarsList('limit');
}
if(isset($limitstart)) {
  shRemoveFromGETVarsList('limitstart');
}

if(!empty($group_id) && $group_id <=0) {
  shRemoveFromGETVarsList('group_id');
}
if(!empty($plan_id) && $plan_id <= 0 ) {
  shRemoveFromGETVarsList('plan_id');
}

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef){
  $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
  (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
  (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------

