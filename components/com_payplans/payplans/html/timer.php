<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansHtmlTimer
{
	static function edit($name, $value, $prefix)
	{
		// $prefix = $control_name.$name;
		$class = XiHelperUtils::jsCompatibleId($prefix,'_');
		
		$yearHtml = '<span >'.XiText::_('COM_PAYPLANS_TIMER_YEARS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_year" id="'.$class.'_year" >';
		$monthHtml= '<span >'.XiText::_('COM_PAYPLANS_TIMER_MONTHS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_month" id="'.$class.'_month" >';
		$dayHtml  = '<span >'.XiText::_('COM_PAYPLANS_TIMER_DAYS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_day" id="'.$class.'_day" >';
		$hourHtml = '<span >'.XiText::_('COM_PAYPLANS_TIMER_HOURS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_hour" id="'.$class.'_hour" >';
		$minHtml  = '<span >'.XiText::_('COM_PAYPLANS_TIMER_MINUTES').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_minute" id="'.$class.'_minute" >';
		$secHtml  = '<span>'.XiText::_('COM_PAYPLANS_TIMER_SECONDS').'</span>'
					.'<select class="'.$class.'" name="'.$class.'_second" id="'.$class.'_second" >';		 
			
		for($count=0 ; $count<=60 ; $count++){
			$yearHtml  .= ($count<=10) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$monthHtml .= ($count<=11) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$dayHtml   .= ($count<=30) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$hourHtml  .= ($count<=23) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$minHtml   .= ($count<=59) ? '<option value="'.$count.'">'.$count.'</option>' : '';
			$secHtml   .= ($count<=59) ? '<option value="'.$count.'">'.$count.'</option>' : '';
		}

		$yearHtml  .= '</select> ';
		$monthHtml .= '</select> ';
		$dayHtml   .= '</select> ';
		$hourHtml  .= '</select> ';
		$minHtml   .= '</select> ';
		$secHtml   .= '</select> ';

		$text = '<span id="'.$class.'" >&nbsp;</span>';
		$hidden = '<input type="hidden" id="'.$class.'" name="'.$name.'" value="'.$value.'" />';
		
		self::_setupTimerScript();
		
		ob_start();
		?>
		payplans.jQuery(document).ready(function(){
			payplans.element.timer.setup('<?php echo $class;?>', '<?php echo $value;?>');			
		});
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		XiFactory::getDocument()->addScriptDeclaration($content);

		$micro = '<span class="hide">'.$hourHtml.$minHtml.$secHtml.'</span>';
		if(XiFactory::getConfig()->microsubscription){
			$micro = $hourHtml.$minHtml.'<span class="hide">'.$secHtml.'</span>';
		}
		
		return 	'<div id="timer-warp-'.$class.'"><div class="readable pp-mouse-pointer"><span class="pp-icon-edit">&nbsp;</span><span class="pp-content"></span></div> <div class="editable">'
					. $yearHtml.$monthHtml.$dayHtml.$micro
					.$hidden
				.'</div></div>';
	}

	static function _setupTimerScript()
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
	
			payplans.element.timer = {
				elems : Array('year', 'month', 'day', 'hour', 'minute', 'second'),
			
				// get value from selects and combine into string
				getValue : function(elem_class){
					var prefix	= elem_class+'_';
	        		var timer 	= '';
				
	 				for(var i =0; i < this.elems.length ; i++){
	 					var value= parseInt($('#' + prefix + this.elems[i]).val());
	 					if(10 > value){
							value = '0' + value;
						}
	
						timer += value;
					}
					
					return timer;
				},
				
				// set given string to different timer selects
				setValue : function(elem_class, value){
					var prefix	= elem_class+'_';
		
		 			for(var i =0; i < this.elems.length ; i++){
		 				$('#' + prefix+ this.elems[i]).val(parseInt(value.substr(i*2, 2), 10));
		 			}
				},
				
				
				format : function(elem_class){
					var prefix	= elem_class+'_';
					
					var data = {timer:{}};
	 				for(var i =0; i < this.elems.length ; i++){
	 					data.timer[this.elems[i]]=parseInt($('#' + prefix + this.elems[i]).val());
					}
					
					data['domObject'] = 'timer-warp-'+elem_class+' .readable span.pp-content';
					var url='index.php?option=com_payplans&view=support&task=format&object=timer&headerFooter=0';
					payplans.ajax.go(url, data);
				},
			
				onchange : function(elem_class){
					$('#' + elem_class).attr('value', this.getValue(elem_class));
					this.format(elem_class);
				},
				
				setup : function(elem_class, value){
	
		 			this.setValue(elem_class, value);
		 						   		
			   		// show readble
			   		this.format(elem_class);
			   		
					
					var hoverClass='pp-mouse-hover';    		        
			        $('#timer-warp-'+elem_class).parent().hover(
			    		function(){$(this).addClass(hoverClass);},
			    		function(){$(this).removeClass(hoverClass);}
			        );
			        
			        //setup ppeditable
			        $('#timer-warp-'+elem_class+' .editable').hide();
					$('#timer-warp-'+elem_class+' .readable').click(function(){
							$('#timer-warp-'+elem_class+' .editable').fadeToggle(200);
						});
					
					// setup onchange functionality
		    		$('select.'+elem_class).live('change' , function(){ 
		    				payplans.element.timer.onchange(elem_class);
		    			});  
				}
			}
		})(payplans.jQuery);

		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		XiFactory::getDocument()->addScriptDeclaration($content);
		$added = true;
		return true;
	}
}