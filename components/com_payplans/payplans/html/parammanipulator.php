<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlParammanipulator
{
	static function edit($options, $value, $params, $name, $class, $id , $control_name ='')
	{
		self::_getFilterScript( $id,$name, $params, $value, $control_name);
		$html = JHTML::_('select.genericlist',  $options, $name , $class, 'value', 'text', $value, $id);
		return $html;
	}
	
	private static function _getFilterScript($id, $name, $params, $value, $control_name)
	{
		self::_setupManipulatorScript();
		
		ob_start();
		?>	
		payplans.jQuery(document).ready(function(){
			//	alert("<?php echo XiHelperUtils::jsCompatibleId($control_name.$name);?>");
			
			payplans.element.parammanipulator.insert(
						// in case of direct html input(not through XML)'id' is needed to used 
						// on the place of control_name because control_name is blank
						'<?php  echo XiHelperUtils::jsCompatibleId($control_name?$control_name:$id);?>',
						payplans.jQuery('#'+ '<?php  echo XiHelperUtils::jsCompatibleId($id);?>'),
						<?php echo json_encode($params);?>
					);
		});
		<?php 
		$content = ob_get_contents();
		ob_end_clean();
		
		XiFactory::getDocument()->addScriptDeclaration($content);
		
		return true;
	} 
	
	static function _setupManipulatorScript()
	{
		static $added = false;
		if($added){
			return true;
		}
		
		ob_start();
		?>

		(function($){
			// if elements not defined already
			if(typeof(payplans.element)=='undefined'){
				payplans.element = {};
			}
	
			payplans.element.parammanipulator = {
				_queue 	: new Array(),
				_elem_count : 0, 
				_show_queue 	: new Array(),
				
				insert : function(prefix, elem, params){
					var data = new Object;
					
					data.prefix = prefix;
					data.elem 	= elem[0];
					data.params = eval(params);
					
					queue = payplans.element.parammanipulator._queue;
					queue.push(data);
					
					if(queue.length == this._elem_count){
						this.init();
					}
				},
				
				init : function(){
					
					queue = payplans.element.parammanipulator._queue;
					show_queue = payplans.element.parammanipulator._show_queue ;
					
					// initiliaze all manipulators on screen
					for(var index=0 ; index < queue.length; index++){
						var data = queue[index];
						payplans.element.parammanipulator.manipulate(data.prefix, data.elem, data.params);
					}
					
					payplans.element.parammanipulator._show_queue = new Array();
					
					$('.pp-parammanipulator').change(payplans.element.parammanipulator.init);
				},
				
				manipulate : function(prefix, elem, params){
					
					show_queue = payplans.element.parammanipulator._show_queue ;
					 
					var $elem = $('#'+elem.id);
					// hide all childs
					$.each(params, function(key, val) {
							$.each(val, function(){
								// only hide if none make it visible
								if(this.toString() != '' && show_queue.indexOf('.' + prefix + this) == -1){
									$('.' + prefix + this).hide();
								}
							});
						});
					
					// show child if, I am visible
					//parammanipulator won't work in case of tabs,if check for element visibility
<!--					if($elem.is(':visible') == true){-->
						$.each(params[$elem.val()], function(){
							//alert("value is "+this);
							if(this.toString() != ''){
								$('.' + prefix + this).show();
								show_queue.push('.' + prefix + this);
							}
						});
<!--					}-->
				}
			}
			
			// 
			$(document).ready(function(){
				payplans.element.parammanipulator._elem_count = $('.pp-parammanipulator').length;
			});
			
		})(payplans.jQuery);

		
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		XiFactory::getDocument()->addScriptDeclaration($content);
		$added = true;
		return true;
	}
}
