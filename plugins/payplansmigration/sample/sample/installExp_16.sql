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
(11, 'Widget for Upgrade', 'corewidget', '', '{"applyAll":1}', '{"widget_title":"Upgrade","widget_position":"payplans-dashboard-right","widget_class_suffix":"","app_type":"upgrade"}', 11, 1),
(12, 'Discount Coupon', 'discount', '', '{"applyAll":0}', '{"coupon_code":"AA1A","coupon_amount":10,"coupon_amount_type":"fixed","allowed_quantity":15,"used_quantity":"","publish_start":"","publish_end":"","reusable":"no","onlyFirstRecurringDiscount":0}', 12, 1),
(13, 'Basic Tax', 'basictax', '', '{"applyAll":1}', '{"tax_rate":12,"tax_country":99}', 13, 1),
(14, 'Content App', 'content', '', '{"applyAll":0}', '{"defined_locations":"view=dashboard&task=frontview","position":"prefix","custom_content":"PHA+V3JpdGUgeW91ciBjdXN0b20gY29udGVudCBoZXJlLjwvcD4=","filter":"custom_content","joomla_article":0}', 14, 1),
(15, 'Assign Plan', 'assignplan', '', '{"applyAll":0}', '{"assignPlan":9,"setPlanOnHold":"","setPlanOnExpire":""}', 15, 1),
(16, 'User Preferences', 'userpreferences', 'user can submit his preferences through front-end.', '{"applyAll":1}', '{}', 16, 1),
(17, 'Widget for User-Preference', 'corewidget', '', '{"applyAll":1}', '{"widget_title":"User Preferences","widget_position":"payplans-dashboard-right","widget_class_suffix":"","app_type":"userpreferences"}', 17, 1),
(18, 'User Details', 'userdetail', '', '{"applyAll":1}', '{"additional":"<config>\\n<params>\\n<param name="address" type="textarea" rows="4" cols="25" label="Shipping Adress"/>\\n\\n<param name="contact" type="radio" label="Contact me before shipping">\\n<option value="0">No</option>\\n<option value="1">Yes</option>\\n</param>\\n     \\n<param name="mobile" type="text" size="30" label="Mobile Phone"/>\\n\\n</params>\\n</config>"}', 18, 1);


INSERT IGNORE INTO `#__payplans_plan`  (`plan_id`, `title`, `published`, `visible`, `ordering`, `checked_out`, `checked_out_time`, `modified_date`, `description`, `details`, `params`) VALUES
(1, 'Forever Free', 1, 1, 1, 0, '0000-00-00 00:00:00', '2012-03-12 08:51:11', '<p>This is an example for <strong>Forever Free</strong> plan.</p>', '{"expirationtype":"forever","expiration":0,"recurrence_count":0,"trial_price_1":0,"trial_time_1":0,"trial_price_2":0,"trial_time_2":0,"price":0,"currency":"USD"}', '{"teasertext":"Simply free plan","user_activation":0,"css_class":"free","redirecturl":""}'),
(2, 'Recurring', 1, 1, 3, 0, '0000-00-00 00:00:00', '2012-03-12 08:56:39', '<p>This is an example for <strong>Recurring</strong> plan.</p>', '{"expirationtype":"recurring","expiration":100000000,"recurrence_count":20,"trial_price_1":0,"trial_time_1":0,"trial_price_2":0,"trial_time_2":0,"price":20,"currency":"USD"}', '{"teasertext":"Simple recurring plan","user_activation":0,"css_class":"hot","redirecturl":""}'),
(3, 'Life Time', 1, 1, 2, 0, '0000-00-00 00:00:00', '2012-03-12 08:56:19', '<p>This is an example for <strong>Life Time</strong> plan.</p>', '{"expirationtype":"fixed","expiration":0,"recurrence_count":0,"trial_price_1":0,"trial_time_1":0,"trial_price_2":0,"trial_time_2":0,"price":200,"currency":"USD"}', '{"teasertext":"Life time benefits","user_activation":0,"css_class":"new","redirecturl":""}'),
(4, 'Recurring and 1 Free Trial', 1, 1, 4, 0, '0000-00-00 00:00:00', '2012-03-12 08:57:40', '<p>This is an example for <strong>Recurring and 1 Free Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_1","expiration":100000000,"recurrence_count":20,"trial_price_1":0,"trial_time_1":100000000,"trial_price_2":0,"trial_time_2":0,"price":20,"currency":"USD"}', '{"teasertext":"Recurring with 1 free trial","user_activation":0,"css_class":"new","redirecturl":""}'),
(5, 'Recurring and 1 Trial', 1, 1, 5, 0, '0000-00-00 00:00:00', '2012-03-12 08:57:20', '<p>This is an example for <strong>Recurring and 1 Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_1","expiration":100000000,"recurrence_count":20,"trial_price_1":10,"trial_time_1":0,"trial_price_2":0,"trial_time_2":0,"price":20,"currency":"USD"}', '{"teasertext":"Recurring with 1 trial","user_activation":0,"css_class":"popular","redirecturl":""}'),
(6, 'Recurring and 2 Free Trial', 1, 1, 6, 0, '0000-00-00 00:00:00', '2012-03-12 08:57:54', '<p>This is an example for <strong>Recurring and 2 Free Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_2","expiration":100000000,"recurrence_count":20,"trial_price_1":0,"trial_time_1":100000000,"trial_price_2":0,"trial_time_2":100000000,"price":20,"currency":"USD"}', '{"teasertext":"Recurring with 2 free trial","user_activation":0,"css_class":"","redirecturl":""}'),
(7, 'Recurring and 2 Trial', 1, 1, 7, 0, '0000-00-00 00:00:00', '2012-03-12 08:54:28', '<p>This is an example for <strong>Recurring and 2 Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_2","expiration":100000000,"recurrence_count":20,"trial_price_1":1,"trial_time_1":7000000,"trial_price_2":5,"trial_time_2":14000000,"price":20,"currency":"USD"}', '{"teasertext":"Recurring with 2 trial","user_activation":0,"css_class":"","redirecturl":""}'),
(8, 'Recurring with 1st Free trial & 2nd Paid trial', 1, 1, 8, 0, '0000-00-00 00:00:00', '2012-03-12 08:54:28', '<p>This is an example for <strong>Recurring and 2 Trial</strong> plan.</p>', '{"expirationtype":"recurring_trial_2","expiration":200000000,"recurrence_count":10,"trial_price_1":0,"trial_time_1":7000000,"trial_price_2":5,"trial_time_2":14000000,"price":30,"currency":"USD"}', '{"teasertext":"Recurring with 2 trial","user_activation":0,"css_class":"","redirecturl":""}'),
(9, 'Bonus Plan', 1, 1, 9, 0, '0000-00-00 00:00:00', '2012-03-12 08:54:28', '', '{"expirationtype":"fixed","expiration":100000000,"recurrence_count":0,"trial_price_1":0,"trial_time_1":0,"trial_price_2":0,"trial_time_2":0,"price":0,"currency":"USD"}', '{"teasertext":"","user_activation":0,"css_class":"","redirecturl":""}');



INSERT IGNORE INTO `#__payplans_planapp` (`planapp_id`, `plan_id`, `app_id`) VALUES
(1, 4, 7),
(2, 1, 6),
(3, 1, 10),
(4, 2, 4),
(5, 3, 4),
(6, 2, 8),
(7, 3, 6),
(8, 5, 8),
(9, 3, 12),
(10, 2, 14),
(11, 2, 15);

CREATE TABLE IF NOT EXISTS `#__payplans_parentchild` (
						  `dependent_plan`    	   INT NOT NULL ,
						  `base_plan`              VARCHAR(255),
						  `relation`               INT NULL default -2, 
  						  `display_dependent_plan` INT NULL default 0,
  						  `params`                 TEXT,
  						   PRIMARY KEY (`dependent_plan`)
						);
INSERT IGNORE INTO `#__payplans_parentchild` (`dependent_plan`, `base_plan`, `relation`, `display_dependent_plan`, `params`) VALUES
(1, '', -2, 0, NULL),
(2, '', -2, 0, NULL),
(3, '2', -2, 0, NULL);


INSERT IGNORE INTO `#__payplans_group`(`group_id`, `title`, `parent`, `published`, `visible`, `ordering`, `description`, `params`) VALUES
(1, 'Popular', 0, 1, 1, 1, '<p>You can categories your plans into different groups.</p>', '{"css_class":"","teasertext":""}'),
(2, 'Latest', 0, 1, 1, 2, '<p>You can categories your plans into different groups.</p>', '{"css_class":"","teasertext":""}');


INSERT IGNORE INTO `#__payplans_plangroup`(`plangroup_id`, `group_id`, `plan_id`) VALUES
(1, 1, 3),
(2, 2, 2);


INSERT IGNORE INTO `#__payplans_config` (`key`, `value`) VALUES
('registrationType', 'auto'),
('currency', 'USD'),
('show_currency_as', 'symbol'),
('allowedMaxPercentDiscount', '100'),
('cronFrequency', '900'),
('cronAcessTime', '1372473389'),
('https', '0'),
('fractionDigitCount', '2'),
('accessLoginBlock', '1'),
('blockLogging', ''),
('useGroupsForPlan', '1'),
('displayExistingSubscribedPlans', '1'),
('walletRechargeAllowed', '0'),
('walletUtilizationAllowed', '0'),
('walletForRecurringAllowed', '0'),
('enableDiscount', '1'),
('multipleDiscount', '0'),
('microsubscription', '0'),
('displayRenewLink', '1'),
('currentCronAcessTime', '1372473389'),
('companyName', 'ReadyBytes Software Labs'),
('companyAddress', '59, Ashok Nagar Bhilwara'),
('companyCityCountry', 'Bhilwara, India'),
('companyPhone', '9982231113'),
('companyLogo', ''),
('expert_useminjs', '0'),
('expert_encryption_key', 'RBSLRBSL'),
('expert_wait_for_payment', '1000000'),
('expert_run_automatic_cron', '1'),
('expert_auto_delete', 'NEVER'),
('theme', 'orange'),
('layout', 'horizontal'),
('row_plan_counter', '2,2,3'),
('walletWidget', '1'),
('log_bucket','1'),
('expert_use_bootstrap_css', '1');
