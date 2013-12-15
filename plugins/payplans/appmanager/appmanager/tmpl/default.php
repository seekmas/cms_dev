<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Backend
* @contact 		payplans@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>

<script src="<?php echo PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'_media'.DS.'js'.DS.'jquery.lazyload.js');?>"	type="text/javascript"></script>
<script	src="<?php echo PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'_media'.DS.'js'.DS.'appmanager.js');?>"		type="text/javascript"></script>
<link 	href="<?php echo PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'_media'.DS.'css'.DS.'appmanager.css');?>"	type="text/css"  rel="stylesheet"/>

<div class="container-fluid pp-appmanager-container">

<?php echo $this->loadTemplate('filter'); ?>

<div id="row-fluid">

<?php
 
	$applist = (array)$applist;
	
	if(count($applist) > 0):
		foreach($applist as $name => $app): 
			$app 			  	= (array) $app;
			$badgeData  	  	= XiText::_("PLG_PAYPLANS_APPMANAGER_ALERT");  
			$badgeColor 	  	= "#BF0B0B";
			$availablePlugin 	= 0;
			$exactAppName 		= $app['extension_type'].'_'.$app['app_folder'].'_'.$app['app_element'].'_'.$app['client_id'];
?>
		<div class="pp-app-block">

			<div class="pp-app-block-content pp-app-block-back">
				<div class="pp-app-block-content-top">
					<div class="pull-left">
						<div>
							<span class="pp-app-block-content-name"><?php echo $app['title'];?></span>
							<div class="clr"></div>
							<span class="pp-app-block-content-type"><?php echo JString::ucfirst($app['app_folder']);?></span>
						</div>							
						<div class="pp-app-block-content-desc">
							<?php echo JString::substr(strip_tags($app['teaser']), 0, 160);?>...<a target="_blank" href="http://www.jpayplans.com/app-ville/item/<?php echo $app['slug'];?>.html"><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_READ_MORE')?></a>
						</div>
					</div>
				</div>
				<div class="pp-app-block-content-bottom">						
					<div class="row-fluid">
						<div class="text-center">
						<?php if(strtolower($app['app_folder']) != 'inbuilt' && !in_array($app['app_name'], array('premiumthemes', 'themebuilder'))):?>
							<?php if(!isset($installed_plugins[$exactAppName])) :?>
							<?php $released_version = isset($versions[$app['extension_type'].'_'.$app['app_folder'].'_'.$app['app_element']]) 
															? $versions[$app['extension_type'].'_'.$app['app_folder'].'_'.$app['app_element']]
															: 0;			
									if($released_version != 0):?>		
											<button class="btn btn-success"
													id="pp-install-<?php echo $app['app_folder'].'-'.$app['app_element'];?>"
													onclick="return payplans.plg.appmanager.install('<?php echo $app['app_folder'];?>', '<?php echo $app['app_element'];?>','<?php echo $app['extension_type'];?>',<?php echo $app['client_id'];?>,'install');">
												<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_BUTTON_INSTALL');?>
											</button>
											<?php $badgeData = XiText::_('PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_AVAILABLE'); $badgeColor = "#DF8E1B"; $availablePlugin = 1;?>
							<?php  else: ?>
										<div class="text-error">
									   		<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_VERSION_NOT_COMPATIBLE');?>		   		
								   		</div>
							<?php  endif;?>
							<?php endif;?>
							
							<?php if(isset($installed_plugins[$exactAppName])) :?>
								<button class="btn btn-danger"
										id="pp-uninstall-<?php echo $app['app_folder'].'-'.$app['app_element'];?>"
										onclick="payplans.url.modal('<?php echo XiRoute::_('index.php?option=com_payplans&view=appmanager&task=uninstall&eid='.$installed_plugins[$exactAppName]->extension_id.'&appType='.$app['app_folder'].'&appName='.$app['app_element'].'&extension_type='.$app['extension_type'].'&client_id='.$app['client_id']);?>'); return false;">
									<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_BUTTON_UNINSTALL');?>							
								</button>
								<?php $badgeData = XiText::_("PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_INSTALLED");  $badgeColor = "#63AF62";?>
							<?php endif;?>
							
							<?php if(isset($installed_plugins[$exactAppName])) : ?>								
								<?php $current_version  = isset($installed_plugins[$exactAppName]->build_version)
															? $installed_plugins[$exactAppName]->build_version
															: 0; ?>
								<?php $released_version = isset($versions[$app['extension_type'].'_'.$app['app_folder'].'_'.$app['app_element']]) 
															? $versions[$app['extension_type'].'_'.$app['app_folder'].'_'.$app['app_element']]
															: 0;?>											
															
								<?php $released_version = explode(".", $released_version); ?>				
								<?php if((int)$released_version[0] && ((int)$released_version[3] > (int)$current_version)) :?>
								<button class="btn btn-primary"
										id="pp-upgrade-<?php echo $app['app_folder'].'-'.$app['app_element'];?>"
										onclick="return payplans.plg.appmanager.install('<?php echo $app['app_folder'];?>', '<?php echo $app['app_element'];?>','<?php echo $app['extension_type'];?>',<?php echo $app['client_id'];?>,'upgrade');">
									<?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_BUTTON_UPGRADE');?>
								</button>	
								<?php $badgeData = XiText::_("PLG_PAYPLANS_APPMANAGER_FILTER_PLUGIN_STATE_UPGRADE");  $badgeColor = "#F89406";?>									
								<?php endif;?>
							<?php endif;?>
						<?php endif;?>
						</div>
					</div>
				</div>									
			</div>
			
			
			<div class="pp-app-block-content pp-app-block-front">
				<div class="pp-app-block-content-top pp-center">
				
					<?php if($app['app_folder']=='inbuilt')
						  {
							 $badgeData=XiText::_("PLG_PAYPLANS_APPMANAGER_INBUILT");  
							 $badgeColor="#0088CC";
						  }?>
						  
					<?php if($availablePlugin != 1):?>	
							<div class="pp-badge" 
								 style="border-color :<?php echo $badgeColor;?>; background-color:<?php echo $badgeColor;?>">
								<?php echo $badgeData;?>
							</div>
					<?php endif;?>
			
					<div class="row-fluid">
						<div class="text-center">
							<img class="lazyimages" alt="<?php echo $app['title'];?>" src="<?php echo PayplansHelperUtils::pathFS2URL(PAYPLANS_PATH_MEDIA.DS.'images'.DS.'cron.png');?>" data-original="<?php echo $app['icon_url'];?>">
						</div>
					</div>
			
					<?php if($app['rating'] != ""):?>
							<div class="pp-rating-center">
								<div class="pp-previous-rating">
									<div class="pp-rating" style="width:<?php echo ((round($app['rating']->value,1))*20);?>%"></div>
								</div>
							</div>
					<?php endif;?>
				</div>
				
				<div class="pp-app-block-content-bottom">
					<div class="pull-left">
						<span class="pp-app-block-content-name"><?php echo $app['title'];?></span>
						<div class="clr"></div>
						<span class="pp-app-block-content-type"><?php echo JString::ucfirst($app['app_folder']);?></span>
					</div>
					<div class="pull-right pp-app-running-status-<?php echo $app['app_folder'].'-'.$app['app_element'];?>">
						</div>	
					</div>
				</div>
				
			</div>	
		
			
<?php 		
		endforeach;
		
	else : ?>
		  
		<div class="text-center muted">
			<h1><?php echo XiText::_('PLG_PAYPLANS_APPMANAGER_NO_APP_FOUND');?></h1>
		</div>
					
<?php endif;?>
</div>
</div>
<?php 
