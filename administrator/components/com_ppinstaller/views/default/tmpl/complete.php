<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_ppinstaller
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

// no direct access
defined('_JEXEC') or die;

// load broadcast message
$version = new JVersion();
$suffix = 'jom=J'.$version->RELEASE.'&utm_campaign=broadcast&pay=PP'.PAYPLANS_VERSION.'&dom='.JURI::getInstance()->toString(array('scheme', 'host', 'port'));
?>
	<div class="clearfix">
			<fieldset class="pp-parameter">
				<legend><?php echo JText::_('COM_PPINSTALLER_COMPLETED'); ?></legend>
			</fieldset>	
			
			<div class="clearfix pp-body">
				<div class="pp-complete-msg">
					   <?php echo JText::_('COM_PPINSTALLER_CONGRATULATION');?>
				</div>
				
				<div class="pp-ponder-message">
					<div class="pp-message-title">
						<?php echo JTEXT::_('COM_PPINSTALLER_POINTS_TO_PONDER')?>
					</div>
					<div class="pp-message-body">
					   <?php echo JText::_('COM_PPINSTALLER_PONDER_MSG');?>
					 </div>
				</div>
				
			</div>						
		</div>
		

	<input type="hidden" name="option" value="com_ppinstaller" />
	<input type="hidden" name="task" value="disable" />
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
	<div style="display:none;">    
		<iframe src='http://pub.jpayplans.com/installation.html?<?php echo $suffix?>' frameborder="0" scrolling="auto" width="0px" height="0px"></iframe>
	</div>
