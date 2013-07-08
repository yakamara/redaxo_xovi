<?php

/*
stellt credits dar 
*/
function returnCredits($lang)
{

	$xovi_credits = xovi_myCredits(true);
	
	$credits_table = '';
	
	if(!empty($xovi_credits)) {

		
		$credits_table = '
		<div class="rex-addon-output">
		  <h2 class="rex-hl2">'.$lang->msg("credits").'</h2>

		  <div class="rex-addon-content">
				
					<table class="rex-table" summary="Tabelle der verfügbaren Credits">
						<thead>
							<tr>
								<th>'.$lang->msg('settings_credit_total').'</th>
								<th>'.$lang->msg('settings_credit_used').'</th>
								<th>'.$lang->msg('settings_credit_left').'</th>
							</tr>
							<tr>
								<td>'.convertCredits($xovi_credits->apiResult->creditamount).'</td>
								<td>'.convertCredits($xovi_credits->apiResult->creditsused).'</td>
								<td>'.convertCredits($xovi_credits->apiResult->creditsleft).'</td>
							</tr>
						</thead>
						<tbody>
					</table>

		  </div>
  
		</div>
		';
	}
	
	return $credits_table;

}





/*
gibt xovi daten zurück
*/
function xovi_getOptions()
{
	global $REX;
	$table = 'rex_xovi';

	$options = array();

	$sql_read = rex_sql::factory();
	$sql_read->debugsql = 0;
	$sql_read->setQuery("SELECT * FROM $table");
	
	if ($sql_read->getRows() == 1)
	{
	
		$id = $sql_read->getValue("id");
		
		$sql = rex_sql::factory();
		$sql->setQuery("SELECT * FROM $table WHERE id=$id");
		if ($sql->getRows() == 1)
		{
		
			$options['domain'] 				= $sql->getValue("domain");
			$options['api_token'] 			= $sql->getValue("api_token");
			$options['search_engines'] 		= $sql->getValue("search_engines");
			$options['chart_num_weeks'] 	= $sql->getValue("chart_num_weeks");
			$options['results'] 			= $sql->getValue("results");
			
			$options['getkeywords'] 			= $sql->getValue("getkeywords");
			$options['getkeywords_created'] 	= $sql->getValue("getkeywords_created");
			
			$options['getovitrend'] 			= $sql->getValue("getovitrend");
			$options['getovitrend_created'] 	= $sql->getValue("getovitrend_created");
			
			$options['getbacklinks'] 			= $sql->getValue("getbacklinks");
			$options['getbacklinks_created'] 	= $sql->getValue("getbacklinks_created");
			
			$options['getreportings'] 			= $sql->getValue("getreportings");
			$options['getreportings_created'] 	= $sql->getValue("getreportings_created");
			return $options;
		}
		
	}

}


function convertCredits($v)
{
	return number_format($v,0,".",'.');
}















/**
 * xovi_build_query()
 * 
 * Creates the querystring for the xovi-api.
 * 
 * @param array $args
 * @return string
 */
 
function xovi_build_query($args) {
    if( function_exists('http_build_query')) {    
        $queryString = http_build_query($args);
    } else {
        $queryString = join("&", $args);
    }      
    return 'https://api.xovi.net/index.php?'.$queryString;
}


/**
 * xovi_apiConnect()
 * 
 * Connects to xovi-api using cURL.
 * 
 * @param array $args - Arguments for the query
 * @return type
 */
function xovi_apiConnect($args, $decode=0) {
 

    if(empty($args['key'])) {
        return array();
    }
    
    try {        

		if (!function_exists('curl_init')) {
			
			
			//echo __('cURL is not available', 'xovi');
			
			
		} else {
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                // needed for SSL
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);                // needed for SSL
			curl_setopt($ch, CURLOPT_URL, xovi_build_query($args));     // form and set URL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);             // parse data

			$output = curl_exec($ch);                                   // download the given URL
			curl_close($ch);                                            // close connection to free space
			
		}

		if ($decode) $tmp = json_decode($output, false);
		else $tmp = $output;
		
	}
    catch(Exception $ex) {
            echo $ex->getMessage();
    }
    
    return $tmp;
}








/**
 * xovi_myCredits()
 * 
 * Prepares and Executes a query 
 * to read the actual amount of credits. 
 * 
 * @global array $xovi_options
 * @return object 
 */
function xovi_myCredits($decode=0) {
    
    $xovi_options = xovi_getOptions();

    return xovi_apiConnect(array(
        'key'     => $xovi_options['api_token'],
        'service' => 'user',
        'method'  => 'getCreditState',
        'format'  => 'json',
    ),$decode);
}


/**
 * xovi_myOviTrend()
 * 
 * Prepares and Executes a query 
 * to get the OVI-trend
 * 
 * @global array $xovi_options
 * @return object
 */
function xovi_myOviTrend() {
    
    $xovi_options = xovi_getOptions();
        
    return xovi_apiConnect(array(
       'key'     => $xovi_options['api_token'],
       'domain'  => $xovi_options['domain'],
       'service' => 'seo',
       'method'  => 'getovitrend',
       'limit'   => $xovi_options['chart_num_weeks'],
       'sengine' => $xovi_options['search_engines'],
       'format'  => 'json'
    ),$decode);         
}


/**
 * xovi_myDomainTrend()
 * 
 * Prepares and Executes a query 
 * to get the Domain-trend
 * 
 * @global array $xovi_options
 * @return object
 */
function xovi_myDomainTrend() {

	$xovi_options = xovi_getOptions();

	return xovi_apiConnect(array(
		'key'     => $xovi_options['api_token'],
		'domain'  => $xovi_options['domain'],
		'limit'   => $options['chart_num_weeks'],
		'service' => 'links',
		'method'  => 'getdomaintrend',        
		'format'  => 'json'
	),$decode);
}


/**
 * xovi_myKeywords
 * 
 * Prepares and Executes a query 
 * to get the Keywords
 * 
 * @global array $xovi_options
 * @return object
 */
function xovi_myKeywords($skip = 0) {
    
    $xovi_options = xovi_getOptions();
                
	return xovi_apiConnect(array(
		'key'     => $xovi_options['api_token'],  
		'domain'  => $xovi_options["domain"],
		'sengine' => $xovi_options['search_engines'],
		'skip'    => $skip,
		'limit'   => $xovi_options['results'],
		'format'  => 'json',
		'service' => 'seo',
		'method'  => 'getkeywords',

	),$decode);              
}

/**
 * xovi_myKeywords
 * 
 * Prepares and Executes a query 
 * to get the Trend for the passed keyword
 * 
 * @param string $keyword Keyword
 * @global array $xovi_options
 * @return object
 */
function xovi_myKeywordTrend($keyword) {
 
    $xovi_options = xovi_getOptions(); 
             
    return xovi_apiConnect(array(
        'key'     => $xovi_options['api_token'],
        'domain'  => $xovi_options["domain"],          
        'limit'   => $xovi_options["chart_num_weeks"],
        'service' => 'seo',
        'method'  => 'getkeywordtrend',
        'sengine' => $xovi_options['search_engines'],
        'keyword' => $keyword,
        'format'  => 'json',
    ),$decode);
     
}

/**
 * xovi_myDailyKeywords()
 * 
 * Prepares and Executes a query 
 * to get all Daily Keywords
 * 
 * @global type $xovi_options
 * @return object
 */
function xovi_myDailyKeywords() {

    $xovi_options = xovi_getOptions();
 
    return xovi_apiConnect(array(
        'key'     	=> $xovi_options['api_token'],
        'domain'    => $xovi_options["domain"],
		'service'   => 'seo',
		'method'    => 'getDailyKeywords',
		'format'    => 'json'	
    ),$decode);  
}

/**
 * xovi_myDailyKeywordTrend()
 * 
 * Prepares and Executes a query 
 * to get the DailyTrend for the passed keyword
 * 
 * @global array $xovi_options
 * @param int $sengineId ID of the Search Engine
 * @param sting $keyword Keyword
 * @return object
 */
function xovi_myDailyKeywordTrend($sengineId, $keyword) {
    
    $xovi_options = xovi_getOptions();
    
    return xovi_apiConnect(array(
		'key'     	=> $xovi_options['api_token'],         
        'domain'    => $xovi_options["domain"],
		'method'	=> 'getDailyKeywordTrend',        
		'sengineId'	=> $sengineId,
		'keyword'	=> $keyword,
        'service'   => 'seo',
		'format'	=> 'json',
        'limit'         => $xovi_options['xovi[weeks]']
    ),$decode);
    
}

/**
 * 
 * xovi_myBacklinks()
 * 
 * gets 100 Backlinks of given domain.
 * 
 * @global array $xovi_options
 * @return object
 */
function xovi_myBacklinks($skip = 0) {
    
    $xovi_options = xovi_getOptions();
    
     return xovi_apiConnect(array(  
        'key'     => $xovi_options['api_token'], 
        'domain'  => $xovi_options["domain"],   
        'service' => 'links',
        'method'  => 'getbacklinks',        
        'format'  => 'json',
        'limit'   => $xovi_options['results'],
        'skip'    => $skip
    ),$decode);
}
/**
 * 
 * xovi_myReportings() 
 * 
 * Get's all Reports considered with given domain
 * 
 * @global array $xovi_options
 * @return object
 */
function xovi_myReportings() {
     
    $xovi_options = xovi_getOptions();
    
     return xovi_apiConnect(array(  
        'key'     => $xovi_options['api_token'],        
        'service' => 'report',
        'method'  => 'getdownloads',
        'domain'  => $xovi_options["domain"],
        'format'  => 'json',
   ),$decode);
}
?>