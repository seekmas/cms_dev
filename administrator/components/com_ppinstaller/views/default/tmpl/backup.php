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

	<fieldset class="clearfix pp-header">
			<legend><?php echo JText::_('COM_PPINSTALLER_BACKUP'); ?></legend>
			<div class="pp-description"> 	</div>
	</fieldset>
	
	<div class="clearfix pp-body">
		<div class="pp-message">
			<?php echo JText::_('COM_PPINSTALLER_PP_BACKUP_CREATED');?>
		</div>
	</div>	
	
			
	<input type="hidden" name="option" value="com_ppinstaller"/>
	<input type="hidden" name="task" value="<?php echo $this->nextTask?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
