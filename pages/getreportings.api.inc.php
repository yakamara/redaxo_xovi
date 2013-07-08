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
$mess = '';

require $REX["INCLUDE_PATH"].'/addons/xovi/functions/xovi_functions.inc.php';
require $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title('Xovi AddOn');

$xovi_options = xovi_getOptions();
$stop = false;

$lastFeedInfo = '';

if (strlen($xovi_options['api_token']) < 10) $stop = true;

if ( strlen($xovi_options['getreportings']) < 1 ) $mess = 'Es wurde noch kein Ergebnis gespeichert. (Kosten 5)<br /><br />';
else {

	$lastfeed_date = date("d.m.Y, h:m",$xovi_options['getreportings_created']);
	$lastFeedInfo = 'Letztes Ergebnis geladen und gespeichert: <strong>'.$lastfeed_date.' Uhr</strong><br /><br />';

}

?>


<?php echo returnCredits($I18N);?>



<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("getreportings"); ?></h2>

  <div class="rex-addon-content">
  

	<?php
	
	// ERGEBNIS LADEN UND SPEICHERN
	
	if ($loadAndSave)
	{
	
		$xovi_myReportings = xovi_myReportings();
	
		if(!empty($xovi_myReportings)) {
	
			$sql_read = rex_sql::factory();
			//$sql_read->debugsql = 1;
			$sql_read->setQuery("SELECT * FROM $table");
	
			$sql = rex_sql::factory();
			$sql->debugsql = 0;
			$sql->setTable($table);

			//SETTINGS VORHANDEN, SPEICHERN
			if ($sql_read->getRows() > 0)
			{

				$id = $sql_read->getValue("id");

				$sql->setWhere('id='.$id);
				
				$sql->setValue('getreportings', $xovi_myReportings);
				$sql->setValue('getreportings_created', time());
			

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
					<input class="rex-form-submit" type="submit" name="btn_save" value="<?php echo $I18N->msg('feed_load_save'); ?>" /> (Kosten: Kosten 5 Credits)
					 
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



<?php if ( strlen($xovi_options['getreportings_created']) > 1 ) { ?>

<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("results"); ?></h2>

  <div class="rex-addon-content">
	<ul class="getkeywords">
	<?php

	if ( strlen($xovi_options['getreportings']) > 1 )
	{
	
		$output = $xovi_options['getreportings'];
		$output = json_decode($output, false);
		
		$n = count($output->apiResult);
		
		for ($i=0; $i<$n; $i++)
		{
		
			
			$file = 'http://tool.xovi.de/process.reporting.php?task=downloadpdfreport&file='.$output->apiResult[$i]->id;
			
			echo '
			<li>
			domain: <strong>' . $output->apiResult[$i]->domain . '</strong><br />
			sengine: '. $output->apiResult[$i]->sengine . '<br />
			reportDate: '. $output->apiResult[$i]->reportDate . '<br />
			finished: '. $output->apiResult[$i]->finished . '<br />
			<a href="'.$file.'">'.$output->apiResult[$i]->domain.'</a>
			</li>';
		
		}
		
	}
		
	?>
	</ul>
	
  </div>

</div>

<?php } ?>

<script>
jQuery(".more").click(function() 
{
	jQuery(this).parent().find('.density').toggle();
	return false;
});
</script>

<?php
require $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>