# idea platform
# mysql flavored schema

CREATE TABLE IF NOT EXISTS `my_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(128) NOT NULL,
  `option_value` longtext NOT NULL,
  `autoload` char(1) DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `my_sessions` (
  `session_id` varchar(40) DEFAULT '0' NOT NULL,
  `ip_address` varchar(16) DEFAULT '0' NOT NULL,
  `user_agent` varchar(50) NOT NULL,
  last_activity int(10) unsigned DEFAULT 0 NOT NULL,
  user_data text DEFAULT '' NOT NULL,
  PRIMARY KEY (session_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;