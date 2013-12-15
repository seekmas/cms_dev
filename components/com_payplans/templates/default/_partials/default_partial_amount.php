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
// display amount as per preferences

$config = XiFactory::getconfig();
$fractionDigitCount = $config->fractionDigitCount;
$separator = $config->price_decimal_separator;
$amount = number_format(round($amount, $fractionDigitCount), $fractionDigitCount, $separator, '');
 
if($config->show_currency_at == "before"):
	echo '<span class="pp-currency">'.$currency.'</span>&nbsp;'
		 .'<span class="pp-amount">'.$amount.'</span>';
else :
	echo '<span class="pp-amount">'.$amount.'</span>&nbsp;'
		 .'<span class="pp-currency">'.$currency.'</span>';
endif;