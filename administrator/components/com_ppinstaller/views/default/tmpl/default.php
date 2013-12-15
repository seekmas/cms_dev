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

$versions = Array(0=>array('value'=>20,'text'=>'2.0.0'));
?>

	<fieldset class="clearfix pp-header">
		<legend><?php echo JText::_('COM_PPINSTALLER_PP_REQUIRMENTS'); ?></legend>
		<div class="pp-description"><?php echo JText::_('COM_PPINSTALLER_PP_REQUIRMENTS_DESC'); ?></div>
	</fieldset>
					
	<div class="clearfix pp-body">
	
		<div class="prefix_1 suffix_1 clearfix">
			<table class="adminlist plan-grid">
				<thead>
					<tr>
						<th><?php echo JText::_('Requirments'); ?></th>
						<th><?php echo JText::_('Status'); ?></th>
					</tr>
				</thead>
				
				<tbody>
					<?php
						foreach ($this->results as $result) : ?>
						<tr class="pp-row">
							<td>
								<div><?php echo JText::_($result['msg']); ?> </div>
								<span class='clearfix pp-msg'><?php echo (isset($result['recommended']))
																		?JText::_($result['recommended'])
																		:'';
																?>
								</span>
							</td>
							<td>
								<?php 
										
										$suffix = ($result['status'] == PPINSTALLER_SUCCESS_LEVEL) 
													? 'success' 
													: (($result['status']== PPINSTALLER_WARNING_LEVEL) ? 'warning': 'error');
									
										$class  = 'ppi-badge ppi-badge-'.$suffix;
								?>
								<?php $status = ($result['status']==PPINSTALLER_SUCCESS_LEVEL) ? 'Ok':'No'; ?>
								<span class="<?php echo $class; ?>" title="<?php echo $suffix; ?>"><?php echo $status; ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				
				<tfoot>
					<tr><td colspan="2">&nbsp;</td></tr>
				</tfoot>
			</table>
		</div>
		
		
		<!--<div class="clearfix">
			<fieldset class="pp-parameter">
				<legend><?php echo JText::_('COM_PPINSTALLER_SELECT_PP_VERSION'); ?></legend>
				<div class="push_1 grid_10 pp-row">
					<div class="pp-col pp-label">
						<?php echo JText::_('COM_PPINSTALLER_SELECT_PP_VERSION'); ?>
					</div>
					<div class="pp-col pp-value">
						<?php echo JHTML::_('select.genericlist',$versions,'ppinstaller_version') ?>
						<span class="clearfix pp-msg">
					
							<?php echo JText::_('COM_PPINSTALLER_PP_VERSION_DESC'); ?>
						</span>
					</div> 
				</div>						
			</fieldset>	
		</div>

	--></div>

	<input type="hidden" name="option" value="com_ppinstaller" />
	<input type="hidden" name="task" value="<?php echo $this->nextTask; ?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
