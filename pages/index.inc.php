<?php

/**
 * Version AddOn
 *
 * @author 
 *
 * @package redaxo4
 * @version svn:$Id$
 */
$page = 'xovi';
$subpage = rex_request("subpage","string");


switch($subpage)
  {
    case('getkeywords'):
      	include $REX["INCLUDE_PATH"]."/addons/$page/pages/$subpage.api.inc.php";
      	break;
    case('getovitrend'):
    	include $REX["INCLUDE_PATH"]."/addons/$page/pages/$subpage.api.inc.php";
      	break;
    case('getkeywordtrend'):
    	include $REX["INCLUDE_PATH"]."/addons/$page/pages/$subpage.api.inc.php";
      	break;
    case('getbacklinks'):
    	include $REX["INCLUDE_PATH"]."/addons/$page/pages/$subpage.api.inc.php";
      	break;
    case('getreportings'):
    	include $REX["INCLUDE_PATH"]."/addons/$page/pages/$subpage.api.inc.php";
      	break;
    default:
      	include $REX["INCLUDE_PATH"]."/addons/$page/pages/setup.inc.php";
      	break;
  }

?>