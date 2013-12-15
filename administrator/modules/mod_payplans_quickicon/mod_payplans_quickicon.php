<?php
/**
* @package    Payplans Quick Icons
* @author     Jitendra
* @copyright  Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license    GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//If Payplans not Enabled or Not Installed then nothing to do.
if(!JComponentHelper::getComponent('com_payplans', true)->enabled || !defined('PAYPLANS_LOADED')){
	return true;
}
	$dashBoard  = XiText::_('MOD_PAYPLANS_QUICK_ICON_DASHBOARD');
	$appManager = XiText::_('MOD_PAYPLANS_QUICK_ICON_APP_MANAGER');
	$config		= XiText::_('MOD_PAYPLANS_QUICK_ICON_CONFIG');

	//Create an array of quick icons detail
	$record = array();   //'Dashboard','Appmanager'
	$record = array($dashBoard	   =>	array('route' => XiRoute::_route('index.php?option=com_payplans'),
										  	  'icon'  => PAYPLANS_PATH_TEMPLATE_ADMIN.'/default/_media/images/icons/48/payplans.png',
	 									  	  'alt'   => XiText::_('MOD_PAYPLANS_QUICK_ICON_DASHBOARD_ALT_TEXT')
										),
					$appManager	   =>	array('route' => XiRoute::_route('index.php?option=com_payplans&view=appmanager'),
											  'icon'  => PAYPLANS_PATH_TEMPLATE_ADMIN.'/default/_media/images/icons/48/appmanager.png',
	 									  	   'alt'  => XiText::_('MOD_PAYPLANS_QUICK_ICON_APPMANAGER_ALT_TEXT')
										),
					$config		   =>	array('route' => XiRoute::_route('index.php?option=com_payplans&view=config'),
									    	  'icon'  => PAYPLANS_PATH_TEMPLATE_ADMIN.'/default/_media/images/icons/48/config.png' ,
	 									      'alt'	  => XiText::_('MOD_PAYPLANS_QUICK_ICON_CONFIG_ALT_TEXT')
										)
					);
		
	foreach ($record as $key => $value): 
	  if(XI_JVERSION >= '30'){ ?>
		<div class="row-striped">
			 <div class="row-fluid">
			       <div class="span12">
<?php }
      else { ?>
          <div id="cpanel">
			   <div class="icon-wrapper" style="float:left;">
			       <div class ="icon">
<?php  } ?>
<!--			    Creates a link. When visitor clicks on the icon then redirected to this link. -->
					<a href="<?php echo $value['route'];?>">
<!--				   Image to be shown on Quick icon-->
					   <?php echo XiHtml::image(XiHelperTemplate::mediaURI($value['icon'], false), $value['alt']) ;?>
<!--					  Shows a label in Icon like Dashboard and Appmanager					   -->
					   	  <span><?php echo $key;?></span>
					</a>
				 </div>
			  </div>
		</div>
<?php  endforeach;?>
