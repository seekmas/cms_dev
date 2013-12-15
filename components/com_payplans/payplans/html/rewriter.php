<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlRewriter
{
	static function edit($name, $value, $attr=null)
	{
		jimport('joomla.html.pane');
		ob_start();
		    $args = array();
			$appContent = PayplansHelperEvent::trigger('onPayplansRewriterDisplayTokens', $args);
			 $libs = array('plan', 'subscription', 'order', 'payment', 'invoice', 'transaction', 'user');
		?>
		<ul class="nav nav-tabs">
				<li class="active"><a href="#Config" data-toggle="tab"><?php echo XiText::_('Config'); ?> </a></li>
			
				<?php  foreach($libs as $lib):?>
					<li><a href="<?php echo "#".JString::ucfirst($lib);?>" data-toggle="tab"><?php echo JString::ucfirst($lib);?></a></li>
				<?php endforeach;?>
				<?php 
				  foreach ($appContent as $cont):
				  		echo isset($cont[0])? $cont[0]: '';
				  endforeach;
				?>
		</ul>
		<div class="tab-content">
				<div class="tab-pane active" id="Config">
					<?php $rewriter = new PayplansRewriter();
					        $rewriter->setConfigMapping();
					        $content = '';
							foreach($rewriter->get('mapping') as $key => $val){
								$content .= '[['.$key.']]<br/>';
							} 
							echo $content;?>
				</div>
				
				<?php 
				 foreach($libs as $lib):
						$libInstance = XiLib::getInstance($lib); 
						$rewriter = new PayplansRewriter();
						$rewriter->setMapping($libInstance, false);
					    ?>
					    
							<div class="tab-pane" id="<?php echo JString::ucfirst($lib);?>">
							<?php 
							$content = '';
							foreach($rewriter->get('mapping') as $key => $val):
								$content .= '[['.$key.']]<br/>';			
							?>
							<?php endforeach; ?>
							<?php echo $content; ?>
							</div>
				<?php endforeach;?>
				<?php 
					  foreach ($appContent as $cont):
					  		echo isset($cont[1])? $cont[1]: '';
					  endforeach;
				?>
         </div>
        <?php 
        $content = ob_get_contents();
		ob_end_clean();
		
		
		// load return link html
		ob_start();
		?>

		<a href="#" onclick="payplans.$('#pp-rewriter-content').toggle('slide', { direction: 'up'}, 500); return false;">
			<?php echo XiText::_('COM_PAYPLANS_REWRITER_TOKEN');?>
		</a>
		<div id="pp-rewriter-content" class="hide"><?php echo $content;?></div>
		<?php 		
		$html = ob_get_contents();
		ob_end_clean();
		 
		return $html;
	}

}
