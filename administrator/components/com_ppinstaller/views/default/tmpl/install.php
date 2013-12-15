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
//$count = 1;
$steps = Array('uninstall','extract','install');
$this->sub_task = JRequest::getVar('subtask',false);

//index of next sub task
$key = ($this->sub_task)? array_search($this->sub_task, $steps) : count($steps);
//$current task
//$sub_task = $steps[$key-1];
?>
	<div class="clearfix">
		<fieldset class="pp-parameter">
			<legend><?php echo JText::_('COM_PPINSTALLER_INSTALLED_STEPS'); ?></legend>
			
				<?php
						foreach($steps as $index => $step):
							$class_name = 'pp-complete';
							
							if($index > $key  ):
								$class_name = 'pp-pending';
							endif;
								
							if($index == $key ):
								$class_name = 'pp-running';
							endif;
							
							$string = 'COM_PPINSTALLER_'.$step;
							
							?>	
								<div class="push_1 grid_10 pp-row ">
									<div class="pp-col pp-label migration-step <?php echo $class_name; ?>">
										<?php echo JText::_($string); ?>
									</div>
									<div class="pp-col pp-value ">
										<span class="pp-msg"><?php echo (($key-1) == $index)? JText::_($string.'_MSG'):''; ?></span>
									</div> 
								</div>	
					<?php
						endforeach; ?>					
			</fieldset>
		</div>
	
	<input type="hidden" name="option" value="com_ppinstaller"/>
	<input type="hidden" name="task" value="<?php echo $this->nextTask; ?>"/>
	<input type="hidden" name="subtask" value="<?php echo $this->sub_task; ?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />