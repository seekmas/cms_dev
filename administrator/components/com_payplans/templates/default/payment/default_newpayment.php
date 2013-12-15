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
//XiHtml::stylesheet('admin.css', PAYPLANS_URL_MEDIA."/css/", false);
?>

<div id="newPayment">
<a href="<?php echo JURI::base().'index.php?option=com_payplans&view=order' ;?>"><?php echo XiText::_('COM_PAYPLANS_PAYMENT_NEW_TEXT_CLICK'); ?></a>
<?php echo XiText::_('COM_PAYPLANS_PAYMENT_NEW_TEXT_MESSAGE'); ?>
</div>


<?php 
