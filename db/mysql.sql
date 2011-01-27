# idea platform
# mysql flavored schema

CREATE TABLE `my_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(128) NOT NULL,
  `option_value` longtext NOT NULL,
  `autoload` char(1) DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1200 DEFAULT CHARSET=latin1;

