<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class XiHtmlDaterange extends XiHtml
{
	static function edit($name, $id, $value, $format = '%Y-%m-%d', $attr = null)
	{
		$style = isset($attr['style']) ? $attr['style'] : '';
		
		ob_start();
		?>
			<input class="xidaterange" type="text" name="<?php echo $name;?>" id="<?php echo $id?>" value="<?php echo $value?>" />
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		self::includeScripts();
		return $content;
	}
	
	static function includeScripts()
	{
		static $loaded = false;
		
		if($loaded){
			return;
		}
		
		
		$url = XiHelperTemplate::mediaURI(dirname(__FILE__).DS.'daterange');
		JFactory::getDocument()->addScript($url.'moment.js');
		JFactory::getDocument()->addScript($url.'daterangepicker.js');
		JFactory::getDocument()->addStyleSheet($url.'daterangepicker.css');
		
		ob_start();
		?>

		(function($){
		//-----------
			$(document).ready(function() {
		  		$('.xidaterange').daterangepicker({
			  		ranges: {
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_TODAY')?>": [new Date(), new Date()],
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_YESTERDAY')?>": [moment().subtract('days', 1), moment().subtract('days', 1)],
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_LAST_7DAYS')?>": [moment().subtract('days', 6), new Date()],
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_LAST_30DAYS')?>": [moment().subtract('days', 29), new Date()],
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_THIS_MONTH')?>": [moment().startOf('month'), moment().endOf('month')],
                  		"<?php echo XiText::_('COM_PAYPLANS_HTML_DATERANGE_LAST_MONTH')?>": [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
               	},
               	showDropdowns: true,
               	format: 'YYYY-MM-DD',
               	separator: ':',
			  });
		  });
		
		//-----------
		}(payplans.jQuery));
		
		
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		JFactory::getDocument()->addScriptDeclaration($content);
		return;
	}
}