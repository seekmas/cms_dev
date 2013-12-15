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
<div id="resource">
<div class="xippElements">
<form action="<?php echo $uri; ?>" method="post" id="adminForm" name="adminForm" id="adminForm">
	
	<div class="elementColumn">
		<fieldset class="pp-parameter">
			<legend> <?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_DETAILS' ); ?> </legend>

			<input type="hidden" name="id" value="<?php echo $resource->getId();?>" />
			
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_TITLE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TITLE') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input"><?php echo $resource->getTitle();?></div>
				<input type="hidden" name="resource" value="<?php echo $resource->getTitle();?>" />
			</div>
			
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_VALUE') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_VALUE') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input"><?php echo $resource->getValue(PayplansResource::PAYPLANS_RESOURCE_NAME_REQUIRED);?></div>
				<input type="hidden" name="value" value="<?php echo $resource->getValue();?>" />
			</div>
			
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_SUBSCRIPTION_IDS') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_SUBSCRIPTION_IDS') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input">
				<?php echo PayplansHtml::_('usersubscription.edit', 'subscription_ids', $resource->getUser(), $resource->getSubscriptions());?>
				</div>
			</div>
			
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_COUNT') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_COUNT') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input"><input type="text" name="count" value="<?php echo $resource->getCount();?>" /></div>
			</div>
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_USERID') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_USERID') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input"><?php echo $resource->getUser();?></div>
				<input type="hidden" name="userid" value="<?php echo $resource->getUser();?>" />
			</div>
			<div class="pp-row">
				<div class="pp-col pp-label">
					<span class="hasTip" title="<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_TOOLTIP_USERNAME') ?>" >
				 		<?php echo XiText::_('COM_PAYPLANS_RESOURCE_EDIT_USERNAME') ?> 
				 	</span>
				 </div>
				<div class="pp-col pp-input"><?php echo PayplansHelperUser::getUserName($resource->getUser());?></div>
				<input type="hidden" name="username" value="<?php echo PayplansHelperUser::getUserName($resource->getUser());?>" />
			</div>
		</fieldset>
		
		<!-- LOGS -->
			<?php echo $this->loadTemplate('edit_log'); ?>
	</div>
	<div class="elementColumn">
</div>
	<input type="hidden" name="task" value="save" />
</form>
</div>
</div>
<?php
