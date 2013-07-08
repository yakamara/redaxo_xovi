<?php

/**
 * Version
 *
 * @author Peter Wolfrum, goldfischclub.de
 *
 * @package redaxo4
 * @version svn:$Id$
 */

$mypage = "xovi";
$REX['ADDON']['name'][$mypage] = 'Xovi';
$REX['ADDON']['perm'][$mypage] = 'xovi[]';
$REX['ADDON']['version'][$mypage] = '0.1';
$REX['ADDON']['author'][$mypage] = '';
$REX['ADDON']['supportpage'][$mypage] = 'www.redaxo.org/de/forum';


if($REX['REDAXO']){

	require $REX["INCLUDE_PATH"].'/addons/xovi/functions/ep_xovi.inc.php';

	$I18N->appendFile($REX['INCLUDE_PATH'].'/addons/xovi/lang/');
  	rex_register_extension('PAGE_HEADER', 'rex_xovi_addjs');

	$REX['ADDON'][$mypage]['SUBPAGES'] = array();
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( '' , $I18N->msg("settings"));
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( 'getkeywords' , $I18N->msg("getkeywords"));
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( 'getkeywordtrend' , $I18N->msg("getkeywordtrend"));
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( 'getovitrend' , $I18N->msg("getovitrend"));
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( 'getbacklinks' , $I18N->msg("getbacklinks"));
	$REX['ADDON'][$mypage]['SUBPAGES'][] = array( 'getreportings' , $I18N->msg("getreportings"));
}


