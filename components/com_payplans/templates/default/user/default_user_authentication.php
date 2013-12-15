<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();
?>
<div id="payplans" class="payplans-wrap">
  <div class="payplans">
       <div class="msg-box">
           <div class="pp-recharge-msg pp-center">
                 <?php echo $message;?>    
            </div>
        </div>
   
   <div class="pp-grid_12">
       
      <div class="pp-grid_4">
           <div class="pp-row pp-gap-top20 border">
               <div class="pp-center pp-bold"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIBE_PLAN');?></div>
		            
	            <div class="pp-row pp-center pp-gap-top10">
	                  <span><a class="pp-button ui-button-primary ui-button ui-widget  ui-corner-all ui-button-text-only pp-bold" href="<?php echo XiRoute::_('index.php?option=com_payplans&view=plan'); ?>"><?php echo XiText::_('COM_PAYPLANS_SUBSCRIBE_PLAN_HERE')?></a></span>
	            </div>
		             
		         <div class="pp-row pp-center pp-gap-top10"> 
		            <span><a href="<?php echo XiRoute::_('index.php?option=com_users&view=registration')?>"><?php echo XiText::_('COM_PAYPLANS_CREATE_ACCOUNT');?> <i class="icon-arrow-right"></i></a></span>	
			     </div>   
	         </div>	
        </div>
       
       <div class="pp-grid_8">
            <div class="pp-prefix_4 pp-grid_8">
		        <div class="pp-row">
			         <div class="pp-error"><span class="err-payplansLoginError pp-bold"></span>&nbsp;</div>
	             </div>
	         
	             <div class="pp-row">
			          <div class="pp-col pp-label required"><?php echo XiText::_('COM_PAYPLANS_LOGIN_USERNAME');?></div>
			          <div class="pp-col pp-input pp-gap-top05"><input type="text" class="payplansLoginUsername" size="20"></div>
	             </div>					

		         <div class="pp-row">&nbsp;</div> 
		
	            <div class="pp-row">
			         <div class="pp-col pp-label required"><?php echo XiText::_('COM_PAYPLANS_LOGIN_PASSWORD');?></div>
			         <div class="pp-col pp-input pp-gap-top05"><input type="password" class="payplansLoginPassword" size="20"></div>
	            </div>
	        </div>    
   
	  </div> 
   </div> 

  </div>
</div>   
<?php 
