<?php

/**
 * Version AddOn
 *
 * @author 
 *
 * @package redaxo4
 * @version svn:$Id$
 */


$subpage 			= rex_request("subpage","string");

$send	 			= rex_post("send","string");

$domain 			= rex_post("domain","string");
$api_token 			= rex_post("api_token","string");
$search_engines 	= rex_post("search_engines","string");

$table = 'rex_xovi';

require $REX["INCLUDE_PATH"].'/addons/xovi/functions/xovi_functions.inc.php';
require $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title('Xovi AddOn');


$debug = false;



if ( $send )
{

	$sql_read = rex_sql::factory();
	$sql_read->debugsql = $debug;
	$sql_read->setQuery("SELECT * FROM $table");
	
	$sql = rex_sql::factory();
	$sql->debugsql = $debug;
	$sql->setTable($table);

	if ($sql_read->getRows() > 0)
	{

		$id = $sql_read->getValue("id");

		$sql->setWhere('id='.$id);
		$sql->setValue('domain', $domain);
		$sql->setValue('api_token', $api_token);
		$sql->setValue('results', $results);

		$sql->update();
		echo rex_info($I18N->msg('settings_saved'));
		
	}else{
	

		$sql->setValue('domain', $domain);
		$sql->setValue('api_token', $api_token);
		$sql->setValue('search_engines', $search_engines);
		$sql->setValue('chart_num_weeks', 12); //DEFAULT
		$sql->setValue('results', 25); //DEFAULT

		$sql->insert();
		echo rex_info($I18N->msg('settings_saved'));
		
	}
	
}else{
	
	$sql_read = rex_sql::factory();
	$sql_read->setQuery("SELECT * FROM $table");
	
	if ($sql_read->getRows() == 1)
	{
	
		$id = $sql_read->getValue("id");
		
		$sql = rex_sql::factory();
		$sql->setQuery("SELECT * FROM $table WHERE id=$id");
		if ($sql->getRows() == 1)
		{
		
			$domain 					= $sql->getValue("domain");
			$api_token 					= $sql->getValue("api_token");
			$search_engines 			= $sql->getValue("search_engines");
			$chart_num_weeks 			= $sql->getValue("chart_num_weeks");
			$results 					= $sql->getValue("results");	
			
		}
		
	}
}


?>

<?php echo returnCredits($I18N);?>


<div class="rex-addon-output">
  <h2 class="rex-hl2"><?php echo $I18N->msg("settings"); ?></h2>

  <div class="rex-addon-content">
  
  	<div id="rex-addon-editmode" class="rex-form">
		<form action="" method="post">
		<fieldset class="rex-form-col-1">
			<input type="hidden" name="send" id="send" value="1" />
			<div class="rex-form-wrapper">


				<div class="rex-form-row">
					<p class="rex-form-col-a rex-form-text">
					  <label for="fromname"><?php echo $I18N->msg('settings_domain'); ?></label>
					  <input type="text" name="domain" id="domain" value="<?php echo $domain ?>" />
					</p>
				</div>
			
				<div class="rex-form-row">
					<p class="rex-form-col-a rex-form-text">
					  <label for="fromname"><?php echo $I18N->msg('settings_api_token'); ?></label>
					  <input type="text" name="api_token" id="api_token" value="<?php echo $api_token ?>" />
					</p>
				</div>
			
				<div class="rex-form-row">
					<p class="rex-form-col-a rex-form-text">
					  <label for="fromname"><?php echo $I18N->msg('settings_search_engines'); ?></label>
					  <input type="text" name="search_engines" id="search_engines" value="<?php echo $search_engines ?>" />
					</p>
				</div>
				
				<!--
				<div class="rex-form-row">
					<p class="rex-form-col-a rex-form-text">
					  <label for="fromname"><?php echo $I18N->msg('settings_chart_num_weeks'); ?></label>
					  <input type="text" name="chart_num_weeks" id="chart_num_weeks" value="<?php echo $chart_num_weeks ?>" />
					</p>
				</div>
			
				<div class="rex-form-row">
					<p class="rex-form-col-a rex-form-text">
					  <label for="fromname"><?php echo $I18N->msg('settings_results'); ?></label>
					  <input type="text" name="results" id="results" value="<?php echo $results ?>" />
					</p>
				</div>
				-->
			
				<div class="rex-form-row">
					  <p class="rex-form-col-a rex-form-submit">
						 <input class="rex-form-submit" type="submit" name="btn_save" value="<?php echo $I18N->msg('settings_save'); ?>" />
					  </p>
				</div>


			
			
			</div>

		 </fieldset>
		</form>
	</div>
  
  </div>

</div>



<?php
require $REX['INCLUDE_PATH'].'/layout/bottom.php';

?>