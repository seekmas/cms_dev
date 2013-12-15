<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* @package	PayPlans
* @subpackage	Router
* @contact 	shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();

class PayplansRouter extends XiRouter
{
    protected $_component  = 'com_payplans';   

    function _routes($key)
    {
        $routes =  array(
                'plan/'         => array(),
                'plan/subscribe' => array('plan_id'),
                'plan/login'    => array('plan_id'),

                'order/'        => array(),
                'order/display' => array('order_key'),

        		'subscription/'        => array(),
                'subscription/display' => array('subscription_key'),
        
                'invoice/'          => array(),
                'invoice/display'   => array('invoice_key'),
                'invoice/confirm'   => array('invoice_key'),
                'invoice/thanks'    => array('invoice_key'),

                'payment/pay'       => array('payment_key'),
                'payment/complete'  => array('payment_key'),


                'dashboard/' => array(),
                'dashboard/noaccess' => array(),
                'dashboard/frontview' => array(),

                'wallet/' => array(),
                'wallet/display' => array()
            );
    
        if(isset($routes[$key])==false){
            return array();
        }

        return $routes[$key];
    }
    
    public static function getInstance($name='Router', $prefix='Payplans')
    {
        return XiFactory::getInstance($name, '', $prefix);
    }
}