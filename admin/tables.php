<?php
/**
 * The Javaria Project
 * Copyright © 2019
 * Michel Noel
 * Datalight Analytics
 * http://www.datalightanalytics.com/
 *
 * Creative Commons Attribution-ShareAlike 4.0 International Public License
 * By exercising the Licensed Rights (defined below), You accept and agree to be bound by the terms and conditions of
 * this Creative Commons Attribution-ShareAlike 4.0 International Public License ("Public License"). To the extent this
 * Public License may be interpreted as a contract, You are granted the Licensed Rights in consideration of Your
 * acceptance of these terms and conditions, and the Licensor grants You such rights in consideration of benefits the
 * Licensor receives from making the Licensed Material available under these terms and conditions.
 *
 * File: tables.php
 * Last Modified: 8/24/19, 12:53 PM
 */

$tables = array();

$tables['chartjs'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `charttype` varchar(45) DEFAULT NULL,
  `datasets` varchar(2048) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `x_axis_name` varchar(45) DEFAULT NULL,
  `xgrid` int(1) DEFAULT '0',
  `xshow` int(1) DEFAULT '0',
  `xtick` int(1) DEFAULT '0',
  `xstack` int(1) DEFAULT '0',
  `xline` int(1) DEFAULT '0',
  `xlabel` int(1) DEFAULT '0',
  `y_axis_name` varchar(45) DEFAULT '0',
  `ygrid` int(1) DEFAULT '0',
  `yshow` int(1) DEFAULT '0',
  `ytick` int(1) DEFAULT '0',
  `ystack` int(1) DEFAULT '0',
  `yline` int(1) DEFAULT '0',
  `ylabel` int(1) DEFAULT '0',
  `legend` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)";

$tables['colours'] = "`idcolours` int(11) NOT NULL AUTO_INCREMENT,
  `colour_name` varchar(45) NOT NULL,
  `colour_code` varchar(45) NOT NULL,
  `border_colour_code` varchar(45) DEFAULT NULL,
  `border_colour_width` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idcolours`),
  UNIQUE KEY `idcolours_UNIQUE` (`idcolours`)";

$tables['themes'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colour_theme_name_UNIQUE` (`theme_name`)";

$tables['colours_theme_colours'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `colour_ids` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)";

$tables['dashboard_elements'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `dashboard_id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)";

$tables['dashboards'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL,
  `options` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)";

$tables['dashboards_assigned'] = "  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dashboard_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)";

$tables['data_connectors'] = "  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `server` varchar(45) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `instance` varchar(45) DEFAULT NULL,
  `db` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `pword` varchar(45) DEFAULT NULL,
  `servertype` varchar(45) DEFAULT NULL,
  `auth_ids` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)";

$tables['elements'] = " `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_type` varchar(45) NOT NULL,
  `element_id` int(11) NOT NULL,
  `refresh_rate` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_datetime` int(11) DEFAULT NULL,
  `options` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)";


$tables['group_info'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `parentid` varchar(45) NOT NULL DEFAULT '0',
  `notes` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`id`)";

$tables['group_members'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `groupadmin` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)";

$tables['htmlblocks'] = "  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `htmlcode` blob NOT NULL,
  `deleted` bit(1) DEFAULT b'0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)";

$tables['permissions_role'] = "  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL,
  `permissionid` int(11) NOT NULL,
  PRIMARY KEY (`id`)";

$tables['preferences'] = "`preference` varchar(45) NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`preference`)";

$tables['role_info'] = "  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `notes` varchar(45) DEFAULT NULL,
  `deleted` varchar(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)";

$tables['role_members'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)";

$tables['users'] = "`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `user_p` varchar(100) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `note` blob,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `default_dashboard` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)";

$tables['permissions'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)";

$tables['dataconnection_auths'] = "`id` int(11) NOT NULL AUTO_INCREMENT,
  `dataconnectionid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)";

$tables['example_data_simple'] =
  "`customer_name` varchar(100) NOT NULL,
  `total_revenue` double DEFAULT NULL,
  PRIMARY KEY (`customer_name`)";
