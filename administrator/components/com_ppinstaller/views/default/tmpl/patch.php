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

	<div class="clearfix">
		<fieldset class="pp-parameter">
			<legend><?php echo JText::_('COM_PPINSTALLER_PATCH'); ?></legend>
				
				<?php
				// If nothing to patch then  I will not consider 'update version' as patch.
					if(isset($this->patches)):
						$append_msg = '';
						if($this->offset){
							//XITODO:: Clean this msg
							$append_msg = JText::sprintf('COM_PPINSTALLER_MODIFIED_RECORD',$this->offset);
						}					
						$flag = true;
						if(-1 == $this->key){
							$flag = false; 		// Nothing to patch Or All patches hase been applied and now updating payplans version
						}
						$next_patch_key = $this->key+1;
						foreach ($this->patches as $key => $patch):
							$class_name = 'pp-complete';
							if($flag && $this->key < $key ):
								$class_name = 'pp-pending';
							endif;
							
							if($flag && $key == $next_patch_key ):
								$class_name = 'pp-running';
							endif;
							$patch_string = 'COM_PPINSTALLER_'.$this->patches[$key];	
						?>	
							<div class="push_1 grid_10 pp-row ">
								<div class="pp-col pp-label migration-step <?php echo $class_name; ?>">
									<?php echo JText::_($patch_string); ?>
								</div>
								<div class="pp-col pp-value ">
									<span class="pp-msg"><?php echo ($key == $this->key)? JText::_($patch_string.'_MSG').$append_msg:''; ?></span>
								</div> 
							</div>	
						<?php endforeach;
						else: // I think  else condition never execute
							?>
							<div class="pp-message">
								<?php $this->needToContinue =true;
									  $this->nextTask ='complete';
									echo JText::_('COM_PPINSTALLER_NO_NEED_ANY_PATCH');
									?>
							</div>
							<?php 
						endif;
					?>					
			</fieldset>
		</div>
	
	<input type="hidden" name="option" value="com_ppinstaller" />
	<input type="hidden" name="task" value="<?php echo $this->nextTask?>"/>
	<input type="hidden" name="needToContinue" value="<?php echo $this->needToContinue; ?>" />
	<input type="hidden" name="offset" value="<?php echo $this->offset; ?>" />
