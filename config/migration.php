<?php defined('SYSPATH') or die('No direct script access.');

return array
(
	'table_name' => 'tbl_migration',
	'database_group' => 'default',
	'path' => APPPATH.'migrations'.DIRECTORY_SEPARATOR,
);

/*

CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

*/