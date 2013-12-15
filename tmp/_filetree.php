<?php 	    	if(defined('_JEXEC')===false) die();
	    	
	    	class XiFileTreeProvider
	    	{
	    		static $_filetree =  array (
  '/components/com_payplans/' => 
  array (
    'files' => 
    array (
      0 => 'payplans.php',
      1 => 'router.php',
    ),
    'folders' => 
    array (
      0 => 'apps',
      1 => 'controllers',
      2 => 'elements',
      3 => 'helpers',
      4 => 'includes',
      5 => 'libraries',
      6 => 'media',
      7 => 'payplans',
      8 => 'sef_ext',
      9 => 'templates',
      10 => 'views',
      11 => 'xiframework',
    ),
  ),
  '/components/com_payplans/apps/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'access',
      1 => 'payment',
    ),
  ),
  '/components/com_payplans/apps/access/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'contentacl',
    ),
  ),
  '/components/com_payplans/apps/access/contentacl/' => 
  array (
    'files' => 
    array (
      0 => 'contentacl.php',
    ),
    'folders' => 
    array (
      0 => 'elements',
    ),
  ),
  '/components/com_payplans/apps/access/contentacl/elements/' => 
  array (
    'files' => 
    array (
      0 => 'xiarticle.php',
      1 => 'xijcategory.php',
      2 => 'xijsection.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/apps/payment/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'adminpay',
      1 => 'offlinepay',
      2 => 'paypal',
    ),
  ),
  '/components/com_payplans/apps/payment/adminpay/' => 
  array (
    'files' => 
    array (
      0 => 'adminpay.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/apps/payment/adminpay/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'adminform.php',
      1 => 'transaction.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/apps/payment/offlinepay/' => 
  array (
    'files' => 
    array (
      0 => 'offlinepay.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/apps/payment/offlinepay/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'cancel.php',
      1 => 'form.php',
      2 => 'transaction.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/apps/payment/paypal/' => 
  array (
    'files' => 
    array (
      0 => 'paypal.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/apps/payment/paypal/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'error.php',
      1 => 'form_buynow.php',
      2 => 'form_subscription.php',
      3 => 'transaction.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/controllers/' => 
  array (
    'files' => 
    array (
      0 => 'cron.php',
      1 => 'dashboard.php',
      2 => 'invoice.php',
      3 => 'order.php',
      4 => 'payment.php',
      5 => 'plan.php',
      6 => 'subscription.php',
      7 => 'support.php',
      8 => 'user.php',
      9 => 'wallet.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/elements/' => 
  array (
    'files' => 
    array (
      0 => 'apps.php',
      1 => 'currency.php',
      2 => 'imageselector.php',
      3 => 'jsmultiprofile.php',
      4 => 'jusertype.php',
      5 => 'parammanipulator.php',
      6 => 'plans.php',
      7 => 'popup.php',
      8 => 'price.php',
      9 => 'registrationtype.php',
      10 => 'rewriter.php',
      11 => 'seperator.php',
      12 => 'timer.php',
      13 => 'xiapptype.php',
      14 => 'xicalendar.php',
      15 => 'xicountry.php',
      16 => 'xieditor.php',
      17 => 'xifbselect.php',
      18 => 'xifilelist.php',
      19 => 'xigroup.php',
      20 => 'xijarticle.php',
      21 => 'xijssrc.php',
      22 => 'xipassword.php',
      23 => 'xiprofiletype.php',
      24 => 'xispacer.php',
      25 => 'xistatus.php',
      26 => 'xitextarea.php',
      27 => 'xithemes.php',
      28 => 'xiuploadfile.php',
      29 => 'xiwidgetposition.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/helpers/' => 
  array (
    'files' => 
    array (
      0 => 'app.php',
      1 => 'cron.php',
      2 => 'event.php',
      3 => 'format.php',
      4 => 'invoice.php',
      5 => 'loader.php',
      6 => 'logger.php',
      7 => 'modifier.php',
      8 => 'order.php',
      9 => 'param.php',
      10 => 'patch.php',
      11 => 'plan.php',
      12 => 'rewriter.php',
      13 => 'search.php',
      14 => 'statistics.php',
      15 => 'template.php',
      16 => 'theme.php',
      17 => 'transaction.php',
      18 => 'user.php',
      19 => 'utils.php',
      20 => 'wallet.php',
    ),
    'folders' => 
    array (
      0 => 'patch',
    ),
  ),
  '/components/com_payplans/helpers/patch/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'sql',
    ),
  ),
  '/components/com_payplans/helpers/patch/sql/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/includes/' => 
  array (
    'files' => 
    array (
      0 => 'api.php',
      1 => 'defines.php',
      2 => 'includes.php',
      3 => 'ini.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
      1 => 'event',
      2 => 'formatter',
      3 => 'iface',
      4 => 'lib',
      5 => 'model',
      6 => 'setup',
      7 => 'table',
    ),
  ),
  '/components/com_payplans/libraries/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'assignplan',
      1 => 'content',
      2 => 'corewidget',
      3 => 'docman',
      4 => 'email',
      5 => 'jsmultiprofile',
      6 => 'jusertype',
      7 => 'userpreferences',
      8 => 'xiprofiletype',
    ),
  ),
  '/components/com_payplans/libraries/app/assignplan/' => 
  array (
    'files' => 
    array (
      0 => 'assignplan.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/content/' => 
  array (
    'files' => 
    array (
      0 => 'content.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/corewidget/' => 
  array (
    'files' => 
    array (
      0 => 'corewidget.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/docman/' => 
  array (
    'files' => 
    array (
      0 => 'docman.php',
    ),
    'folders' => 
    array (
      0 => 'elements',
      1 => 'tmpl',
    ),
  ),
  '/components/com_payplans/libraries/app/docman/elements/' => 
  array (
    'files' => 
    array (
      0 => 'docmangroups.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/docman/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/email/' => 
  array (
    'files' => 
    array (
      0 => 'email.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/libraries/app/email/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'Cart_Abandonment.php',
      1 => 'Subscription_Active.php',
      2 => 'Subscription_Expire.php',
      3 => 'Subscription_Post_Activation.php',
      4 => 'Subscription_Post_Expiry.php',
      5 => 'Subscription_Pre_Expiration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/jsmultiprofile/' => 
  array (
    'files' => 
    array (
      0 => 'jsmultiprofile.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/jusertype/' => 
  array (
    'files' => 
    array (
      0 => 'jusertype.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/libraries/app/jusertype/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/userpreferences/' => 
  array (
    'files' => 
    array (
      0 => 'userpreferences.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/libraries/app/userpreferences/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/app/xiprofiletype/' => 
  array (
    'files' => 
    array (
      0 => 'xiprofiletype.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/event/' => 
  array (
    'files' => 
    array (
      0 => 'access.php',
      1 => 'app.php',
      2 => 'config.php',
      3 => 'core.php',
      4 => 'dashboard_info.php',
      5 => 'discount.php',
      6 => 'log.php',
      7 => 'order.php',
      8 => 'plan.php',
      9 => 'wallet.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/formatter/' => 
  array (
    'files' => 
    array (
      0 => 'email.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/iface/' => 
  array (
    'files' => 
    array (
      0 => 'apptriggerable.php',
      1 => 'discountable.php',
      2 => 'maskable.php',
      3 => 'orderable.php',
    ),
    'folders' => 
    array (
      0 => 'api',
      1 => 'app',
    ),
  ),
  '/components/com_payplans/libraries/iface/api/' => 
  array (
    'files' => 
    array (
      0 => 'config.php',
      1 => 'group.php',
      2 => 'invoice.php',
      3 => 'modifier.php',
      4 => 'order.php',
      5 => 'payment.php',
      6 => 'plan.php',
      7 => 'subscription.php',
      8 => 'transaction.php',
      9 => 'user.php',
      10 => 'wallet.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/iface/app/' => 
  array (
    'files' => 
    array (
      0 => 'access.php',
      1 => 'discount.php',
      2 => 'payment.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/lib/' => 
  array (
    'files' => 
    array (
      0 => 'app.php',
      1 => 'config.php',
      2 => 'group.php',
      3 => 'invoice.php',
      4 => 'modifier.php',
      5 => 'order.php',
      6 => 'payment.php',
      7 => 'plan.php',
      8 => 'resource.php',
      9 => 'subscription.php',
      10 => 'transaction.php',
      11 => 'user.php',
      12 => 'wallet.php',
    ),
    'folders' => 
    array (
      0 => 'app',
    ),
  ),
  '/components/com_payplans/libraries/lib/app/' => 
  array (
    'files' => 
    array (
      0 => 'access.php',
      1 => 'discounts.php',
      2 => 'payment.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/model/' => 
  array (
    'files' => 
    array (
      0 => 'app.php',
      1 => 'config.php',
      2 => 'country.php',
      3 => 'currency.php',
      4 => 'group.php',
      5 => 'invoice.php',
      6 => 'log.php',
      7 => 'modifier.php',
      8 => 'order.php',
      9 => 'payment.php',
      10 => 'plan.php',
      11 => 'planapp.php',
      12 => 'plangroup.php',
      13 => 'resource.php',
      14 => 'statistics.php',
      15 => 'subscription.php',
      16 => 'support.php',
      17 => 'transaction.php',
      18 => 'user.php',
      19 => 'wallet.php',
    ),
    'folders' => 
    array (
      0 => 'xml',
    ),
  ),
  '/components/com_payplans/libraries/model/xml/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/setup/' => 
  array (
    'files' => 
    array (
      0 => 'adminpay.php',
      1 => 'configuration.php',
      2 => 'cron.php',
      3 => 'curl.php',
      4 => 'menus.php',
      5 => 'oneclickcheckout.php',
      6 => 'payment.php',
      7 => 'phpversion.php',
      8 => 'plans.php',
      9 => 'plugins.php',
      10 => 'registration.php',
      11 => 'tax.php',
      12 => 'upgradeapp.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/libraries/table/' => 
  array (
    'files' => 
    array (
      0 => 'app.php',
      1 => 'config.php',
      2 => 'country.php',
      3 => 'currency.php',
      4 => 'group.php',
      5 => 'invoice.php',
      6 => 'log.php',
      7 => 'modifier.php',
      8 => 'order.php',
      9 => 'payment.php',
      10 => 'plan.php',
      11 => 'planapp.php',
      12 => 'plangroup.php',
      13 => 'resource.php',
      14 => 'statistics.php',
      15 => 'subscription.php',
      16 => 'support.php',
      17 => 'transaction.php',
      18 => 'user.php',
      19 => 'wallet.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'images',
      2 => 'js',
      3 => 'premium_themes',
      4 => 'themes',
    ),
  ),
  '/components/com_payplans/media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'blue',
      1 => 'blue_dark',
      2 => 'green',
      3 => 'green_dark',
      4 => 'red',
      5 => 'red_dark',
    ),
  ),
  '/components/com_payplans/media/premium_themes/blue/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/blue_dark/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/green/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/green_dark/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/red/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/premium_themes/red_dark/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/themes/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'dark_ef723b',
      1 => 'default_ef723b',
    ),
  ),
  '/components/com_payplans/media/themes/dark_ef723b/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/media/themes/default_ef723b/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/payplans/' => 
  array (
    'files' => 
    array (
      0 => 'factory.php',
      1 => 'formatter.php',
      2 => 'html.php',
      3 => 'rewriter.php',
      4 => 'router.php',
      5 => 'statistics.php',
      6 => 'status.php',
    ),
    'folders' => 
    array (
      0 => 'html',
      1 => 'statistics',
    ),
  ),
  '/components/com_payplans/payplans/html/' => 
  array (
    'files' => 
    array (
      0 => 'apps.php',
      1 => 'apptags.php',
      2 => 'apptypes.php',
      3 => 'country.php',
      4 => 'currency.php',
      5 => 'email.php',
      6 => 'groups.php',
      7 => 'logclass.php',
      8 => 'loglevel.php',
      9 => 'orders.php',
      10 => 'parammanipulator.php',
      11 => 'plans.php',
      12 => 'price.php',
      13 => 'rewriter.php',
      14 => 'status.php',
      15 => 'taxrate.php',
      16 => 'timer.php',
      17 => 'users.php',
      18 => 'usersubscription.php',
      19 => 'widgetposition.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/payplans/statistics/' => 
  array (
    'files' => 
    array (
      0 => 'cart.php',
      1 => 'discount.php',
      2 => 'donation.php',
      3 => 'payment.php',
      4 => 'plan.php',
      5 => 'subscription.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/sef_ext/' => 
  array (
    'files' => 
    array (
      0 => 'com_payplans.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'default',
    ),
  ),
  '/components/com_payplans/templates/default/' => 
  array (
    'files' => 
    array (
      0 => 'default_assets.php',
    ),
    'folders' => 
    array (
      0 => '_media',
      1 => '_partials',
      2 => 'dashboard',
      3 => 'invoice',
      4 => 'order',
      5 => 'payment',
      6 => 'plan',
      7 => 'subscription',
      8 => 'support',
      9 => 'user',
      10 => 'wallet',
    ),
  ),
  '/components/com_payplans/templates/default/_media/' => 
  array (
    'files' => 
    array (
      0 => 'helper.php',
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'js',
    ),
  ),
  '/components/com_payplans/templates/default/_media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'images',
    ),
  ),
  '/components/com_payplans/templates/default/_media/css/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/_media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/_partials/' => 
  array (
    'files' => 
    array (
      0 => 'default_partial_amount.php',
      1 => 'default_partial_date.php',
      2 => 'default_partial_email_errorlog.php',
      3 => 'default_partial_extra_details.php',
      4 => 'default_partial_format_timer.php',
      5 => 'default_partial_invoice.php',
      6 => 'default_partial_invoices.php',
      7 => 'default_partial_parameter.php',
      8 => 'default_partial_parameters.php',
      9 => 'default_partial_position.php',
      10 => 'default_partial_subscription.php',
      11 => 'default_partial_transaction.php',
      12 => 'default_partial_user.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/dashboard/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_frontview.php',
      2 => 'default_noaccess.php',
      3 => 'default_template_action.php',
      4 => 'default_template_footer.php',
      5 => 'default_template_message.php',
      6 => 'default_template_right.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/invoice/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_complete.php',
      2 => 'default_confirm.php',
      3 => 'default_confirm_details.php',
      4 => 'default_discount.php',
      5 => 'default_invoice_action.php',
      6 => 'default_plan_details.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/order/' => 
  array (
    'files' => 
    array (
      0 => 'default_terminate.php',
      1 => 'default_terminate_confirm.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/payment/' => 
  array (
    'files' => 
    array (
      0 => 'default_complete_cancel.php',
      1 => 'default_complete_error.php',
      2 => 'default_pay.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/plan/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_group.php',
      2 => 'default_login.php',
      3 => 'default_login_plan.php',
      4 => 'default_plan.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/subscription/' => 
  array (
    'files' => 
    array (
      0 => 'default_display.php',
      1 => 'default_order_cancel.php',
      2 => 'default_subscription_action.php',
      3 => 'default_view.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/support/' => 
  array (
    'files' => 
    array (
      0 => 'default_emailform.php',
      1 => 'default_error.php',
      2 => 'default_sent.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/user/' => 
  array (
    'files' => 
    array (
      0 => 'default_login.php',
      1 => 'default_user_authentication.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/templates/default/wallet/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_recharge_request_details.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'dashboard',
      1 => 'invoice',
      2 => 'order',
      3 => 'payment',
      4 => 'plan',
      5 => 'subscription',
      6 => 'support',
      7 => 'user',
      8 => 'wallet',
    ),
  ),
  '/components/com_payplans/views/dashboard/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/views/dashboard/tmpl/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/invoice/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/order/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/views/order/tmpl/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/payment/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/plan/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/views/plan/tmpl/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/subscription/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/components/com_payplans/views/subscription/tmpl/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/support/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/user/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/views/wallet/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/' => 
  array (
    'files' => 
    array (
      0 => '_filetree.php',
      1 => 'defines.php',
      2 => 'filetree.php',
      3 => 'includes.php',
      4 => 'loader.php',
    ),
    'folders' => 
    array (
      0 => 'base',
      1 => 'elements',
      2 => 'includes',
      3 => 'lib',
      4 => 'media',
    ),
  ),
  '/components/com_payplans/xiframework/base/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'date.php',
      2 => 'element.php',
      3 => 'encryptor.php',
      4 => 'error.php',
      5 => 'factory.php',
      6 => 'field.php',
      7 => 'form.php',
      8 => 'html.php',
      9 => 'language.php',
      10 => 'lib.php',
      11 => 'lock.php',
      12 => 'logger.php',
      13 => 'model.php',
      14 => 'modelform.php',
      15 => 'pagination.php',
      16 => 'parameter.php',
      17 => 'plugin.php',
      18 => 'query.php',
      19 => 'render.php',
      20 => 'route.php',
      21 => 'router.php',
      22 => 'session.php',
      23 => 'setup.php',
      24 => 'table.php',
      25 => 'text.php',
      26 => 'view.php',
      27 => 'widget.php',
    ),
    'folders' => 
    array (
      0 => 'abstract',
      1 => 'ajax',
      2 => 'helper',
      3 => 'html',
      4 => 'plugin',
      5 => 'render',
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'date.php',
      2 => 'factory.php',
      3 => 'model.php',
      4 => 'route.php',
      5 => 'view.php',
    ),
    'folders' => 
    array (
      0 => 'helper',
      1 => 'j16',
      2 => 'j35',
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/helper/' => 
  array (
    'files' => 
    array (
      0 => 'joomla.php',
      1 => 'patch.php',
      2 => 'toolbar.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/j16/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'factory.php',
      2 => 'model.php',
      3 => 'route.php',
    ),
    'folders' => 
    array (
      0 => 'helper',
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/j16/helper/' => 
  array (
    'files' => 
    array (
      0 => 'joomla.php',
      1 => 'patch.php',
      2 => 'toolbar.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/j35/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'factory.php',
      2 => 'model.php',
      3 => 'route.php',
    ),
    'folders' => 
    array (
      0 => 'helper',
    ),
  ),
  '/components/com_payplans/xiframework/base/abstract/j35/helper/' => 
  array (
    'files' => 
    array (
      0 => 'joomla.php',
      1 => 'patch.php',
      2 => 'toolbar.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/ajax/' => 
  array (
    'files' => 
    array (
      0 => 'response.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/helper/' => 
  array (
    'files' => 
    array (
      0 => 'context.php',
      1 => 'joomla.php',
      2 => 'patch.php',
      3 => 'plugin.php',
      4 => 'setup.php',
      5 => 'table.php',
      6 => 'template.php',
      7 => 'toolbar.php',
      8 => 'utils.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/html/' => 
  array (
    'files' => 
    array (
      0 => 'autocomplete.php',
      1 => 'boolean.php',
      2 => 'combo.php',
      3 => 'daterange.php',
      4 => 'datetime.php',
      5 => 'jusertype.php',
      6 => 'range.php',
      7 => 'text.php',
    ),
    'folders' => 
    array (
      0 => 'autocomplete',
      1 => 'daterange',
    ),
  ),
  '/components/com_payplans/xiframework/base/html/autocomplete/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/html/daterange/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/plugin/' => 
  array (
    'files' => 
    array (
      0 => 'migration.php',
      1 => 'registration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/base/render/' => 
  array (
    'files' => 
    array (
      0 => 'ajax.php',
      1 => 'html.php',
      2 => 'json.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/elements/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/includes/' => 
  array (
    'files' => 
    array (
      0 => 'ini.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/lib/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/media/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'js',
    ),
  ),
  '/components/com_payplans/xiframework/media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'images',
    ),
  ),
  '/components/com_payplans/xiframework/media/css/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/components/com_payplans/xiframework/media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'nvd3',
    ),
  ),
  '/components/com_payplans/xiframework/media/js/nvd3/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/' => 
  array (
    'files' => 
    array (
      0 => 'payplans.php',
      1 => 'script.payplans.php',
    ),
    'folders' => 
    array (
      0 => 'controllers',
      1 => 'includes',
      2 => 'installer',
      3 => 'templates',
      4 => 'views',
    ),
  ),
  '/administrator/components/com_payplans/controllers/' => 
  array (
    'files' => 
    array (
      0 => 'app.php',
      1 => 'config.php',
      2 => 'dashboard.php',
      3 => 'group.php',
      4 => 'invoice.php',
      5 => 'log.php',
      6 => 'order.php',
      7 => 'payment.php',
      8 => 'plan.php',
      9 => 'reports.php',
      10 => 'resource.php',
      11 => 'subscription.php',
      12 => 'support.php',
      13 => 'transaction.php',
      14 => 'user.php',
      15 => 'wallet.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/includes/' => 
  array (
    'files' => 
    array (
      0 => 'defines.php',
      1 => 'functions.php',
      2 => 'includes.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/' => 
  array (
    'files' => 
    array (
      0 => 'installer.php',
      1 => 'message.php',
    ),
    'folders' => 
    array (
      0 => 'apps',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'languagepack_en_GB',
      1 => 'mod_payplans_quickicon',
      2 => 'mod_payplans_subscription',
      3 => 'plg_payplans_appmanager',
      4 => 'plg_payplans_discount',
      5 => 'plg_payplans_renewal',
      6 => 'plg_payplans_sample',
      7 => 'plg_payplans_system',
      8 => 'plg_payplans_upgrade',
      9 => 'plg_payplansregistration_auto',
      10 => 'plg_payplansregistration_joomla',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/languagepack_en_GB/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'plg_payplans',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/languagepack_en_GB/plg_payplans/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/mod_payplans_quickicon/' => 
  array (
    'files' => 
    array (
      0 => 'mod_payplans_quickicon.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/mod_payplans_subscription/' => 
  array (
    'files' => 
    array (
      0 => 'helper.php',
      1 => 'mod_payplans_subscription.php',
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'images',
      2 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/mod_payplans_subscription/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/mod_payplans_subscription/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/mod_payplans_subscription/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/' => 
  array (
    'files' => 
    array (
      0 => 'appmanager.php',
    ),
    'folders' => 
    array (
      0 => 'appmanager',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'helper.php',
      2 => 'view.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_credential_view.php',
      2 => 'default_filter.php',
      3 => 'default_uninstall_confirm.php',
      4 => 'default_uninstall_error.php',
      5 => 'default_uninstall_success.php',
    ),
    'folders' => 
    array (
      0 => '_media',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/tmpl/_media/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'images',
      2 => 'js',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/tmpl/_media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/tmpl/_media/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_appmanager/appmanager/tmpl/_media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_discount/' => 
  array (
    'files' => 
    array (
      0 => 'discount.php',
    ),
    'folders' => 
    array (
      0 => 'discount',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_discount/discount/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_discount/discount/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'discount',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_discount/discount/app/discount/' => 
  array (
    'files' => 
    array (
      0 => 'discount.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_renewal/' => 
  array (
    'files' => 
    array (
      0 => 'renewal.php',
    ),
    'folders' => 
    array (
      0 => 'renewal',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_renewal/renewal/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_renewal/renewal/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'renewal',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_renewal/renewal/app/renewal/' => 
  array (
    'files' => 
    array (
      0 => 'renewal.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_renewal/renewal/app/renewal/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_sample/' => 
  array (
    'files' => 
    array (
      0 => 'sample.php',
    ),
    'folders' => 
    array (
      0 => 'sample',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_sample/sample/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_sample/sample/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'migration.php',
      1 => 'post.php',
      2 => 'pre.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_system/' => 
  array (
    'files' => 
    array (
      0 => 'payplans.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/' => 
  array (
    'files' => 
    array (
      0 => 'upgrade.php',
    ),
    'folders' => 
    array (
      0 => 'upgrade',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/upgrade/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
      1 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/upgrade/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'upgrade',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/upgrade/app/upgrade/' => 
  array (
    'files' => 
    array (
      0 => 'helper.php',
      1 => 'upgrade.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/upgrade/app/upgrade/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplans_upgrade/upgrade/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'order_cancel_email.php',
      1 => 'upgrade_details.php',
      2 => 'upgrade_from.php',
      3 => 'upgrade_success.php',
      4 => 'upgrade_to.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_auto/' => 
  array (
    'files' => 
    array (
      0 => 'auto.php',
    ),
    'folders' => 
    array (
      0 => 'auto',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_auto/auto/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_auto/auto/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'registration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_joomla/' => 
  array (
    'files' => 
    array (
      0 => 'joomla.php',
    ),
    'folders' => 
    array (
      0 => 'joomla',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_joomla/joomla/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/administrator/components/com_payplans/installer/apps/plg_payplansregistration_joomla/joomla/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'registration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'default',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/' => 
  array (
    'files' => 
    array (
      0 => 'default_assets.php',
      1 => 'default_blank.php',
      2 => 'default_edit_log.php',
      3 => 'default_parameter.php',
      4 => 'default_view.php',
    ),
    'folders' => 
    array (
      0 => '_media',
      1 => '_partials',
      2 => 'app',
      3 => 'config',
      4 => 'dashboard',
      5 => 'group',
      6 => 'invoice',
      7 => 'log',
      8 => 'order',
      9 => 'payment',
      10 => 'plan',
      11 => 'reports',
      12 => 'resource',
      13 => 'subscription',
      14 => 'support',
      15 => 'transaction',
      16 => 'user',
      17 => 'wallet',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'images',
      2 => 'js',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'images',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'icons',
      1 => 'install',
      2 => 'setup',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/icons/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => '16',
      1 => '48',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/icons/16/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/icons/48/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/install/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/css/images/setup/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'icons',
      1 => 'setup',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/images/icons/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => '16',
      1 => '48',
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/images/icons/16/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/images/icons/48/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/images/setup/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/_partials/' => 
  array (
    'files' => 
    array (
      0 => 'default_partial_amount.php',
      1 => 'default_partial_invoice_table.php',
      2 => 'default_partial_modifier_table.php',
      3 => 'default_partial_order.php',
      4 => 'default_partial_resource_table.php',
      5 => 'default_partial_search.php',
      6 => 'default_partial_subscription.php',
      7 => 'default_partial_transaction_table.php',
      8 => 'default_partial_user.php',
      9 => 'default_partial_wallet_table.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/app/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_selectapp.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/config/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_edit_customization.php',
      2 => 'default_edit_migrate.php',
      3 => 'default_edit_settings.php',
      4 => 'default_migration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/dashboard/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_charts.php',
      2 => 'default_charts_details.php',
      3 => 'default_charts_linechart.php',
      4 => 'default_charts_numeric.php',
      5 => 'default_charts_numeric_partial.php',
      6 => 'default_charts_piechart.php',
      7 => 'default_migrate.php',
      8 => 'default_modsearch.php',
      9 => 'default_rebuildstats.php',
      10 => 'default_toolbar.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/group/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/invoice/' => 
  array (
    'files' => 
    array (
      0 => 'default_discount.php',
      1 => 'default_edit.php',
      2 => 'default_error.php',
      3 => 'default_filter.php',
      4 => 'default_grid.php',
      5 => 'default_help.php',
      6 => 'default_invoice_transaction.php',
      7 => 'default_sendinvoicelink.php',
      8 => 'default_sent.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/log/' => 
  array (
    'files' => 
    array (
      0 => 'default_filter.php',
      1 => 'default_grid.php',
      2 => 'default_view.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/order/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_order_invoice.php',
      4 => 'default_order_subscription.php',
      5 => 'default_order_transaction.php',
      6 => 'default_terminate.php',
      7 => 'default_terminate_confirm.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/payment/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_newpayment.php',
      4 => 'default_payment_transaction.php',
      5 => 'default_payment_transaction_table.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/plan/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_recurrence_validation.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/reports/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/resource/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/subscription/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_edit_invoice.php',
      2 => 'default_edit_transaction.php',
      3 => 'default_extend.php',
      4 => 'default_filter.php',
      5 => 'default_grid.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/support/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_installsuccess.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/transaction/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_new.php',
      4 => 'default_newtransaction.php',
      5 => 'default_refund_confirm.php',
      6 => 'default_refund_failure.php',
      7 => 'default_refund_success.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/user/' => 
  array (
    'files' => 
    array (
      0 => 'default_edit.php',
      1 => 'default_filter.php',
      2 => 'default_grid.php',
      3 => 'default_rechargewallet.php',
      4 => 'default_search.php',
      5 => 'default_selectplan.php',
      6 => 'default_user_order.php',
      7 => 'default_wallet_recharge_invoices.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/templates/default/wallet/' => 
  array (
    'files' => 
    array (
      0 => 'default_filter.php',
      1 => 'default_grid.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
      1 => 'config',
      2 => 'dashboard',
      3 => 'group',
      4 => 'invoice',
      5 => 'log',
      6 => 'manage',
      7 => 'order',
      8 => 'payment',
      9 => 'plan',
      10 => 'reports',
      11 => 'resource',
      12 => 'subscription',
      13 => 'support',
      14 => 'transaction',
      15 => 'user',
      16 => 'wallet',
    ),
  ),
  '/administrator/components/com_payplans/views/app/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/config/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/dashboard/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/group/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/invoice/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/log/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/manage/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/order/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/payment/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/plan/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/reports/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/resource/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/subscription/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/support/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/transaction/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/user/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/administrator/components/com_payplans/views/wallet/' => 
  array (
    'files' => 
    array (
      0 => 'view.html.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'appmanager',
      1 => 'discount',
      2 => 'renewal',
      3 => 'upgrade',
    ),
  ),
  '/plugins/payplans/appmanager/' => 
  array (
    'files' => 
    array (
      0 => 'appmanager.php',
    ),
    'folders' => 
    array (
      0 => 'appmanager',
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/' => 
  array (
    'files' => 
    array (
      0 => 'controller.php',
      1 => 'helper.php',
      2 => 'view.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'default.php',
      1 => 'default_credential_view.php',
      2 => 'default_filter.php',
      3 => 'default_uninstall_confirm.php',
      4 => 'default_uninstall_error.php',
      5 => 'default_uninstall_success.php',
    ),
    'folders' => 
    array (
      0 => '_media',
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/tmpl/_media/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'css',
      1 => 'images',
      2 => 'js',
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/tmpl/_media/css/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/tmpl/_media/images/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/appmanager/appmanager/tmpl/_media/js/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/discount/' => 
  array (
    'files' => 
    array (
      0 => 'discount.php',
    ),
    'folders' => 
    array (
      0 => 'discount',
    ),
  ),
  '/plugins/payplans/discount/discount/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
    ),
  ),
  '/plugins/payplans/discount/discount/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'discount',
    ),
  ),
  '/plugins/payplans/discount/discount/app/discount/' => 
  array (
    'files' => 
    array (
      0 => 'discount.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/renewal/' => 
  array (
    'files' => 
    array (
      0 => 'renewal.php',
    ),
    'folders' => 
    array (
      0 => 'renewal',
    ),
  ),
  '/plugins/payplans/renewal/renewal/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
    ),
  ),
  '/plugins/payplans/renewal/renewal/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'renewal',
    ),
  ),
  '/plugins/payplans/renewal/renewal/app/renewal/' => 
  array (
    'files' => 
    array (
      0 => 'renewal.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/plugins/payplans/renewal/renewal/app/renewal/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/upgrade/' => 
  array (
    'files' => 
    array (
      0 => 'upgrade.php',
    ),
    'folders' => 
    array (
      0 => 'upgrade',
    ),
  ),
  '/plugins/payplans/upgrade/upgrade/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'app',
      1 => 'tmpl',
    ),
  ),
  '/plugins/payplans/upgrade/upgrade/app/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'upgrade',
    ),
  ),
  '/plugins/payplans/upgrade/upgrade/app/upgrade/' => 
  array (
    'files' => 
    array (
      0 => 'helper.php',
      1 => 'upgrade.php',
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/plugins/payplans/upgrade/upgrade/app/upgrade/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'widgethtml.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplans/upgrade/upgrade/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'order_cancel_email.php',
      1 => 'upgrade_details.php',
      2 => 'upgrade_from.php',
      3 => 'upgrade_success.php',
      4 => 'upgrade_to.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplansregistration/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'auto',
      1 => 'joomla',
    ),
  ),
  '/plugins/payplansregistration/auto/' => 
  array (
    'files' => 
    array (
      0 => 'auto.php',
    ),
    'folders' => 
    array (
      0 => 'auto',
    ),
  ),
  '/plugins/payplansregistration/auto/auto/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/plugins/payplansregistration/auto/auto/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'registration.php',
    ),
    'folders' => 
    array (
    ),
  ),
  '/plugins/payplansregistration/joomla/' => 
  array (
    'files' => 
    array (
      0 => 'joomla.php',
    ),
    'folders' => 
    array (
      0 => 'joomla',
    ),
  ),
  '/plugins/payplansregistration/joomla/joomla/' => 
  array (
    'files' => 
    array (
    ),
    'folders' => 
    array (
      0 => 'tmpl',
    ),
  ),
  '/plugins/payplansregistration/joomla/joomla/tmpl/' => 
  array (
    'files' => 
    array (
      0 => 'registration.php',
    ),
    'folders' => 
    array (
    ),
  ),
) ;
		    	static $_fileroot =  '/Applications/XAMPP/xamppfiles/htdocs/cms_dev' ;
		    	
		    	static public function get($index)
    			{
    				$index = str_replace(self::$_fileroot, '', $index);
    				$index = DS.trim($index, DS).DS;
    				if( isset(self::$_filetree[$index]) ) {
    					return self::$_filetree[$index];
    				}
    
       				return false;
    			}
			}
	    