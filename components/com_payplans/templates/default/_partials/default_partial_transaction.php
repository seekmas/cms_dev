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

<?php if(empty($transactions)): ?>
<div> <?php echo $message; ?> </div>
<?php else :?>

	<table class="table table-hover">
    	<thead>
			<tr>
	            <th>#</th>
    	        <th><?php echo XiText::_('COM_PAYPLANS_FRONT_TRANSACTION_AMOUNT'); ?></th>
        	    <th><?php echo XiText::_('COM_PAYPLANS_FRONT_TRANSACTION_CREATED_DATE');?></th>
            	<th class='hidden-phone'><?php echo XiText::_('COM_PAYPLANS_FRONT_TRANSACTION_PAYMENT_METHOD');?></th>
			</tr>
		</thead>
		<tbody>
	<?php 
		$count = 0;
		foreach($transactions as $record):
			if(is_a($record, 'PayplansTransaction')){
				$transaction = $record;
			}else{
				$transaction = PayplansTransaction::getInstance($record->transaction_id, null, $record);
			}
			$count++;
		?>
		<tr class="hasTip" title="<?php echo XiText::_($transaction->getMessage());?>" payplans-tipsy-gravity="s">
			<td><?php echo $count;?>.</td>
			<td>
			
				<?php 	
					$amount   = $transaction->getAmount();
					$currency = PayplansHelperFormat::currency(XiFactory::getCurrency(XiFactory::getConfig()->currency));
					echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));
				?>
			</td>

			<td> <?php echo PayplansHelperFormat::date($transaction->getCreatedDate());?></td>
			
			<td class='hidden-phone'>
						<?php  $payment_id = $transaction->getPayment();
								if(!empty($payment_id)){
									$payment = $transaction->getPayment(PAYPLANS_INSTANCE_REQUIRE);
									echo ($payment instanceof PayplansPayment)
						  	    			? $payment->getAppName()
						  	    			: XiText::_('COM_PAYPLANS_TRANSACTION_PAYMENT_GATEWAY_NONE');
								}else{
									echo XiText::_('COM_PAYPLANS_TRANSACTION_PAYMENT_GATEWAY_NONE');
								}
			  	    ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
	<?php endif; ?>
<?php 