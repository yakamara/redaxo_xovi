<?php

/**
 * Version AddOn
 *
 * @author 
 *
 * @package redaxo4
 * @version svn:$Id$
 */





require $REX["INCLUDE_PATH"].'/addons/xovi/functions/xovi_functions.inc.php';
require $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title('Xovi AddOn');


$loadAndSave 	= rex_request("load","string");
$keyword 		= rex_request("keyword","string");
$trend_id 		= rex_request("trend_id","string");

$table = 'rex_xovi_keywordtrend';
$mess = "";

$xovi_options = xovi_getOptions();
$stop = false;
$debug = false;

if (strlen($xovi_options['api_token']) < 10) $stop = true;



$sql_read = rex_sql::factory();
$sql_read->debugsql = $debug;
$sql_read->setQuery("SELECT * FROM $table");
$list_trends = '<ul>';	

for($i=0;$i<$sql_read->getRows();$i++)
{

	$id 				= $sql_read->getValue("id"); 
	$getkeyword 		= $sql_read->getValue("getkeyword");
	$getkeywordtrend 	= $sql_read->getValue("getkeywordtrend");
	$created 			= $sql_read->getValue("created");    
	
	$list_trends .= '<li><a href="index.php?page=xovi&subpage=getkeywordtrend&trend_id='.$id.'">am '.date("d.m.Y, h:m",$created).' mit dem Keyword: <strong>'.$getkeyword.'</strong></a></li>';
	            
	
	$sql_read->counter++;

}		
$list_trends .= '</ul>';	

if ( $sql_read->getRows() == 0 ) $mess = 'Es wurde noch kein Ergebnis gespeichert. (Kosten 15)<br /><br />';

?>


<?php echo returnCredits($I18N);?>



<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("getkeywordtrend"); ?></h2>

  <div class="rex-addon-content">
  

	<?php
	
	// ERGEBNIS LADEN UND SPEICHERN
	
	if ($loadAndSave)
	{
	
		if (strlen($keyword) > 1)
		{
	
			$xovi_myKeywordTrend = xovi_myKeywordTrend($keyword);
			
			if(!empty($xovi_myKeywordTrend)) {
	
				$sql_read = rex_sql::factory();
				$sql_read->debugsql = $debug;
				$sql_read->setQuery("SELECT * FROM $table WHERE getkeyword='$keyword'");
				
				if ($sql_read->getRows() == 1)
				{
				
					$sql = rex_sql::factory();
					$sql->debugsql = $debug;
					$sql->setTable($table);
					$sql->setWhere('id='.$sql_read->getValue("id"));

					$sql->setValue('getkeyword', $keyword);
					$sql->setValue('getkeywordtrend', $xovi_myKeywordTrend);
					$sql->setValue('created', time());
		
					$sql->update();


				}else{
				
					$sql = rex_sql::factory();
					$sql->debugsql = $debug;
					$sql->setTable($table);

					$sql->setValue('getkeyword', $keyword);
					$sql->setValue('getkeywordtrend', $xovi_myKeywordTrend);
					$sql->setValue('created', time());
		
					$sql->insert();
				
				}
				
				echo rex_info($I18N->msg('data_loaded'));
				
			}
		
		}else{
			echo rex_info($I18N->msg('missing_keyword'));
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
						<p class="rex-form-col-a rex-form-text">
						  <label for="keyword">Keyword</label>
						  <input type="text" name="keyword" id="keyword" value="" />
						</p>
					</div>
					
					<div class="rex-form-row">
						<input class="rex-form-submit" type="submit" name="btn_search_save" value="<?php echo $I18N->msg('feed_search_load_save'); ?>" /> (Kosten 15 Credits)
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


<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("results_select"); ?></h2>
  
  <?php echo $list_trends; ?>
  
</div>

<?php if ( $trend_id > 0 ) { ?>

<div class="rex-addon-output">

  <h2 class="rex-hl2"><?php echo $I18N->msg("results"); ?></h2>

	
  <canvas id="myChart" width="740" height="400"></canvas>	

  

  <div class="rex-addon-content">
	<ul class="getkeywords">
	<?php

	$sql_read = rex_sql::factory();
	$sql_read->debugsql = 1;
	$rows = $sql_read->getArray("SELECT * FROM $table WHERE id='$trend_id'");
	if ( count($rows) == 1)
	{
	
		$row = current($rows);
		$output = $row["getkeywordtrend"];

		echo '<pre>';var_dump($row);
		$output = json_decode($output, false);

		$n = count($output->apiResult);
	
		$labels = array();
		$position = array();
	
		for ($i=0; $i<$n; $i++)
		{
			echo '
			<li>
			date: <strong>' . $output->apiResult[$i]->date . '</strong> | 
			position: <strong>' . $output->apiResult[$i]->position . '</strong>
			url: <strong>' . $output->apiResult[$i]->url . '</strong>
			</li>';
		
			$labels[] = str_replace("2013-", "", $output->apiResult[$i]->date);
			$ovi[] = $output->apiResult[$i]->position;
		}
		
	
		$labels_array = json_encode($labels);
		$position_array = json_encode($position);	
	
	}
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