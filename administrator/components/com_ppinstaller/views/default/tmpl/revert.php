<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans-Installer
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/

// no direct access
defined('_JEXEC') or die;
?>

	<fieldset class="clearfix pp-header">
		<legend><?php echo JText::_('COM_PPINSTALLER_RESTORE_BACKUP'); ?></legend>
		<div class="pp-description"> 	</div>
	</fieldset>

	<div class="clearfix pp-body">
		<div class="pp-message">
			<?php echo JText::_('COM_PPINSTALLER_SUCCESSFULLY_RESTORED_BACKUP');?>
		</div>
	</div>	
	
	<input type="hidden" name="option" value="com_ppinstaller" />
	<input type="hidden" name="task" value="<?php echo $this->nextTask?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
