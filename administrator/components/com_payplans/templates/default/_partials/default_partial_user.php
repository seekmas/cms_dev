<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();?>

<fieldset class="form-horizontal">
<legend><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_DETAILS' ); ?></legend>

<?php if(empty($user)) : ?>
	<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_NO_USER'); ?>
<?php else : ?>
<div class="pp-partial-user">
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_ID') ?>  
		</div>
		<div class="controls">
			<?php echo $user->getId(); ?>
		</div>			
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_NAME') ?>
		</div>
		<div class="controls">
			<?php echo PayplansHtml::link(XiRoute::_("index.php?option=com_payplans&view=user&task=edit&id=".$user->getId(), false), $user->getRealname()); ?>
			<?php echo ' ('.$user->getUsername().')'; ?>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_EMAIL') ?>  
		</div>
		<div class="controls">
			<?php echo $user->getEmail(); ?>					
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_USERTYPE') ?>  </div>
		<div class="controls">
			<?php echo $user->getUsertype(); ?>					
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label">
			<?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_REGISTERDATE') ?>
		</div>
		<div class="controls"><?php echo XiDate::timeago($user->getRegisterDate()); ?></div>					
	</div>
	
	<div class="control-group">
		<div class="control-label"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIPTION_EDIT_USER_LASTVISITDATE') ?>  </div>
		<div class="controls"><?php echo XiDate::timeago($user->getLastvisitDate()); ?></div>					
	</div>
</div>	
<?php endif; ?>
</fieldset>