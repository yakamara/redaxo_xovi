<?php

function rex_xovi_addjs($params){

	$subject = $params['subject'];
	
	$subject .= '
  <script type="text/javascript" src="../files/addons/xovi/json2html.js"></script>
  <script type="text/javascript" src="../files/addons/xovi/jquery.json2html.js"></script>
  <script type="text/javascript" src="../files/addons/xovi/Chart.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="../files/addons/xovi/css_main.css" media="screen, projection, print" />
';
	
	return $subject;

}

?>