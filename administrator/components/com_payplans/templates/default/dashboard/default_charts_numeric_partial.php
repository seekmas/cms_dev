<?php
/**
* @copyright		Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package			PayPlans
* @subpackage		Backend
* @contact 			payplans@readybytes.in
* website			http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();
?>

<div class="pp-statistics-block">
		<div class="pp-statistics-block-content pp-statistics-block-front" style="top:0px;">
  				<div class="pp-numeric-image ">
  					<?php echo $current_image; ?>
  				</div>
				<div class="pp-gap-top30">
						<div class="pp-numeric-value"><?php echo $current; ?></div>
						<div class="pp-numeric-text"><?php echo $message; ?></div>
				</div>
  		</div>

		<div class="pp-statistics-block-content pp-statistics-block-back" style="top:250px;">
  				<div class="pp-gap-top20">
						<div class="pp-numeric-value" style="font-size:2.5em;"><?php echo $current;?></div>
						<div class="pp-numeric-text"><?php echo XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_CHART_CURRENT');?></div>
				</div>
				<div class="pp-gap-top20">
						<?php 
									$style = 'font-size:1.5em; color:red';
									$image = 'ppdecrease.png';
									if($percentage > 0){
											$style = 'font-size:1.5em; color:#01BF00;';
											$image = 'ppincrease.png';	
									}
									elseif ($percentage == 0){
										$style = 'font-size:1.5em; color:#BCBABA';
										$image = false;
									}
						?>
						<div style="<?php echo $style;?>">
											<?php 
											if($image){
												echo XiHtml::image(JURI::root().'administrator/components/com_payplans/templates/default/_media/images/icons/'.$image, ' ');	
											}?>
											&nbsp;<?php echo $percentage;?>%
						</div>
				</div> 							
				<div class="pp-gap-top20">
						<div class="pp-numeric-value" style="font-size:2.5em;"><?php echo $previous; ?></div>
						<div class="pp-numeric-text"><?php echo XiText::_('COM_PAYPLANS_STATISTICS_NUMERIC_CHART_PREVIOUS');?></div>
				</div>
  		</div>
</div>