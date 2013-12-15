INSERT IGNORE INTO `#__payplans_app` (`app_id`, `title`, `type`, `description`, `core_params`, `app_params`, `ordering`, `published`) VALUES
(1, 'Admin Pay', 'adminpay', 'Through this application Admin can create payment from back-end. There is no either way to create payment from back-end. This application can not be created, changed and deleted. And can not be used for front-end payment.', '{"applyAll":"1\\n\\n"}', '{}', 1, 1),
(2, 'Activation Email', 'email', '', '{"applyAll":1}', '{"send_to":"","send_cc":"","send_bcc":"","subject":"Thanks for subscribing at [[CONFIG_SITE_NAME]]","content":"PHA+SGkgW1tVU0VSX1JFQUxOQU1FXV0sPC9wPg0KPHA+VGhhbmsgeW91IGZvciBzdWJzY3JpYmluZyBhdCBbW0NPTkZJR19TSVRFX05BTUVdXSAhPC9wPg0KPHA+WW91ciBwdXJjaGFzZSBvZiBbW1BMQU5fVElUTEVdXSBoYXZlIGJlZW4gY29tcGxldGVkLiBXZSBhcmUgYSB0ZWFtIG9mIHByb2Zlc3Npb25hbCB3ZWIgZGV2ZWxvcGVycyBkZWRpY2F0ZWQgdG8gZGVsaXZlciBoaWdoLXF1YWxpdHkgZXh0ZW5zaW9ucywgdW5pcXVlIHNvbHV0aW9ucyBhbmQgYWR2YW5jZWQgc2VydmljZXMgZm9yIEpvb21sYSEsIHRoZSBtb3N0IHBvcHVsYXIgb3BlbiBzb3VyY2UgQ29udGVudCBNYW5hZ2VtZW50IFN5c3RlbSAoQ01TKSB3b3JsZHdpZGUuPC9wPg0KPHA+PHN0cm9uZz4gWW91ciBkZXRhaWxzIGF0IG91ciB3ZWJzaXRlIDwvc3Ryb25nPjwvcD4NCjxwPsKgPC9wPg0KPHVsPg0KPGxpPlVzZXJuYW1lIDogW1tVU0VSX1VTRVJOQU1FXV08L2xpPg0KPGxpPkFjdGl2YXRpb24gRGF0ZSA6IFtbU1VCU0NSSVBUSU9OX1NVQlNDUklQVElPTl9EQVRFXV08L2xpPg0KPGxpPkV4cGlyYXRpb24gRGF0ZSA6IFtbU1VCU0NSSVBUSU9OX0VYUElSQVRJT05fREFURV1dPC9saT4NCjwvdWw+DQo8ZGl2PkZvciBhbnkgZnVydGhlciBxdWVyeSB5b3UgY2FuIHJlcGx5IHRvIHRoaXMgZW1haWwgb3IgY2FuIGNvbnRhY3QgdXMgYXQgeHh4eEB5eXl5LmNvbTwvZGl2Pg0KPGRpdj5UaGFua3MgYWdhaW48L2Rpdj4NCjxkaXY+W1tDT05GSUdfU0lURV9OQU1FXV08L2Rpdj4NCjxkaXY+W1tDT05GSUdfU0lURV9VUkxdXTwvZGl2Pg0KPHA+PHN0cm9uZz4gPC9zdHJvbmc+PC9wPg0KPHA+wqA8L3A+","html_format":1,"on_status":1601}', 2, 1),
(3, 'Expiration Email', 'email', '', '{"applyAll":1}', '{"send_to":"","send_cc":"","send_bcc":"","subject":"Subscription for [[PLAN_TITLE]] expired at [[CONFIG_SITE_NAME]]","content":"PHA+wqA8L3A+DQo8cD5IaSBbW1VTRVJfUkVBTE5BTUVdXSw8L3A+DQo8cD5UaGFuayBZb3Ugc28gbXVjaCBmb3IgeW91ciBwYXN0IHN1YnNjcmlwdGlvbiBvZiBbW1BMQU5fVElUTEVdXSBhdCBbW0NPTkZJR19TSVRFX05BTUVdXS48L3A+DQo8cD5XZSBhcHByZWNpYXRlZCB0aGUgb3Bwb3J0dW5pdHkgdG8gc2VydmUgeW91IGFzIGEgdmFsdWVkIGN1c3RvbWVyLiBIb3dldmVyIEkgbm90aWNlIHlvdXIgc3Vic2NyaXB0aW9uIGV4cGlyZWQgb24gwqBbW1NVQlNDUklQVElPTl9FWFBJUkFUSU9OX0RBVEVdXSAsIGFuZCB5b3UgaGF2ZSBub3QgeWV0IHJlbmV3ZWQgeW91ciBzZXJ2aWNlLjwvcD4NCjxwPklmIHlvdSBleHBlcmllbmNlZCBhbnkgcHJvYmxlbSBkdXJpbmcgeW91ciBzdWJzY3JpcHRpb24sIHBsZWFzZSBsZXQgdXMga25vdyAoeHh4eEB5eXl5LmNvbSkgc28gdGhhdCB3ZSBjYW4gY29ycmVjdCBpdCBpbW1lZGlhdGVseS48L3A+DQo8cD48c3Ryb25nPllvdXIgZGV0YWlscyBhdCBvdXIgd2Vic2l0ZTwvc3Ryb25nPjwvcD4NCjxwPjxzdHJvbmc+PHNwYW4gc3R5bGU9ImZvbnQtd2VpZ2h0OiBub3JtYWw7Ij4gDQo8dWw+DQo8bGk+VXNlcm5hbWUgOiBbW1VTRVJfVVNFUk5BTUVdXTwvbGk+DQo8bGk+QWN0aXZhdGlvbiBEYXRlIDogW1tTVUJTQ1JJUFRJT05fU1VCU0NSSVBUSU9OX0RBVEVdXTwvbGk+DQo8bGk+RXhwaXJhdGlvbiBEYXRlIDogW1tTVUJTQ1JJUFRJT05fRVhQSVJBVElPTl9EQVRFXV08L2xpPg0KPC91bD4NCjwvc3Bhbj48L3N0cm9uZz48L3A+DQo8cD5Gb3IgYW55IFF1ZXJ5IHlvdSBjYW4gcmVwbHkgdG8gdGhpcyBFLW1haWwuPC9wPg0KPHA+VGhhbmtzIGFnYWluPC9wPg0KPHA+wqA8L3A+","html_format":1,"on_status":1603}', 3, 1),
(4, 'Offline Payment', 'offlinepay', '', '{"applyAll":0}', '{}', 4, 1),
(5, 'Paypal Payments', 'paypal', '', '{"applyAll":1}', '{"merchant_email":"enter_your_email_here@paypal.com","sandbox":0,"sandbox_merchant_email":"","merchant_id":"YS812092","currency":"USD","sandbox_customer_email":""}', 5, 1),
(6, 'Create Author', 'jusertype', '', '{"applyAll":0}', '{"jusertypeOnActive":3,"jusertypeOnHold":"","jusertypeOnExpire":2,"removeFromDefault":0,"on_status":1601,"jusertype":"Author"}', 6, 1),
(7, 'Create Editor', 'jusertype', '', '{"applyAll":0}', '{"jusertypeOnActive":4,"jusertypeOnHold":"","jusertypeOnExpire":2,"removeFromDefault":0,"on_status":1601,"jusertype":"Editor"}', 7, 1),
(8, 'Create Publisher', 'jusertype', '', '{"applyAll":0}', '{"jusertypeOnActive":5,"jusertypeOnHold":3,"jusertypeOnExpire":2,"removeFromDefault":0,"on_status":1601,"jusertype":"Publisher"}', 8, 1),
(9, 'Widget for jusertype', 'corewidget', '', '{"applyAll":1}', '{"widget_title":"Joomla User Type","widget_position":"payplans-dashboard-right","widget_class_suffix":"","app_type":"jusertype"}', 9, 1),
(10, 'Upgrade', 'upgrade', '', '{"applyAll":0}', '{"upgrade_to":"3|2"}', 10, 1),
(11, 'Widget for Upgrade', 'corewidget', '', '{"applyAll":1}', '{"widget_title":"Upgrade","widget_position":"payplans-dashboard-right","widget_class_suffix":"","app_type":"upgrade"}', 11, 1);


INSERT IGNORE INTO `#__payplans_plan`  (`plan_id`, `title`, `published`, `visible`, `ordering`, `checked_out`, `checked_out_time`, `modified_date`, `description`, `details`, `params`) VALUES
(1, 'Forever Free', 1, 1, 1, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:18', '<p>This is an example for <strong>Forever Free</strong> plan.</p>', '{"expirationtype":"forever","expiration":"000000000000","recurrence_count":"0","trial_price_1":"0","trial_time_1":"000000000000","trial_price_2":"0","trial_time_2":"000000000000","price":"0.00","currency":"USD"}', '{"teasertext":"Simply free plan","user_activation":0,"css_class":"free","redirecturl":""}'),
(2, 'Recurring', 1, 1, 3, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:31', '<p>This is an example for <strong>Recurring</strong> plan.</p>', '{"expirationtype":"recurring","expiration":"000100000000","recurrence_count":"20","trial_price_1":"0","trial_time_1":"000000000000","trial_price_2":"0","trial_time_2":"000000000000","price":"20.00","currency":"USD"}', '{"teasertext":"Simple recurring plan","user_activation":0,"css_class":"hot","redirecturl":""}'),
(3, 'Life Time', 1, 1, 2, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:25', '<p>This is an example for <strong>Life Time</strong> plan.</p>', '{"expirationtype":"fixed","expiration":"000000000000","recurrence_count":"0","trial_price_1":"0","trial_time_1":"000000000000","trial_price_2":"0","trial_time_2":"000000000000","price":"200.00","currency":"USD"}', '{"teasertext":"Life time benefits","user_activation":0,"css_class":"new","redirecturl":""}'),
(4, 'Recurring and 1 Free Trial', 1, 1, 4, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:36', '<p>This is an example for <strong>Recurring and 1 Free Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_1","expiration":"000100000000","recurrence_count":"20","trial_price_1":"0","trial_time_1":"000100000000","trial_price_2":"0","trial_time_2":"000000000000","price":"20.00","currency":"USD"}', '{"teasertext":"Recurring with 1 free trial","user_activation":0,"css_class":"new","redirecturl":""}'),
(5, 'Recurring and 1 Trial', 1, 1, 5, 0, '0000-00-00 00:00:00', '2012-03-12 08:57:20', '<p>This is an example for <strong>Recurring and 1 Trial</strong> plan.</p>', 'expirationtype=recurring_trial_1\nexpiration=000100000000\nrecurrence_count=20\ntrial_price_1=10\ntrial_time_1=000000000000\ntrial_price_2=0\ntrial_time_2=000000000000\nprice=20.00\ncurrency=USD\n\n', '{"teasertext":"Recurring with 1 trial","user_activation":0,"css_class":"popular","redirecturl":""}'),
(6, 'Recurring and 2 Free Trial', 1, 1, 6, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:57', '<p>This is an example for <strong>Recurring and 2 Free Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_2","expiration":"000100000000","recurrence_count":"20","trial_price_1":"0","trial_time_1":"000100000000","trial_price_2":"0","trial_time_2":"000100000000","price":"20.00","currency":"USD"}', '{"teasertext":"Recurring with 2 free trial","user_activation":0,"css_class":"","redirecturl":""}'),
(7, 'Recurring and 2 Trial', 1, 1, 7, 0, '0000-00-00 00:00:00', '2013-07-18 13:29:52', '<p>This is an example for <strong>Recurring and 2 Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_2","expiration":"000100000000","recurrence_count":"20","trial_price_1":"1","trial_time_1":"000007000000","trial_price_2":"5","trial_time_2":"000014000000","price":"20.00","currency":"USD"}', '{"teasertext":"Recurring with 2 trial","user_activation":0,"css_class":"","redirecturl":""}');



INSERT IGNORE INTO `#__payplans_planapp` (`planapp_id`, `plan_id`, `app_id`) VALUES
(1, 4, 7),
(2, 1, 6),
(3, 1, 10),
(4, 2, 4),
(5, 3, 4),
(6, 2, 8),
(7, 3, 6),
(8, 5, 8);


INSERT IGNORE INTO `#__payplans_config` ( `key`, `value`) VALUES
( 'expert_use_bootstrap_jquery', '1'),
( 'expert_use_jquery', '1'),
( 'blockLogging', ''),
( 'expert_encryption_key', 'RBSLRBSL'),
( 'cronAcessTime', '1372465904'),
( 'cronFrequency', '900'),
( 'walletForRecurringAllowed', '0'),
( 'walletUtilizationAllowed', '0'),
( 'multipleDiscount', '0'),
( 'displayExistingSubscribedPlans', '1'),
( 'microsubscription', '0'),
( 'https', '0'),
( 'displayRenewLink', '1'),
( 'currentCronAcessTime', '1372465904'),
( 'allowedMaxPercentDiscount', '100'),
( 'fractionDigitCount', '2'),
( 'show_currency_at', 'before'),
( 'show_currency_as', 'symbol'),
( 'currency', 'USD'),
( 'date_format', '%Y-%m-%d'),
( 'enableDiscount', '1'),
( 'walletRechargeAllowed', '0'),
( 'useGroupsForPlan', '0'),
( 'accessLoginBlock', '1'),
( 'registrationType', 'auto'),
( 'expert_useminjs', '0'),
( 'expert_run_automatic_cron', '1'),
( 'expert_wait_for_payment', '000001000000'),
( 'expert_auto_delete', 'NEVER'),
( 'companyAddress', '59, Ashok Nagar Bhilwara'),
( 'companyName', 'ReadyBytes Software Labs'),
( 'companyCityCountry', 'Bhilwara, India'),
( 'companyPhone', '9982231113'),
( 'add_token', ''),
( 'note', ''),
( 'walletWidget', '1'),
( 'subscription_status', '["0","1601","1602","1603"]'),
( 'theme', 'dark_ef723b'),
( 'rtl_support', '0'),
( 'layout', 'horizontal'),
( 'row_plan_counter', '2,2,3'),
( 'price_decimal_separator', '.'),
( 'rewriter', ''),
( 'companyLogo', ''),
('log_bucket','1'),
( 'expert_use_bootstrap_css', '1');