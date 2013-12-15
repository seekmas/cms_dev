CREATE  TABLE IF NOT EXISTS `#__payplans_support` (
  `support_id` INT NOT NULL AUTO_INCREMENT,
  `key`           VARCHAR(45) NOT NULL ,
  `value`         TEXT NULL,
  PRIMARY KEY (`support_id`) ,
  UNIQUE INDEX `idx_key` (`key` ASC) 
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 

CREATE TABLE IF NOT EXISTS `#__payplans_config` (
   `config_id` int(11) NOT NULL AUTO_INCREMENT,
   `key` varchar(255) NOT NULL,
   `value` text,
    PRIMARY KEY (`config_id`),
    UNIQUE KEY `idx_key` (`key`)
) ENGINE=MyISAM  
DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__payplans_user` (
  `user_id` 	INT 		NOT NULL,
  `params`  	TEXT 		NULL ,
  `address` 	VARCHAR(255)	NOT NULL DEFAULT '',
  `state` 		VARCHAR(255) 	DEFAULT '',
  `city` 		VARCHAR(255) 	DEFAULT '',
  `country` 	INT  		NOT NULL DEFAULT '0',
  `zipcode` 	VARCHAR(10) NOT NULL DEFAULT '',
  `preference` TEXT 	NOT NULL,
  PRIMARY KEY (`user_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 


CREATE TABLE IF NOT EXISTS `#__payplans_order` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `buyer_id` INT NOT NULL,
  `total` DECIMAL(15,5) NOT NULL DEFAULT '0.00000',
  `currency` CHAR(3) DEFAULT NULL,
  `status` INT NOT NULL DEFAULT 0,
  `checked_out` INT DEFAULT 0,
  `checked_out_time` DATETIME NULL,
  `created_date` DATETIME NOT NULL,
  `modified_date` DATETIME NOT NULL,
  `params` text,
  PRIMARY KEY (`order_id`),
  INDEX `idx_buyer_id` (`buyer_id` ASC) 
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ;

CREATE TABLE IF NOT EXISTS `#__payplans_payment` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `app_id` INT NOT NULL,
  `params` text,
  `invoice_id` INT NOT NULL DEFAULT 0,
  `user_id` INT NOT NULL DEFAULT 0,
  `gateway_params` text,
  `checked_out` INT DEFAULT 0,
  `checked_out_time` DATETIME NULL,
  `created_date` DATETIME NOT NULL,
  `modified_date` DATETIME NOT NULL,
  PRIMARY KEY (`payment_id`) ,
  INDEX `idx_invoice_id` (`invoice_id` ASC)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 


CREATE TABLE IF NOT EXISTS `#__payplans_currency` (
  `currency_id` CHAR(3) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `published` tinyint(1) DEFAULT 1,
  `params` text NULL,
  `symbol` char(5) DEFAULT NULL,
  PRIMARY KEY (`currency_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 


CREATE TABLE IF NOT EXISTS `#__payplans_country` (
  `country_id` INT NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `isocode2` CHAR(2) DEFAULT NULL,
  `isocode3` CHAR(3) DEFAULT NULL,
  `isocode3n` int(3) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 


CREATE TABLE  IF NOT EXISTS `#__payplans_address` (
  `address_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11) NOT NULL,
  `street1`    VARCHAR(64) NOT NULL DEFAULT '',
  `street2`    VARCHAR(64) DEFAULT NULL,
  `zipcode`    VARCHAR(10) NOT NULL DEFAULT '',
  `city`       VARCHAR(32) NOT NULL DEFAULT '',
  `state`      VARCHAR(32) DEFAULT NULL,
  `country_id`  int(11) NOT NULL DEFAULT '0',
  `zone_id`     int(11) NULL DEFAULT '0',
  `created_date`  DATETIME NOT NULL,
  `modified_date` DATETIME NOT NULL ,
  `is_personal`     int(1) NOT NULL DEFAULT  '1',
  `is_buisness`     int(1) NOT NULL DEFAULT  '1',
  `is_shipping`     int(1) NOT NULL DEFAULT  '1',
  PRIMARY KEY  (`address_id`),
  INDEX `idx_address_user_id` (`user_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ; 


CREATE TABLE IF NOT EXISTS `#__payplans_app` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `title`  varchar(255) NULL DEFAULT '',
  `type` varchar(255) NOT NULL,
  `description`	varchar(255) NULL DEFAULT '',
  `core_params` text,
  `app_params` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`app_id`)
) ENGINE=MyISAM
DEFAULT CHARACTER SET = utf8;





CREATE TABLE IF NOT EXISTS `#__payplans_subscription` (
  `subscription_id` 	INT NOT NULL AUTO_INCREMENT,
  `order_id` 	  	INT NOT NULL,
  `user_id` 	  	INT NOT NULL,
  `plan_id` 	  	INT NOT NULL,
  `status` 	 	INT NOT NULL DEFAULT 0,
  `total` 	      	DECIMAL(15,5) DEFAULT '0.00000',
  `subscription_date`  	DATETIME  DEFAULT '0000-00-00 00:00:00',
  `expiration_date`  	DATETIME  DEFAULT '0000-00-00 00:00:00',
  `cancel_date`  	DATETIME  DEFAULT '0000-00-00 00:00:00',
  `checked_out`  	INT 	  DEFAULT 0,
  `checked_out_time` 	DATETIME  DEFAULT '0000-00-00 00:00:00',
  `modified_date` 	DATETIME  DEFAULT '0000-00-00 00:00:00',
  `params`	  TEXT NOT NULL,
  PRIMARY KEY (`subscription_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ;



CREATE TABLE IF NOT EXISTS `#__payplans_plan` (
  `plan_id`		INT 		NOT NULL 	AUTO_INCREMENT,
  `title`		VARCHAR(255) 	NOT NULL,
  `published` 		TINYINT(1) 	DEFAULT 1,
  `visible` 		TINYINT(1) 	DEFAULT 1,
  `ordering` 		INT 		DEFAULT 0,
  `checked_out`  	INT NULL 	DEFAULT 0,
  `checked_out_time`	DATETIME 	DEFAULT '0000-00-00 00:00:00',
  `modified_date`	DATETIME  	DEFAULT '0000-00-00 00:00:00',
  `description`		TEXT 		DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  `params`		TEXT 		DEFAULT NULL,  
  PRIMARY KEY (`plan_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ;


CREATE TABLE IF NOT EXISTS `#__payplans_planapp` (
  `planapp_id` 	INT NOT NULL AUTO_INCREMENT,
  `plan_id` 	INT NOT NULL,
  `app_id` 	INT NOT NULL,
  PRIMARY KEY (`planapp_id`)
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8 ;


CREATE TABLE IF NOT EXISTS `#__payplans_plangroup` (
  `plangroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  PRIMARY KEY (`plangroup_id`)
) ENGINE=MyISAM  
DEFAULT CHARACTER SET = utf8 ;

CREATE TABLE IF NOT EXISTS `#__payplans_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '1',
  `visible` tinyint(1) DEFAULT '1',
  `ordering` int(11) DEFAULT '0',
  `description` text,
  `params` text,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM 
DEFAULT CHARACTER SET = utf8 ;

CREATE TABLE IF NOT EXISTS `#__payplans_log` (
  `log_id` 	int(11) NOT NULL AUTO_INCREMENT,
  `level` 	int(11) NOT NULL DEFAULT '0',
  `owner_id`  int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class` 	varchar(255) NOT NULL,
  `object_id` 	int(11) NOT NULL,
  `message`	TEXT NULL ,
  `user_ip` 	varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `content` 	TEXT NULL,
  `read`       tinyint(1) DEFAULT '0',
  `position` TEXT NULL,
  `previous_token` TEXT NULL,
  `current_token` TEXT NULL,
  PRIMARY KEY (`log_id`),
  INDEX `idx_level` (`level` ASC)
) 
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `#__payplans_resource` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `subscription_ids` TEXT DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`resource_id`),
  KEY `user_id` (`user_id`,`title`)
) 
ENGINE=MyISAM 
DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__payplans_invoice`(
	`invoice_id` int(11) NOT NULL AUTO_INCREMENT,
	`object_id` int(11) NOT NULL DEFAULT '0',
	`object_type` varchar(255) DEFAULT NULL,
	`user_id` int(11) NOT NULL,
	`subtotal` decimal(15,5) DEFAULT '0.00000',
	`total` decimal(15,5) NOT NULL DEFAULT '0.00000',
	`currency` char(3) DEFAULT NULL,
	`counter` int(11) DEFAULT '0',
	`status` int(11) NOT NULL DEFAULT '0',
	`params` text,
	`created_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	`checked_out` int(11) DEFAULT '0',
	`checked_out_time` datetime DEFAULT NULL,
  	PRIMARY KEY (`invoice_id`),
  	INDEX `idx_user_id` (`user_id` ASC),
  	INDEX `idx_order_id` (`object_id` ASC)
)
ENGINE=MyISAM 
DEFAULT CHARSET=utf8 
AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__payplans_modifier` (
  `modifier_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `amount` decimal(15,5) DEFAULT '0.00000',
  `type` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `message` text,
  `percentage` tinyint(1) NOT NULL DEFAULT '1',
  `serial` int(11) NOT NULL DEFAULT '0',
  `frequency` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`modifier_id`),
  KEY `idx_user_id` (`invoice_id`)
)
ENGINE=MyISAM 
DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__payplans_transaction`(
	`transaction_id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT DEFAULT 0,
	`invoice_id` INT DEFAULT 0,
	`current_invoice_id` INT DEFAULT 0,
	`payment_id` INT DEFAULT 0,
	`gateway_txn_id` varchar(255) DEFAULT NULL,
	`gateway_parent_txn` varchar(255) DEFAULT NULL,
	`gateway_subscr_id` varchar(255) DEFAULT NULL,
	`amount` DECIMAL(15,5) DEFAULT '0.00000',
	`reference` varchar(255) NULL,
	`message` varchar(255) NULL,
	`created_date` datetime NOT NULL,
	`params` TEXT NULL,
  	PRIMARY KEY (`transaction_id`),	
  	INDEX `idx_user_id` (`user_id` ASC)
)
ENGINE=MyISAM 
DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__payplans_wallet`(
	`wallet_id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`transaction_id` INT DEFAULT 0,
	`amount` DECIMAL(15,5) DEFAULT '0.00000',
	`message` varchar(255) NULL,
	`invoice_id` INT DEFAULT 0,
	`created_date` datetime NOT NULL,
  	PRIMARY KEY (`wallet_id`),
  	INDEX `idx_user_id` (`user_id` ASC)
)
ENGINE=MyISAM 
DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__payplans_statistics`(
	`statistics_id` INT NOT NULL AUTO_INCREMENT,
	`statistics_type` varchar(255) NULL,
	`purpose_id_1` INT NOT NULL,
	`purpose_id_2` INT DEFAULT 0,
	`count_1` INT DEFAULT 0,
	`count_2` INT DEFAULT 0,
	`count_3` INT DEFAULT 0,
	`count_4` INT DEFAULT 0,
	`count_5` INT DEFAULT 0,
	`count_6` INT DEFAULT 0,
	`count_7` INT DEFAULT 0,
	`count_8` INT DEFAULT 0,
	`count_9` INT DEFAULT 0,
	`count_10` INT DEFAULT 0,
	`details_1` varchar(255) NULL,
	`details_2` varchar(255) NULL,
	`message` varchar(255) NULL,
	`statistics_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
  	PRIMARY KEY (`statistics_id`),
  	INDEX `idx_statistics_date` (`statistics_date` ASC)
)
ENGINE=MyISAM 
DEFAULT CHARSET=utf8
AUTO_INCREMENT=1 ;
