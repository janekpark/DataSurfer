<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
ini_set('display_errors', 1);
require 'Slim/Slim.php';
require 'Query.php';
require 'xlsxwriter.class.php';

\Slim\Slim::registerAutoloader();

//define column mappings
$geotypes = [
		"jurisdiction" => "city_name",
		"region" => "region_name",
		"zip" => "zip",
		"msa" => "msa_name",
		"sra" => "sra_name",
		"tract" => "tract_name",
		"elementary" => "elementary_name",
		"secondary" => "high_school_name",
		"unified" => "unified_name",
		"college" => "community_college_name",
		"sdcouncil" => "council",
		"supervisorial" => "supervisorial",
		"cpa" => "cpa_name"
];

$datasources = [
		"forecast" =>
		[
		//        11 => 11,
				12 => 6,
				13 => 13
		],
		"estimate" =>
		[
				2010 => 2,
				2011 => 15,
				2012 => 16,
				2013 => 17,
				2014 => 18,
                2015 => 19
		],
		"census" =>
		[
		//        1990 =>
				2000 => 12,
				2010 => 5
		]
];

$app = new \Slim\Slim();

$app->notFound(function() use ($app)
{
	$app->halt(400, "Bad Request");
});
$app->setName('datasurferapi');
$app->response->headers->set('Content-Type', 'application/json');



$app->get('/', function () use ($app)
{
    $app->response->headers->set('Content-Type', 'text/html');
    $app->render('home.php');
});

$app->get('/:datasource', function ($datasource) use ($app)
{
	$labels = ["forecast" => "series", "census"=>"year", "estimate"=>"year"];
	$response = array();
	
	foreach($GLOBALS['datasources'][$datasource] as $key => $id)
		$response[] = [$labels[$datasource] => $key];

	echo json_encode($response);
	
})->conditions(array('datasource' => 'census|forecast|estimate'));

$app->get('/:datasource/:year', function ($datasource, $year) use ($app)
{
	if(!array_key_exists($year, $GLOBALS['datasources'][$datasource]))
	{
		$app->halt(400, 'Invalid year or series id');
	}
	
	$response = array();
	
	foreach($GLOBALS['geotypes'] as $key => $id)
		$response[] = ['zone' => $key]; 

	echo json_encode($response);

})->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

//Get Information - Zone Names for Geotype
$app->get('/:datasource/:year/:geotype', function ($datasource, $series, $geotype)
{
	$columnName = $GLOBALS['geotypes'][$geotype];
	$series_id = 13;
	
	if ($datasource == 'forecast') 
	{
		$series_id = $series;	
	} elseif ($datasource == 'census')
	{
		if($series == 2000)
		{
			$series_id = 10;
		}
	}
	
	$params = [$series_id];

	$sql = "select lower(geozone) as {$geotype} from dim.mgra where series = $1 and geotype = $2 group by geozone order by geozone;";
	
	echo Query::getInstance()->getZonesAsJson($sql, $series_id, $geotype);
	
})->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

 //Census / Estimate - Housing
$app->get('/:datasource/:year/:geotype/:zone/housing', function ($datasource, $year, $geotype, $zone)
{
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, unit_type, cast(units as int), occupied, unoccupied, round(cast(vacancy_rate as numeric),5) as vacancy_rate FROM fact.summary_housing 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
     
})->conditions(array('datasource' => 'forecast|census|estimate'));

//Census / Estimate - Housing
$app->get('/:datasource/:year/:geotype/:zone/population', function ($datasource, $year, $geotype, $zone)
{
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, housing_type, population FROM fact.summary_population 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
	 
})->conditions(array('datasource' => 'forecast|census|estimate'));

//Census / Estimate - Ethnicity
$app->get('/:datasource/:year/:geotype/:zone/ethnicity', function ($datasource, $year, $geotype, $zone)
{
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, ethnic as ethnicity, population FROM fact.summary_ethnicity
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
})->conditions(array('datasource' => 'forecast|census|estimate'));

//Census / Estimate - Age
$app->get('/:datasource/:year/:geotype/:zone/age', function ($datasource, $year, $geotype, $zone)
{
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, sex, age_group as group_10yr, population FROM fact.summary_age
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
})->conditions(array('datasource' => 'forecast|census|estimate'));

//Census / Estimate - Income
$app->get('/:datasource/:year/:geotype/:zone/income', function ($datasource, $year, $geotype, $zone) use ($app)
{
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, ordinal, income_group, households FROM fact.summary_income 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
})->conditions(array('datasource' => 'forecast|census|estimate'));

$app->get('/:datasource/:year/:geotype/:zone/income/median', function ($datasource, $year, $geotype, $zone) use ($app)
{
    $datasource_id = $GLOBALS['datasources'][$datasource][$year];

    $sql = "SELECT geozone as {$geotype}, yr as year, median_inc FROM fact.summary_income_median 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
})->conditions(array('datasource' => 'forecast|census|estimate'));

$app->map('/:datasource/:year/:geotype/:zones+/export/pdf', function($datasource, $year, $geoType, $zones) use ($app)
{
	if (1 == count($zones))
	{
		$zone = $zones[0];
		$file_name = strtolower(join("_", array('sandag', $datasource, $year, $geoType, $zone)).".pdf");
		$file_path = join(DIRECTORY_SEPARATOR, array(".","pdf", $datasource, $year, $geoType, $file_name));
		
		if (file_exists($file_path))
		{
			$res = $app->response();
			$res['Content-Description'] = 'File Transfer';
			$res['Content-Type'] = 'application/pdf';
			$res['Content-Disposition'] ='attachment; filename='.$file_name;
			$res['Content-Transfer-Encoding'] = 'binary';
			$res['Expires'] = '0';
			$res['Cache-Control'] = 'must-revalidate';
			$res['Pragma'] = 'public';
		
			$res['Content-Length'] = filesize($file_path);
			readfile($file_path);
		} else
		{
			$app->halt(400, 'Invalid PDF Export Request');
		}
	} else 
	{
		natcasesort($zones);
		
		$zip = new ZipArchive();
		$ts = round(microtime(true) * 1000);
		$base_file_name = strtolower(join("_", array('sandag',$datasource, $year, $geoType))."_".$ts.".zip");
		
		$sys_file_name = './zip/'.$ts."_".$base_file_name;
		
		$zip->open($sys_file_name, ZIPARCHIVE::CREATE);
		
		foreach ($zones as $zone)
		{
			$file_name = strtolower(join("_", array('sandag', $datasource, $year, $geoType, $zone)).".pdf");
			$file_path = join(DIRECTORY_SEPARATOR, array(".","pdf", $datasource, $year, $geoType, $file_name));
			if (file_exists($file_path))
			{
				$zip->addFile($file_path, $file_name);
			} else
			{
				$app->halt(400, 'Invalid PDF Export Request');
			}
		}
		
		$zip->close();
		
		$res = $app->response();
		$res['Content-Description'] = 'File Transfer';
		$res['Content-Type'] = 'application/zip';
		$res['Content-Disposition'] ='attachment; filename='.$base_file_name;
		$res['Content-Transfer-Encoding'] = 'binary';
		$res['Expires'] = '0';
		$res['Cache-Control'] = 'must-revalidate';
		$res['Pragma'] = 'public';
		$res['Content-Length'] = filesize($sys_file_name);
		
		readfile($sys_file_name);
		
		unlink($sys_file_name);
	}
})->via('GET', 'POST')->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

$app->post('/:datasource/:year/:geotype/export/xlsx', function ($datasource, $year, $geoType) use ($app)
{
    $zones_param = $app->request()->post('zones');
    if ($zones_param == null)
    {
    	$app->halt(400, 'Invalid XLSX Export Request');
    }
    
    $zones = explode(',', strtolower($zones_param));
    
    natcasesort($zones);
    
    $file_name = strtolower(join("_", array($datasource, $year, $geoType)).".xlsx");
    $file_path = join(DIRECTORY_SEPARATOR, array(".","xlsx",$datasource,$year,$geoType, $file_name));
    
    $res = $app->response();
    $res['Content-Description'] = 'File Transfer';
    $res['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    $res['Content-Disposition'] ='attachment; filename='.$file_name;
    $res['Content-Transfer-Encoding'] = 'binary';
    $res['Expires'] = '0';
    $res['Cache-Control'] = 'must-revalidate';
    $res['Pragma'] = 'public';
    
    $ageArray[0] = [ucwords($geoType), 'Year', 'Sex', 'Group - 10 Year', 'Population'];
    $ethnicityArray[0] = [ucwords($geoType), 'Year', 'Ethnicity', 'Population'];
    $housingArray[0] = [ucwords($geoType), 'Year', 'Unit Type', 'Units', 'Occupied (Households)', 'Unoccupied', 'Vacancy Rate'];
    $populationArray[0] = [ucwords($geoType), 'Year', 'Housing Type', 'Population'];
    $incomeArray[0] = [ucwords($geoType), 'Year', 'Income Group', 'Households'];
    
    if("forecast"==$datasource)
    {
    	$jobsArray[0] = [ucwords($geoType), 'Year', 'Category', 'Jobs'];
    }

    $ageIterator = 1;
    $ethnicityIterator = 1;
    $housingIterator = 1;
    $populationIterator = 1;
    $incomeIterator = 1;
    $jobsIterator = 1;
        
    foreach($zones as $zone)
    {
    	$age_file_name = strtolower(join("_", array('age', $datasource, $year, $geoType, $zone)).".json");
    	$age_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $age_file_name));
    		
    	$ethnicity_file_name = strtolower(join("_", array('ethnicity', $datasource, $year, $geoType, $zone)).".json");
    	$ethnicity_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $ethnicity_file_name));
    	
        $housing_file_name = strtolower(join("_", array('housing', $datasource, $year, $geoType, $zone)).".json");
        $housing_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $housing_file_name));
        
        $population_file_name = strtolower(join("_", array('population', $datasource, $year, $geoType, $zone)).".json");
        $population_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $population_file_name));   
    	    
        $income_file_name = strtolower(join("_", array('income', $datasource, $year, $geoType, $zone)).".json");
        $income_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $income_file_name));
    	    
        $jobs_file_name = strtolower(join("_", array('jobs', $datasource, $year, $geoType, $zone)).".json");
        $jobs_file_path = join(DIRECTORY_SEPARATOR, array(".","json", $datasource, $year, $geoType, $jobs_file_name));
    		  
        $ageZoneArray = json_decode(file_get_contents($age_file_path), true);
        $ethnicityZoneArray = json_decode(file_get_contents($ethnicity_file_path), true);
        $housingZoneArray = json_decode(file_get_contents($housing_file_path), true);
    	$populationZoneArray = json_decode(file_get_contents($population_file_path), true);
    	$incomeZoneArray = json_decode(file_get_contents($income_file_path), true);

    	foreach($ageZoneArray as $arr)
    		$ageArray[$ageIterator++] = [$arr[$geoType], $arr['year'], $arr['sex'], $arr['group_10yr'], $arr['population']];
    		
    	foreach($ethnicityZoneArray as $arr)
    		$ethnicityArray[$ethnicityIterator++] =[$arr[$geoType], $arr['year'],$arr['ethnicity'],$arr['population']];
    		
    	foreach($housingZoneArray as $arr)
    		$housingArray[$housingIterator++] = [$arr[$geoType], $arr['year'], $arr['unit_type'], $arr['units'], $arr['occupied'], $arr['unoccupied'], $arr['vacancy_rate']];
    		
    	foreach($populationZoneArray as $arr)
    		$populationArray[$populationIterator++] = [$arr[$geoType], $arr['year'],$arr['housing_type'],$arr['population']];
    		
    	foreach($incomeZoneArray as $arr)
    		$incomeArray[$incomeIterator++] = [$arr[$geoType], $arr['year'],$arr['income_group'],$arr['households']];
    	
    	if("forecast"==$datasource)
    	{
    		$jobsZoneArray = json_decode(file_get_contents($jobs_file_path), true);
    		foreach($jobsZoneArray as $arr)
    			$jobsArray[$jobsIterator++] = [$arr[$geoType], $arr['year'], $arr['category'], $arr['jobs']];
    	}
    }
    
    $objPHPExcel = new XLSXWriter();
    $objPHPExcel->setAuthor("San Diego Association of Governments");
    
    $objPHPExcel->writeSheet($ageArray, 'Age');
    $objPHPExcel->writeSheet($ethnicityArray, 'Ethnicity');
    $objPHPExcel->writeSheet($housingArray, 'Housing');
    $objPHPExcel->writeSheet($populationArray, 'Population');
    $objPHPExcel->writeSheet($incomeArray, 'Income');
   
    if("forecast"==$datasource)
    	$objPHPExcel->writeSheet($jobsArray, 'Jobs');
    
    
    $objPHPExcel->writeToStdOut();
    
})->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

$app->get('/:datasource/:year/:geotype/:zones+/export/xlsx', function ($datasource, $year, $geotype, $zones) use ($app)
{
	if (count($zones) > 20)
	{
		$app->halt(400, 'Max Zone Request Exceeded (Limit: 20)');
	}
	natcasesort($zones);
	
	$zonelist = '{'.strtolower(implode(',', $zones)).'}';
	 
	$datasource_id = $GLOBALS['datasources'][$datasource][$year];
	
	$ts = round(microtime(true) * 1000);
	$file_name = strtolower(join("_", array($datasource, $year, $geotype))."_{$ts}.xlsx");
	
    $res = $app->response();
    $res['Content-Description'] = 'File Transfer';
    $res['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    $res['Content-Disposition'] ='attachment; filename='.$file_name;
    $res['Content-Transfer-Encoding'] = 'binary';
    $res['Expires'] = '0';
    $res['Cache-Control'] = 'must-revalidate';
    $res['Pragma'] = 'public';

    $writer = new XLSXWriter();
    $writer->setAuthor("San Diego Association of Governments");

    $ageHeader = array(
    		strtoupper($geotype) => 'string',
    		'YEAR' => 'string',
    		'SEX' => 'string',
    		'Group - 10 Year' => 'string',
    		'POPULATION' => 'integer'
    );
    $ageSql = "SELECT geozone as {$geotype}, yr as year, sex, age_group as group_10yr, population FROM fact.summary_age
    WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) AND age_group <> 'Total Population'";
    $ageArray = Query::getInstance()->getResultAsArray($ageSql, $datasource_id, $geotype, $zonelist);
    
    //*******ETHNICITY*************
    $ethnicityHeader = array(
        strtoupper($geotype) => 'string',
        'YEAR' => 'string',
    	'ETHNICITY' => 'string',
    	'POPULATION' => 'integer'
    );
    $ethnicitySql = "SELECT geozone as {$geotype}, yr as year, ethnic as ethnicity, population FROM fact.summary_ethnicity 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) AND ethnic <> 'Total Population' order by geozone, yr;";
    $ethnicityArray = Query::getInstance()->getResultAsArray($ethnicitySql, $datasource_id, $geotype, $zonelist);
    
    //*******HOUSING*************
    $housingHeader = array(
        strtoupper($geotype) => 'string',
    	'YEAR' => 'string',
    	'UNIT TYPE' => 'string',
    	'UNITS' => 'integer',
    	'OCCUPIED' => 'integer',
    	'UNOCCUPIED' => 'integer',
    	'VACANCY RATE' => 'double'
    );
    $housingSql = "SELECT geozone, yr, unit_type, units, occupied, unoccupied, vacancy_rate FROM fact.summary_housing 
        WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) and unit_type <> 'Total Units' order by geozone, yr;";
    $housingArray = Query::getInstance()->getResultAsArray($housingSql, $datasource_id, $geotype, $zonelist);
    
    //*******POPULATION*************
    $populationHeader = array(
        strtoupper($geotype) => 'string',
    	'YEAR' => 'string',
    	'HOUSING TYPE' => 'string',
    	'POPULATION' => 'integer',
    );
    $populationSql = "SELECT geozone, yr, housing_type, population FROM fact.summary_population 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) order by geozone, yr;";
    $populationArray = Query::getInstance()->getResultAsArray($populationSql, $datasource_id, $geotype, $zonelist);
    
    //*******INCOME*************
    $incomeHeader = array(
        strtoupper($geotype) => 'string',
    	'YEAR' => 'string',
    	'ORDINAL' => 'integer',
    	'INCOME GROUP' => 'string',
    	'HOUSEHOLDS' => 'integer'
    );
    $incomeSql = "SELECT geozone, yr, ordinal, income_group, households FROM fact.summary_income 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) order by geozone, yr, ordinal;";
    $incomeArray = Query::getInstance()->getResultAsArray($incomeSql, $datasource_id, $geotype, $zonelist);
    
    $writer->writeSheet($ageArray, 'Age', $ageHeader);
    $writer->writeSheet($ethnicityArray, 'Ethnicity', $ethnicityHeader);
    $writer->writeSheet($housingArray, 'Housing', $housingHeader);
    $writer->writeSheet($populationArray, 'Population', $populationHeader);
    $writer->writeSheet($incomeArray, 'Income', $incomeHeader);
   
    if("forecast"==$datasource)
    {
      $jobsHeader = array(
      		strtoupper($geotype) => 'string',
      		'YEAR' => 'string',
      		'EMPLOYMENT TYPE (Civilian)' => 'string',
      		'JOBS' => 'integer'
      );
      $jobsSql = "SELECT geozone, yr, employment_type, jobs FROM fact.summary_jobs 
      		WHERE datasource_id = :datasource_id AND geotype = :geotype AND lower(geozone) = ANY(:zonelist) and employment_type <> 'Total Jobs' order by geozone, yr;";
      $jobsArray = Query::getInstance()->getResultAsArray($jobsSql, $datasource_id, $geotype, $zonelist);
      $writer->writeSheet($jobsArray, 'Civilian Jobs', $jobsHeader);
    }
    
    $writer->writeToStdOut();
});

//Forecast - Ethnicity Change
$app->get('/forecast/:series/:geotype/:zone/ethnicity/change', function ($series, $geotype, $zone)
{
	$datasource_id = $GLOBALS['datasources']['forecast'][$series];
	
    $sql = "SELECT geozone as ${geotype}, ethnicity, pct_chg_byear_to_2020, pct_chg_2020_to_2025, pct_chg_2025_to_2030, 
    		pct_chg_2030_to_2035, pct_chg_2035_to_2040, pct_chg_2040_to_2045, pct_chg_2045_to_2050, 
    		pct_chg_base_to_horizon FROM fact.summary_ethnicity_change
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;

})->conditions(array('series' => '12|13'));

$app->get('/forecast/:series/:geotype/:zone/jobs', function ($series, $geotype, $zone) use ($app)
{
    $datasource_id = $GLOBALS['datasources']['forecast'][$series];

    $sql = "SELECT geozone as {$geotype}, yr as year, employment_type as category, jobs FROM fact.summary_jobs 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
	
})->conditions(array('series' => '12|13'));

$app->get('/:program/:series/:geotype/:zone/map', function ($datasource, $series, $geoType, $zone) use ($app)
{
	$res = $app->response();
	$res['Content-Type'] = 'image/jpeg';
	$res['Expires'] = '0';
	$res['Cache-Control'] = 'must-revalidate';
	$res['Pragma'] = 'public';
	
	$series_id = '13';
	
	if ($datasource == 'forecast') 
	{
		$series_id = $series;	
	} elseif ($datasource == 'census')
	{
		if($series == 2000)
		{
			$series_id = 10;
		}
	}
	
	$file_name = strtolower(join("_", array('sandag', 'series'.$series_id, $geoType, $zone)).".jpg");
	$file_path = join(DIRECTORY_SEPARATOR, array(".","map", 'series'.$series_id,$geoType, $file_name));
	
	if (file_exists($file_path))
	{
		$image = imagecreatefromjpeg($file_path);
		echo imagepng($image);
		imagedestroy($image);
	} else {
		$app->halt(400, 'Invalid PDF Export Request');
	}
	
})->conditions(array('datasource' => 'census|forecast|estimate', 'series' => '(\d){2,4}'));

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
