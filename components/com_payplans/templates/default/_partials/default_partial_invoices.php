<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<?php if(empty($invoices)):?>
	<div> <?php echo $message; ?> </div>
<?php else :?>
	<table class="table table-hover">
    	<thead>
			<tr>
	            <th>#</th>
    	        <th class='hidden-phone'><?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_KEY'); ?></th>
        	    <th><?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_TOTAL');?></th>
            	<th class='hidden-phone'><?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_STATUS');?></th>
            	<th>&nbsp;
            		<span class='visible-phone'><?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_STATUS');?></span>
            	</th>
			</tr>
		</thead>
		<tbody>

		<?php 
		$count = 0;
		foreach($invoices as $record):
			if(is_a($record, 'PayplansInvoice')){
				$invoice = $record;
			}
			else{
				$invoice = PayplansInvoice::getInstance($record->invoice_id, null, $record);
			}
			
			$count++;
			?>
			<tr>
					<td><?php echo $count;?>.</td>
					<td class='hidden-phone'>
						<?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=invoice&task=display&invoice_key='.$invoice->getKey()), $invoice->getKey());?>
					</td>
					<td >
						<?php $currency = $invoice->getCurrency();
							  $amount   = $invoice->getTotal(); 
							  $text     =  $this->loadTemplate('partial_amount', compact('currency', 'amount')); ?>
											   
						<?php echo PayplansHtml::link(XiRoute::_('index.php?option=com_payplans&view=invoice&task=display&invoice_key='.$invoice->getKey()), $text);?>
					</td>
					
					<td class='hidden-phone'>
						<?php echo $invoice->getStatusName();?>
					</td>
					
					<td>
						<span class='visible-phone'><?php echo $invoice->getStatusName();?> <br /></span>
						<?php if($invoice->getStatus() == PayplansStatus::NONE || $invoice->getStatus() == PayplansStatus::INVOICE_CONFIRMED): ?>
							<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=invoice&task=confirm&invoice_key='.$invoice->getKey()); ?>">
								<span>
									<i class="pp-icon-share-alt"></i>&nbsp;<?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_PAY_NOW');?>
								</span>
							</a>
						<?php elseif(XiHelperPlugin::getStatus('pdfinvoice','payplans')):?>
								<span class="pp-icon-download-alt pp-mouse-pointer hasTip" payplans-tipsy-gravity="s"
									  onclick="payplans.url.redirect('<?php echo XiRoute::_('index.php?option=com_payplans&action=sitePdfInvoice&invoice_key='.$invoice->getKey());?>')" 
									  title="<?php echo XiText::_('COM_PAYPLANS_FRONT_INVOICE_DOWNLOAD_LINK');?>">
								</span>
						<?php endif;?>
						&nbsp;
					</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php 
