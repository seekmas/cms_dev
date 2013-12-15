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
if(defined('_JEXEC')===false) die();
?>
<div class="pp-logs-view offset1">
<form action="<?php echo $uri; ?>" method="post" name="adminForm">
<div class="row-fluid pp-gap-top10">
<?php if(is_array($data)==false):?>
	<div class="span8">
		<?php echo $data;?>
	</div>

<?php else:?>
	<div>
	<?php $diff = false;?>
	<?php $previous = $data['previous'];?>
	<?php $current  = $data['current'];?>
		
	<div class="row-fluid">
		<div class="span4">
			<strong><?php echo XiText::_('COM_PAYPLANS_LOG_KEY_LABEL');?></strong>
		</div>
		<div class="span4">
			<strong><?php echo XiText::_('COM_PAYPLANS_LOG_PREVIOUS_LABEL');?></strong>
		</div>
		<div class="span4">
			<strong><?php echo XiText::_('COM_PAYPLANS_LOG_CURRENT_LABEL');?></strong>
		</div>
	</div>
			
	<?php 
		$pre_exist = !empty($previous);
		$cur_exist = !empty($current);

		$base_record = $pre_exist ? $previous : $current;		
	?>
			
	
	<?php foreach($base_record as $key => $val):?>
	<div class="row-fluid">
		<?php $pre_value = $pre_exist ? (isset($previous[$key]))?$previous[$key]:'' : ''; ?>
		<?php $cur_value = $cur_exist ? (isset($current[$key]))?$current[$key]:''	: '';?>
		<?php $diff = ($cur_value != $pre_value); ?>
		
		<div class="<?php echo $diff ? " pp-highlight":""; ?>">
			<div class="span4 pp-word-wrap">
				<?php echo $key;?>
			</div>
			
			<div class="span4 pp-word-wrap">
				<?php if(is_array($pre_value) && !empty($pre_value)):?>
					<?php print implode("<br/>", $pre_value); ?>
					
				<?php elseif($key === 'status'):?>
					<?php echo (isset($pre_value) && "" !== JString::trim($pre_value)) ? XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($pre_value)):"&nbsp;";?>
					<?php elseif(empty($pre_value)):?>
						<?php echo "&nbsp;" ;?>
				<?php else:?>
					<?php echo $pre_value; ?>
				<?php endif;?>
			</div>
			
			<div class="span4 pp-word-wrap">
				<?php if(is_array($cur_value) && !empty($cur_value)):?>
					<?php print implode("<br/>", $cur_value); ?>
					
				<?php
				// if key is 0 then 0 == 'status' will return true because of type casting. So it is required to check the type also.
				elseif($key ==='status'):?>
					<?php echo (isset($cur_value) && "" !== JString::trim($cur_value)) ? XiText::_('COM_PAYPLANS_STATUS_'.PayplansStatus::getName($cur_value)):"&nbsp;";?>
				<?php elseif($cur_value !== 0 && empty($cur_value)):?>
						<?php echo "&nbsp;" ;?>
				<?php else:?>
					<?php echo $cur_value; ?>
				<?php endif;?>
			</div>
		</div>
		</div>
	<?php endforeach;?>
	
	</div>
<?php endif;?>

<input type="hidden" name="task" value="close" />
</div>
</form>
</div>
<?php

