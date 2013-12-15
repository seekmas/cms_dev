<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Modules
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die(); ?>
<form method="post" id="migration-pre-sample" action="#">
<div  class="pp-migrate-pre row-fluid">	
	<div class="text-error text-center">
		<h4><?php echo XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_WARNING');?></h4>
	</div>
	<br/>
	<div class="text-center">
	<ul>
		<li><?php echo XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_INSTRUCTION1');?></li>
		<li><?php echo XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_INSTRUCTION2');?>
		
		<?php
			$default = 'Bs';
			$options = array();
			$options[] = PayplansHtml::_( 'select.option', 'Bs', XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_BASIC'));
			$options[] = PayplansHtml::_( 'select.option', 'Adv', XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_ADVANCED'));
			$options[] = PayplansHtml::_( 'select.option', 'Exp', XiText::_('PLG_PAYPLANS_SAMPLE_MIGRATION_EXPERT'));
			$sampleDataType = PayplansHtml::_('select.genericlist', $options, 'sampleData', null, 'value', 'text', $default );
			echo $sampleDataType;
		?>
		</li>
	</ul>
	</div>
	<div class="pp-gap-top25 text-center">
		<div class="span6"><?php echo XiText::_('PLG_PAYPLANS_SAMPLE_ESTIMATED_RECORDS');?><?php echo $record_count; ?></div>
		<div class="span6 pull-right"><?php echo XiText::_('PLG_PAYPLANS_SAMPLE_ESTIMATED_TIME');?><?php echo round($time_estimate/60,0); echo XiText::_('PLG_PAYPLANS_SAMPLE_ESTIMATED_TIME_MINUTES');?></div>
	</div> 
</div> 
</form>
