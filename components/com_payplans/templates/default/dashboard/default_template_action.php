<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<?php 

$widget = new XiWidget();
$widget->id('pp-dashboard-menu');

$widget->setOption('title',XiText::_('COM_PAYPLANS_DASHBOARD_QUICKLINKS'));
$widget->setOption('style_class', 'hidden-phone');

ob_start();
?>
<ul class="nav nav-list ">
    <li>
    	<a href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan&task=subscribe'); ?>">
    		<?php echo XiText::_('COM_PAYPLANS_DASHBOARD_ACTION_SUBSCRIBE');?>
    	</a>
    </li>
    
    <?php if(XiFactory::getUser()->id) :?>
    <li>
    	<a href="<?php echo XiHelperJoomla::getLogoutLink(); ?>">
					<?php echo XiText::_('COM_PAYPLANS_DASHBOARD_ACTION_LOGOUT');?>
		</a>
    </li>
    <?php endif;?>
</ul>
<?php
$html = ob_get_contents();
ob_end_clean(); 

$widget->html($html);
echo $widget->draw();


/* widget Walllet */

$userId = XiFactory::getUser()->id ;
$user = PayplansUser::getInstance($userId);

if(XiFactory::getConfig()->walletWidget && $userId):
$amount = $user->getWalletBalance();
$currency = XiFactory::getCurrency(XiFactory::getConfig()->currency);
$currency = PayplansHelperFormat::currency($currency);

$widget = new XiWidget();
$widget->id('wallet-balance-'.$userId);
$widget->setOption('title', XiText::_('COM_PAYPLANS_DASHBOARD_WALLET_DETAILS'));

ob_start();
?>
		<div class="">
			<h2 class="pp-center pp-wallet-balance-amount muted">
				<?php echo $this->loadTemplate('partial_amount', compact('currency', 'amount'));?>
			</h2>	
			<div class="pp-center pp-wallet-balance-message">
				<?php echo XiText::_('COM_PAYPLANS_FRONT_END_DASHBOARD_WALLET_MESSAGE'); ?>
			</div>
			<?php if(XiFactory::getConfig()->walletRechargeAllowed):?>
				<div>
					<a  href="" onclick="xi.url.openInModal('<?php echo 'index.php?option=com_payplans&view=wallet&task=rechargeRequest';?>'); return false;">
			    		<?php echo XiText::_('COM_PAYPLANS_DASHBOARD_RECHARGE_WALLET_LINK');?>
					</a>
				</div>
			<?php endif;?>
		</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean(); 
	
	$widget->html($html);
	echo $widget->draw();

endif;
?>
