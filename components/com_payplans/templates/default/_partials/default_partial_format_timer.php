<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();?>

<?php

		// in case when plan expiration time is not set
		$lifetime = true;
		$count = 0;
		
		foreach($timer as $key => $value){
			$value = (int)$value;
			if($value > 0){
				$lifetime = false;
			}
					
			$count 	+= $value ? 1 : 0;
		}

		if($lifetime){
			echo XiText::_('COM_PAYPLANS_PLAN_LIFE_TIME');
			return;
		}
			
		$counter = 0;
		$str = '';
		foreach($timer as $key => $value)
		{
			$value = (int)$value;
			$key = JString::strtoupper($key);
			
			// show values if they are greater than zero only
			if(!$value){
				continue;
			}
				
			$key .= ($value>1) ? 'S':'';
			$valueStr = $value." ";
			
			$concatStr = $counter ? ' '.XiText::_('COM_PAYPLANS_PLANTIME_CONCATE_STRING_AND').' ' : '';
			$str .= $concatStr.$valueStr.XiText::_("COM_PAYPLANS_PLAN_{$key}"); 
			$counter++;
		}

		echo $str;
		return;
