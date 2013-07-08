<?php

/**
 * Version AddOn
 *
 * @author 
 *
 * @package redaxo4
 * @version svn:$Id$
 */



$loadAndSave = rex_request("load","string");

$table = 'rex_xovi';
$mess = "";

require $REX["INCLUDE_PATH"].'/addons/xovi/functions/xovi_functions.inc.php';
require $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title('Xovi AddOn');

$xovi_options = xovi_getOptions();
$stop = false;

$lastFeedInfo = '';

if (strlen($xovi_options['api_token']) < 10) $stop = true;

if ( strlen($xovi_options['getovitrend_created']) < 1 ) $mess = 'Es wurde noch kein Ergebnis gespeichert. (Kosten 20/100)<br /><br />';
else {

	$lastfeed_date = date("d.m.Y, h:m",$xovi_options['getovitrend_created']);
	$lastFeedInfo = 'Letztes Ergebnis geladen und gespeichert: <strong>'.$lastfeed_date.' Uhr</strong><br /><br />';

}

?>


<?php echo returnCredits($I18N);?>



<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("getovitrend"); ?></h2>

  <div class="rex-addon-content">
  

	<?php
	
	// ERGEBNIS LADEN UND SPEICHERN
	
	if ($loadAndSave)
	{
	
		$xovi_myOvi = xovi_myOviTrend();
	
		if(!empty($xovi_myOvi)) {
	
			$sql_read = rex_sql::factory();
			$sql_read->setQuery("SELECT * FROM $table");
	
			$sql = rex_sql::factory();
			$sql->debugsql = 0;
			$sql->setTable($table);

			//SETTINGS VORHANDEN, SPEICHERN
			if ($sql_read->getRows() > 0)
			{

				$id = $sql_read->getValue("id");

				$sql->setWhere('id='.$id);
				
				$sql->setValue('getovitrend', $xovi_myOvi);
				$sql->setValue('getovitrend_created', time());
			

				$sql->update();
				echo rex_info($I18N->msg('data_loaded'));
				
			}
		
		}
	
	}
	
	?>
	
	
	<div id="rex-addon-editmode" class="rex-form">
		<form action="" method="post">
		<fieldset class="rex-form-col-1">
			<input type="hidden" name="load" id="load" value="1" />
			<div class="rex-form-wrapper">
				
				<?php echo $mess; ?>
				
				<?php if (!$stop) { ?>
				<div class="rex-form-row">
				
					<?php echo $lastFeedInfo; ?>
					<input class="rex-form-submit" type="submit" name="btn_save" value="<?php echo $I18N->msg('feed_load_save'); ?>" /> (Kosten 20 Credits)
					 
				</div>
				<?php }else{ ?>
				
					Bitte unter Einstellungen alle Felder korrekt ausfüllen.
				
				<?php } ?>

			</div>

		 </fieldset>
		</form>
	</div>
	
   </div>	
</div>



<?php if ( strlen($xovi_options['getovitrend_created']) > 1 ) { ?>

<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("results"); ?></h2>

	
  <canvas id="myChart" width="740" height="400"></canvas>	

  

  <div class="rex-addon-content">
	<ul class="getkeywords">
	<?php

	if ( strlen($xovi_options['getovitrend']) > 1 )
	{
		
		$output = $xovi_options['getovitrend'];
		$output = json_decode($output, false);

		$n = count($output->apiResult);
		
		$labels = array();
		$ovi = array();
		
		for ($i=0; $i<$n; $i++)
		{
			echo '
			<li>
			date: <strong>' . $output->apiResult[$i]->date . '</strong> | 
			ovi: <strong>' . $output->apiResult[$i]->ovi . '</strong>
			</li>';
			
			$labels[] = str_replace("2013-", "", $output->apiResult[$i]->date);
			$ovi[] = $output->apiResult[$i]->ovi;
		}
		
	}
	$labels_array = json_encode($labels);
	$ovi_array = json_encode($ovi);	
	?>
	</ul>
	
  </div>


  <script>
  	var ctx = jQuery("#myChart").get(0).getContext("2d");

	var data = {
	labels : <?php echo $labels_array; ?>,
	datasets : [
			{
				fillColor : "rgba(85,180,229,0.5)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				data : <?php echo $ovi_array; ?>
			}
		]
	}
	
	
	new Chart(ctx).Line(data);
  </script>


</div>

<?php } ?>

<?php
require $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>