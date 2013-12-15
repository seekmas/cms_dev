<?php
/**
 * @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package		PayPlans
 * @subpackage	Frontend
 * @contact 		shyam@readybytes.in


 */
if(defined('_JEXEC')===false) die();?>
<script src="<?php echo PayplansHelperUtils::pathFS2URL(dirname(__FILE__).DS.'preferences.js');?>" type="text/javascript"></script>


<form method="post" id="preferences" action="#" class="form-vertical">

	<?php foreach ($form->getFieldset('preference') as $field):?>
               <?php $class = $field->group.$field->fieldname; ?>
               <div class="control-group <?php echo $class;?>">
                       <div class="preferences-param-label control-label"><?php echo $field->label; ?> </div>
                       <div class="preferences-param-value controls input-small"><?php echo $field->input; ?></div>                                                                
               </div>
    <?php endforeach;?>
			
    <div class="readable">
		<a id="preferences-edit-link" class="btn" href="" onClick="xi.form.editable('preferences'); return false;">
			<?php echo XiText::_("COM_PAYPLANS_DASHBOARD_USERDETAIL_EDIT");?>
		</a>
    </div>
    <div class="editable">
		<button type="submit" class="btn btn-primary" onClick="xi.preferences.save(this.form); return false;"><?php  echo XiText::_("COM_PAYPLANS_DASHBOARD_USERDETAIL_SAVE");?></button>

		<a id="preferences-cancel-link" class="btn" href="" onClick="xi.form.readable('preferences'); return false;">
			<?php echo XiText::_("COM_PAYPLANS_DASHBOARD_USERDETAIL_CANCEL");?>
		</a>
	</div>

</form>	

