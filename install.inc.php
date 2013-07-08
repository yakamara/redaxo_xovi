<?php

/**
 * Version
 *
 * @author 
 *
 * @package redaxo4
 * @version svn:$Id$
 */

$REX['ADDON']['install']['xovi'] = 1;


$create_sql = rex_sql::factory();

$sql = "CREATE TABLE IF NOT EXISTS `rex_xovi` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `domain` varchar(255) default NULL,
  `api_token` varchar(255) default NULL,
  `search_engines` text default NULL,
  `chart_num_weeks`  int(11) default 0,
  `results` int(11) default 0,
  `getkeywords` text default NULL,
  `getkeywords_created` int(11) default 0,
  `getovitrend` text default NULL,
  `getovitrend_created` int(11) default 0,
  `getbacklinks` text default NULL,
  `getbacklinks_created` int(11) default 0,
  `getreportings` text default NULL,
  `getreportings_created` int(11) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;
";

$create_sql->setQuery($sql);


$create_sql = rex_sql::factory();

$sql = "CREATE TABLE IF NOT EXISTS `rex_xovi_keywordtrend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `getkeyword` text default NULL,
  `getkeywordtrend` int(11) default 0,
  `created` int(11) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;
";

$create_sql->setQuery($sql);

