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
<?php 
	// input are parameter, name and group
	// defaults : $name = 'params', $group = '_default
	if(isset($name)=== false) $name = 'params';
	if(isset($group)=== false) $group = '_default';
	
	$params = $parameter->getParams($name, $group);
?>
<div class="pp-parameter pp-grid_12">

<?php if(count($params) < 1) : ?>

	<div class="pp-row pp-description clearfix">
		<?php XiText::_('COM_PAYPLANS_THERE_ARE_NO_PARAMETER_FOR_THIS_ITEM'); ?>
	</div>
	
<?php else: ?>
	<?php foreach ($params as $param) : ?>
		<?php $param['name'] =  $name; ?>
		<?php echo XiHelperTemplate::partial('default_partial_parameter',compact('param'));?>
	<?php endforeach; ?>
<?php endif; ?>


</div>
<?php 
