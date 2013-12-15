<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

// no direct access
defined('_JEXEC') or die;
?>

	<div class="clearfix">
			<fieldset class="pp-parameter">
				<legend><?php echo JText::_('COM_PPINSTALLER_MIGRATION_STEPS'); ?></legend>
				<?php
					$flag = false;
					foreach ($this->action as $fun=>$migrate_step):
						$class_name = 'pp-complete';
						if(!empty($flag)):
							$class_name = 'pp-pending';
						endif;
						
						if($fun === $this->migrateAction ):
							$class_name = 'pp-running';
							$flag = true;
						endif;
					?>
					
					<div class="push_1 grid_10 pp-row ">
						<div class="pp-col pp-label migration-step <?php echo $class_name; ?>">
							<?php echo $migrate_step; ?>
						</div>
						<div class="pp-col pp-value ">
							<span class="pp-msg"><?php echo ($this->currentAction == $fun)? $this->msg:''; ?></span>
						</div> 
					</div>	
				<?php endforeach;?>					
			</fieldset>	
		</div>
		
	<input type="hidden" name="option" value="com_ppinstaller"/>
	<input type="hidden" name="task" value="<?php echo $this->nextTask?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
	<input type="hidden" name="migrateAction" value="<?php echo $this->migrateAction; ?>" />
	<input type="hidden" name="start" value="<?php echo JRequest::getVar('start',0); ?>" />
	