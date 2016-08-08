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

$app = new \Slim\Slim();

$app->notFound(function() use ($app)
{
	$app->halt(400, "Bad Request");
});
$app->setName('datasurferapi');
$app->response->headers->set('Content-Type', 'application/json');

$app->hook('slim.after.router', function() use ($app) {
    $env = $app->environment();
    if ($env['pdf.zip.file'])
    {
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$env['pdf.zip.file']);
        header('Content-Length: ' . filesize($env['pdf.zip.path']));
        header('Content-Description: ZIP File Transfer');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        
        set_time_limit(0);
        $file = @fopen($env['pdf.zip.path'], 'rb');
        while (!feof($file))
        {
            print @fread($file, 1024*8);
            ob_flush();
            flush();
        }
        
        unlink($env['pdf.zip.path']);
    }
});

$app->get('/', function () use ($app)
{
    $app->response->headers->set('Content-Type', 'text/html');
    $app->render('home.php');
});

$app->get('/:datasource', function ($datasource) use ($app)
{
    $labels = ["forecast" => "series", "census"=>"year", "estimate"=>"year"];
    $columns = ["forecast" => "series", "census"=>"yr", "estimate"=>"yr"];

	$sql = "SELECT {$columns[$datasource]}  as {$labels[$datasource]} FROM dim.datasource ds INNER JOIN dim.datasource_type dsType ON ds.datasource_type_id = dsType.datasource_type_id WHERE lower(datasource_type) = lower($1) AND is_active ORDER BY 1;";
	
	echo Query::getInstance()->getYearsAsJson($sql, $datasource);
    
})->conditions(array('datasource' => 'census|forecast|estimate'));

$app->get('/:datasource/:year', function ($datasource, $year) use ($app)
{
    $datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
    
	if(!$datasource_id)
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
$app->get('/:datasource/:year/:geotype/:zone/housing', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
		$sql = "SELECT geozone as {$geotype}, yr as year, unit_type, cast(units as int), occupied, unoccupied, round(cast(vacancy_rate as numeric),5) as vacancy_rate FROM fact.summary_housing 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
		
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

//Census / Estimate - Population
$app->get('/:datasource/:year/:geotype/:zone/population', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');

		$sql = "SELECT geozone as {$geotype}, yr as year, housing_type, population FROM fact.summary_population 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
		
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

//Census / Estimate - Ethnicity
$app->get('/:datasource/:year/:geotype/:zone/ethnicity', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');

		$sql = "SELECT geozone as {$geotype}, yr as year, ethnic as ethnicity, population FROM fact.summary_ethnicity
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

//Census / Estimate - Age
$app->get('/:datasource/:year/:geotype/:zone/age', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');

		$sql = "SELECT geozone as {$geotype}, yr as year, sex, age_group as group_10yr, population FROM fact.summary_age
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

//Census / Estimate - Income
$app->get('/:datasource/:year/:geotype/:zone/income', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');

		$sql = "SELECT geozone as {$geotype}, yr as year, ordinal, income_group, households FROM fact.summary_income 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

$app->get('/:datasource/:year/:geotype/:zone/income/median', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');

		$sql = "SELECT geozone as {$geotype}, yr as year, median_inc FROM fact.summary_income_median 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

		$json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

		echo $json;
	})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

$app->get('/:datasource/:year/:geotype/all/export/pdf', function($datasource, $year, $geoType) use ($app)
	{
		$ts = round(microtime(true) * 1000);
		$base_file_name = strtolower(join("_", array('sandag',$datasource, $year, $geoType)).".zip");
		$sys_file_name = './zip/'.$ts."_".$base_file_name;
		
		$zip = new ZipArchive();
		$zip->open($sys_file_name, ZIPARCHIVE::CREATE);
		
		$pdf_dir = join(DIRECTORY_SEPARATOR, array(".","pdf", $datasource, $year, $geoType));
		
		if($handle = opendir($pdf_dir)) {
			while ($entry = readdir($handle)) {
				if (strstr($entry, '.pdf')) {
					$zip->addFile(join(DIRECTORY_SEPARATOR, array($pdf_dir,$entry)),$entry);
				}
			}
			closedir($handle);
		}
		
		$zip->close();
		
		$env = $app->environment();
		$env["pdf.zip.path"] = $sys_file_name;
		$env["pdf.zip.file"] = $base_file_name;
		
})->conditions(array('datasource' => 'forecast|census|estimate', 'year' => '(\d){2,4}'));

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

$app->get('/:datasource/:year/:geotype/all/export/xlsx', function ($datasource, $year, $geotype) use ($app)
{
    $datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
    
    if (!$datasource_id)
        $app->halt(400, 'Invalid year or series id');
    
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

    //*******AGE*************
    $ageSql = "SELECT geozone as {$geotype}, yr as year, sex, age_group as group_10yr, population FROM fact.summary_age
                WHERE datasource_id = :datasource_id AND geotype = :geotype AND age_group <> 'Total Population'";
    $writer->writeSheet(
                Query::getInstance()->getResultAsArray($ageSql, $datasource_id, $geotype, null),
                'Age'
                ,array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'SEX' => 'string',
                    'Group - 10 Year' => 'string',
                    'POPULATION' => 'integer'
                )
    );

    //*******ETHNICITY*************
    $ethnicitySql = "SELECT geozone as {$geotype}, yr as year, ethnic as ethnicity, population FROM fact.summary_ethnicity 
                        WHERE datasource_id = :datasource_id AND geotype = :geotype AND ethnic <> 'Total Population' order by geozone, yr;";
    
    $writer->writeSheet(
                Query::getInstance()->getResultAsArray($ethnicitySql, $datasource_id, $geotype, null),
                'Ethnicity',
                array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'ETHNICITY' => 'string',
                    'POPULATION' => 'integer'
                )
    );
    
    //*******HOUSING*************
    $housingSql = "SELECT geozone as {$geotype}, yr, unit_type, units, occupied, unoccupied, vacancy_rate FROM fact.summary_housing 
        WHERE datasource_id = :datasource_id AND geotype = :geotype and unit_type <> 'Total Units' order by geozone, yr;";

    $writer->writeSheet(
                Query::getInstance()->getResultAsArray($housingSql, $datasource_id, $geotype, null),
                'Housing',
                array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'UNIT TYPE' => 'string',
                    'UNITS' => 'integer',
                    'OCCUPIED' => 'integer',
                    'UNOCCUPIED' => 'integer',
                    'VACANCY RATE' => 'double'
                )
    );

    //*******POPULATION*************
    $populationSql = "SELECT geozone as {$geotype}, yr, housing_type, population FROM fact.summary_population 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype order by geozone, yr;";
    
    $writer->writeSheet(
                Query::getInstance()->getResultAsArray($populationSql, $datasource_id, $geotype, null),
                'Population',
                array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'HOUSING TYPE' => 'string',
                    'POPULATION' => 'integer',
                )
    );

    //*******INCOME*************
    $incomeSql = "SELECT geozone as {$geotype}, yr, ordinal, income_group, households FROM fact.summary_income 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype order by geozone, yr, ordinal;";
   
    $writer->writeSheet(
                Query::getInstance()->getResultAsArray($incomeSql, $datasource_id, $geotype, null),
                'Income',
                array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'ORDINAL' => 'integer',
                    'INCOME GROUP' => 'string',
                    'HOUSEHOLDS' => 'integer'
                )
    );
   
    if("forecast"==$datasource)
    {
        $jobsSql = "SELECT geozone as {$geotype}, yr, employment_type, jobs FROM fact.summary_jobs 
                        WHERE datasource_id = :datasource_id AND geotype = :geotype and employment_type <> 'Total Jobs' order by geozone, yr;";
        $writer->writeSheet(
                Query::getInstance()->getResultAsArray($jobsSql, $datasource_id, $geotype, null),
                'Civilian Jobs',
                array(
                    strtoupper($geotype) => 'string',
                    'YEAR' => 'string',
                    'EMPLOYMENT TYPE (Civilian)' => 'string',
                    'JOBS' => 'integer'
                )
        );
    }
    
    $writer->writeToStdOut();

		})->conditions(array('datasource' => 'forecast|estimate', 'year' => '(\d){2,4}'));

$app->get('/:datasource/:year/:geotype/all/export/xlsx', function ($datasource, $year, $geotype) use ($app)
	{
	
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
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

		//*******AGE*************
		$ageSql = "SELECT geozone as {$geotype}, yr as year, sex, age_group as group_10yr, population FROM fact.summary_age
                WHERE datasource_id = :datasource_id AND geotype = :geotype AND age_group <> 'Total Population'";
		$writer->writeSheet(
				Query::getInstance()->getResultAsArray($ageSql, $datasource_id, $geotype, null),
				'Age'
				,array(
					strtoupper($geotype) => 'string',
					'YEAR' => 'string',
					'SEX' => 'string',
					'Group - 10 Year' => 'string',
					'POPULATION' => 'integer'
					)
				);

			//*******ETHNICITY*************
			$ethnicitySql = "SELECT geozone as {$geotype}, yr as year, ethnic as ethnicity, population FROM fact.summary_ethnicity 
                        WHERE datasource_id = :datasource_id AND geotype = :geotype AND ethnic <> 'Total Population' order by geozone, yr;";
			
			$writer->writeSheet(
				Query::getInstance()->getResultAsArray($ethnicitySql, $datasource_id, $geotype, null),
				'Ethnicity',
				array(
					strtoupper($geotype) => 'string',
					'YEAR' => 'string',
					'ETHNICITY' => 'string',
					'POPULATION' => 'integer'
					)
				);
			
			//*******HOUSING*************
			$housingSql = "SELECT geozone as {$geotype}, yr, unit_type, units, occupied, unoccupied, vacancy_rate FROM fact.summary_housing 
        WHERE datasource_id = :datasource_id AND geotype = :geotype and unit_type <> 'Total Units' order by geozone, yr;";

			$writer->writeSheet(
				Query::getInstance()->getResultAsArray($housingSql, $datasource_id, $geotype, null),
				'Housing',
				array(
					strtoupper($geotype) => 'string',
					'YEAR' => 'string',
					'UNIT TYPE' => 'string',
					'UNITS' => 'integer',
					'OCCUPIED' => 'integer',
					'UNOCCUPIED' => 'integer',
					'VACANCY RATE' => 'double'
					)
				);

			//*******POPULATION*************
			$populationSql = "SELECT geozone as {$geotype}, yr, housing_type, population FROM fact.summary_population 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype order by geozone, yr;";
			
			$writer->writeSheet(
				Query::getInstance()->getResultAsArray($populationSql, $datasource_id, $geotype, null),
				'Population',
				array(
					strtoupper($geotype) => 'string',
					'YEAR' => 'string',
					'HOUSING TYPE' => 'string',
					'POPULATION' => 'integer',
					)
				);

			//*******INCOME*************
			$incomeSql = "SELECT geozone as {$geotype}, yr, ordinal, income_group, households FROM fact.summary_income 
    		WHERE datasource_id = :datasource_id AND geotype = :geotype order by geozone, yr, ordinal;";
			
			$writer->writeSheet(
				Query::getInstance()->getResultAsArray($incomeSql, $datasource_id, $geotype, null),
				'Income',
				array(
					strtoupper($geotype) => 'string',
					'YEAR' => 'string',
					'ORDINAL' => 'integer',
					'INCOME GROUP' => 'string',
					'HOUSEHOLDS' => 'integer'
					)
				);
			
			if("forecast"==$datasource)
			{
				$jobsSql = "SELECT geozone as {$geotype}, yr, employment_type, jobs FROM fact.summary_jobs 
                        WHERE datasource_id = :datasource_id AND geotype = :geotype and employment_type <> 'Total Jobs' order by geozone, yr;";
				$writer->writeSheet(
					Query::getInstance()->getResultAsArray($jobsSql, $datasource_id, $geotype, null),
					'Civilian Jobs',
					array(
						strtoupper($geotype) => 'string',
						'YEAR' => 'string',
						'EMPLOYMENT TYPE (Civilian)' => 'string',
						'JOBS' => 'integer'
						)
					);
			}
			
			$writer->writeToStdOut();

		})->conditions(array('datasource'=>'census' , 'year' => '2000'));
		    

$app->get('/:datasource/:year/:geotype/:zones+/export/xlsx', function ($datasource, $year, $geotype, $zones) use ($app)
	{
		if (count($zones) > 20)
		{
			$app->halt(400, 'Max Zone Request Exceeded (Limit: 20)');
		}
		natcasesort($zones);
		
		$zonelist = '{'.strtolower(implode(',', $zones)).'}';
		
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
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
		//})->conditions(array('datasource' => 'forecast|census|estimate'));
		})->conditions(array('datasource' => 'forecast|estimate'));


//$app->get('/census/:year/:geotype/:zones+/export/xlsx', function ($datasource, $year, $geotype, $zones) use ($app)
$app->get('/census/:year/:geotype/:zones+/export/xlsx', function ( $year, $geotype, $zones) use ($app)
	{
		if (count($zones) > 20)
		{
			$app->halt(400, 'Max Zone Request Exceeded (Limit: 20)');
		}
		natcasesort($zones);
		
		$zonelist = '{'.strtolower(implode(',', $zones)).'}';
		$datasource = "census";
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
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
		})->conditions(array( 'year' => '2000' ));

//Forecast - Ethnicity Change
$app->get('/forecast/:series/:geotype/:zone/ethnicity/change', function ($series, $geotype, $zone) use ($app)
{
	$datasource_id = Query::getInstance()->getDatasourceId('forecast', $series);
    
    if (!$datasource_id)
        $app->halt(400, 'Invalid year or series id');
	
    $sql = "SELECT geozone as ${geotype}, ethnicity, pct_chg_byear_to_2020, pct_chg_2020_to_2025, pct_chg_2025_to_2030, 
    		pct_chg_2030_to_2035, pct_chg_2035_to_2040, pct_chg_2040_to_2045, pct_chg_2045_to_2050, 
    		pct_chg_base_to_horizon FROM fact.summary_ethnicity_change
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;

})->conditions(array('series' => '(\d){2}'));

$app->get('/forecast/:series/:geotype/:zone/jobs', function ($series, $geotype, $zone) use ($app)
{
    $datasource_id = Query::getInstance()->getDatasourceId('forecast', $series);
    
    if (!$datasource_id)
        $app->halt(400, 'Invalid year or series id');

    $sql = "SELECT geozone as {$geotype}, yr as year, employment_type as category, jobs FROM fact.summary_jobs 
            WHERE datasource_id = $1 AND geotype = $2 AND lower(geozone) = lower($3);";	

    $json = Query::getInstance()->getResultAsJson($sql, $datasource_id, $geotype, $zone); 

    echo $json;
	
})->conditions(array('series' => '(\d){2}'));

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

//Census - Means of Transportation to Work
$app->get('/:datasource/:year/:geotype/:zone/transportation', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
		$transportationtoWorkSql = " select  geography_zone {$geotype} ,{$year} Yearnumber,
					unnest(array[
						'Drive Alone',
						'Carpool',
						'Public Transportation' ,
						'Bicycle',
						'Walk',
						'Other means',
						'Work at home' ]) AS means_of_trans,	
					unnest(array[
					emp_mode_auto_drive_alone,
					emp_mode_auto_carpool,
					emp_mode_transit,
					emp_mode_bike,
					emp_mode_walk,
					emp_mode_others + emp_mode_motor,
					emp_mode_home
					]) as Number
					from app.acs_means_of_trans($1, $2, $3 );";
		if( $year == "2010"){
			$json = Query::getInstance()->getResultAsJson( $transportationtoWorkSql, 9, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}elseif( $year == "2000" ){
			$json = Query::getInstance()->getResultAsJson( $transportationtoWorkSql, 12, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}
		echo $json;		
		
	})->conditions(array('datasource' => 'census', 'year' => '(\d){2,4}'));
//Census - Employment Status
$app->get('/:datasource/:year/:geotype/:zone/employmentstatus', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
	
		$employmentStatusSql = "select '$zone' as {$geotype} ,{$year}  Yearnumber,
			unnest( array[ 'Population age 16 and older',
				
				'Armed forces',
				'Civilian employed',
				'Civilian unemployed',
				'Not in labor force'])
				as Status,			
			unnest(	array[ pop_16plus_male,
				
				in_armed_forces_male,
				emp_civ_male,
				unemployed_male,
				not_in_labor_force_male])	
				as male,
			unnest(	array[ pop_16plus_female,
			
				in_armed_forces_female,
				emp_civ_female,
				unemployed_female,
				not_in_labor_force_female])	
				as female
			from app.acs_employment_status_acs( $1, $2, $3);";
		
		if( $year == "2010"){
			$json = Query::getInstance()->getResultAsJson( $employmentStatusSql, 9, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}elseif( $year == "2000" ){
			$json = Query::getInstance()->getResultAsJson( $employmentStatusSql, 12, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}
		echo $json;
	})->conditions(array('datasource' => 'census', 'year' => '(\d){2,4}'));
	
//Census / Estimate - Poverty Status
$app->get('/:datasource/:year/:geotype/:zone/povertystatus', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		$povertyStatusSql = "select geography_zone  {$geotype}, {$year} Yearnumber,
				unnest(array[   'Total',
						'Above Poverty',
						'Below Poverty'
					 ]) AS Status,
				unnest(array[pop_poverty,
					pop_poverty_above,
					pop_poverty_below
					]) as Number			
				from app.acs_poverty_status($1, $2, $3 );";
	
		
		if( $year == "2010" ){
			$json = Query::getInstance()->getResultAsJson( $povertyStatusSql, 9, $geotype, mb_convert_case($zone, MB_CASE_TITLE, 'utf-8'));
		}elseif( $year == "2000" ){
			$json = Query::getInstance()->getResultAsJson( $povertyStatusSql, 12, $geotype, mb_convert_case($zone, MB_CASE_TITLE, 'utf-8'));
		}
		echo $json;		
	})->conditions(array('datasource' => 'census', 'year' => '(\d){2,4}'));
//Census / Estimate -Educational Attainment
$app->get('/:datasource/:year/:geotype/:zone/education', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		
		$educationalAttainmentSql = " select  geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array[ 'Total Population age 25 and older',
				'Less than high school',
				'High School grad including equivalency',
				'Some college or Associate degree',
				'Bachelors degree' ,
				'Graduate or Professional degree'
				 ]) AS Level,	
			unnest(array[pop_25plus_edu,
				pop_25plus_edu_lt9 + pop_25plus_edu_9to12_no_degree,
				pop_25plus_edu_high_school,
				pop_25plus_edu_col_no_degree + pop_25plus_edu_associate,
				pop_25plus_edu_bachelor,
				pop_25plus_edu_master + pop_25plus_edu_prof + pop_25plus_edu_doctorate	
			]) as population
			from app.acs_pop_by_educational_attainment( $1, $2, $3 );";
		
		if( $year == "2010" ){
			$json = Query::getInstance()->getResultAsJson( $educationalAttainmentSql, 9, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}elseif( $year == "2000" ){
			$json = Query::getInstance()->getResultAsJson( $educationalAttainmentSql, 12, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}
		
		echo $json;		
	})->conditions(array('datasource' => 'census', 'year' => '(\d){2,4}'));

//Census / Estimate -Language Spoken
$app->get('/:datasource/:year/:geotype/:zone/language', function ($datasource, $year, $geotype, $zone) use ($app)
	{
		$datasource_id = Query::getInstance()->getDatasourceId($datasource, $year);
		
		if (!$datasource_id)
		$app->halt(400, 'Invalid year or series id');
		$languageSpokenSql = "select geography_zone  {$geotype} , {$year} Yearnumber,
			unnest(array['Total age 5 and older' , 
				'Speaks only English',
				'Speaks Spanish' , 
				'Speaks Spanish & English well' ,
				'Speaks Spanish & limited English' , 
				'Speaks Asian/Pac. Island' , 
				'Speaks Asian/Pac. Island & English well' , 
				'Speaks Asian/Pac. Island & limited English' , 
				'Speaks Other language' , 
				'Speaks Other language & English well' , 
				'Speaks Other language & limited English']) AS population,
			unnest(array[pop_5plus , pop_5plus_english , 
				pop_5plus_spanish , pop_5plus_spanish_english , 
				pop_5plus_spanish_no_english , pop_5plus_asian , 
				pop_5plus_asian_english , 
				pop_5plus_asian_no_english , 
				pop_5plus_other , 
				pop_5plus_other_english , 
				pop_5plus_other_no_english] ) as total
			from app.acs_pop_by_language_spoken( $1, $2, $3);";
		
		if( $year == "2010"){
			$json = Query::getInstance()->getResultAsJson( $languageSpokenSql, 9, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		
		}elseif( $year == "2000" ){
			$json = Query::getInstance()->getResultAsJson( $languageSpokenSql, 12, $geotype, str_replace( "32Nd", "32nd", mb_convert_case($zone, MB_CASE_TITLE, 'utf-8')));
		}
		echo $json;
	})->conditions(array('datasource' => 'census', 'year' => '(\d){2,4}'));
$app->get('/census/:year/:geotype/:zones+/export/xlsx', function ( $year, $geoType, $zones) use ($app)
	{
		
		if (count($zones) > 20)
		{
			$app->halt(400, 'Max Zone Request Exceeded (Limit: 20)');
		}
		natcasesort($zones);
		
		$select_all_geozone = false;
		
		foreach( $zones as $key => $zone){
			$zones[$key] =  $zone;
			if( $zone == "all")
				$select_all_geozone = true;
		}
		
		$geozonesql = "SELECT DISTINCT geozone FROM dim.mgra WHERE series = $1 AND  geotype = $2 GROUP BY geozone ORDER BY geozone;";
		
		$ts = round(microtime(true) * 1000);
		$ts = date("Y/m/d h:i");
		$file_name = strtolower(join("_", array("censusacs", $year, $geoType, $zones[0]))."_{$ts}.xlsx");
		$file_path = join(DIRECTORY_SEPARATOR, array(".","xlsx","censusacs",$year,$geoType, $file_name));
		
		$res = $app->response();
		$res['Content-Description'] = 'File Transfer';
		$res['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$res['Content-Disposition'] ='attachment; filename='.$file_name;
		$res['Content-Transfer-Encoding'] = 'binary';
		$res['Expires'] = '0';
		$res['Cache-Control'] = 'must-revalidate';
		$res['Pragma'] = 'public';
		
		
		//******* 1 Race-Ethnicity **************
		$raceEthnicityArray[0] = [ ucwords($geoType), 'YEAR', 'RACE', 'NUMBER'];
		//******* 2. Age and Sex **************
		$ageSexArray[0] = [ ucwords($geoType), 'YEAR', 'AGE GROUP-5 YEARS', 'TOTAL', 'MALE', 'FEMALE'];
		//******* 3 MaritalStatus **************
		$maritalStatusArray[0] = [ ucwords($geoType), 'YEAR', 'STATUS', 'NUMBER'];
		//******* 4 HouseholdGroupQuarters **************
		$householdGroupQtrsArray[0] = [ ucwords($geoType), 'YEAR', 'STATUS', 'NUMBER'];
		//******* 5 Age, Race-Ethnicity **************
		$ethnicityAgeGroupArray[0] = [ ucwords($geoType), 'YEAR', 'AGE', 'HISPANIC', 'WHITE', 'BLACK', 'AMERICAN INDIAN', 'ASIAN', 'PACIFIC ISLANDER', 'OTHER', 'TWO OR MORE'];
		//******* 6 LanguageAt Home **************
		$languageSpokenArray[0] = [ ucwords($geoType), 'YEAR', 'POPULATION', 'TOTAL'];
		//******* 7 Educational Attainment **************
		$educationalAttainmentArray[0] = [ ucwords($geoType), 'YEAR', 'LEVEL', 'TOTAL'];
		//******* 8 SchoolEnrollment **************
		$schoolEnrollmentArray[0] = [ ucwords($geoType), 'YEAR', 'DESCRIPTION', 'TOTAL', 'PUBLIC', 'PRIVATE'];
		
		//******* 10 Household TypePresenceUnder 18 **************
		$householdsTypeByUnder18Array[0] = [ ucwords($geoType), 'YEAR', 'FAMILY TYPE' , 'TOTAL' , 'WITH PERSONS U18', 'WITHOUT PERSONS U18'];
		//******* 11 HousingUnitsType **************
		$housingUnitsTypeArray[0] = [ ucwords($geoType), 'YEAR', 'UNIT TYPE', 'UNITS',	'OCCUPIED',	'PERCENTAGE'];
		//******* 12 House Value **************
		$housingValueArray[0] = [ ucwords($geoType), 'YEAR', 'UNITS', 'NUMBER'];
		//******* 13 Yr House Built **************
		$yrHouseBuiltArray[0] = [ ucwords($geoType), 'YEAR', 'BUILT YR', 'NUMBER'];
		//******* 14 House Tenure and OccupRoom **************
		$houseTenureOccupiedArray[0] = [ ucwords($geoType), 'YEAR', 'OCCUPANT PER ROOM', 'TOTAL', 'RENTER OCCUPIED', 'OWNER OCCUPIED'];
		//******* 15 ContractRent **************
		$contractRentArray[0] = [ ucwords($geoType), 'YEAR', 'COST', 'NUMBER'];
		//******* 16 GrossRent Household Income **************
		$grossRentHouseholdIncomeArray[0] = [ ucwords($geoType), 'YEAR', 'PERCENT', 'NUMBER'];
		//******* 17 Vehicle Availability **************
		$vehicleAvailabilityArray[0] = [ ucwords($geoType), 'YEAR', 'AVAILABILITY', 'NUMBER'];
		//******* 18 Place of work( CountyLevel) **************
		$placeofWorkArray[0] = [ ucwords($geoType), 'YEAR', 'PLACE OF WORK', 'NUMBER'];
		//******* 19 Transportation to Work **************
		$transportationtoWorkArray[0] = [ ucwords($geoType), 'YEAR', 'MEANS OF TRANSPORTATION', 'NUMBER'];
		//******* 20 Travel Time to Work **************
		$travelTimetoWorkArray[0] = [ ucwords($geoType), 'YEAR', 'TRAVEL TIME TO WORK', 'NUMBER'];
		//******* 21 Employment Status **************
		$employmentStatusArray[0] = [ ucwords($geoType), 'YEAR', 'STATUS', 'MALE', 'FEMALE' ];
		//******* 22 Occupation **************
		$occupationArray[0] = [ ucwords($geoType), 'YEAR', 'OCCUPATION', 'NUMBER'];
		//******* 23 Industry **************
		$industryArray[0] = [ ucwords($geoType), 'YEAR', 'INDUSTRY', 'NUMBER'];
		//******* 24 Household Income **************
		$householdIncomeArray[0] = [ ucwords($geoType), 'YEAR', 'INCOME', 'NUMBER'];
		//******* 25 Earnings and Income **************
		$earningsAndIncomeArray[0] = [ ucwords($geoType), 'YEAR', 'HOUSEHOLDS', 'NUMBER'];
		//******* 26 Ratio Income to Poverty Level **************
		$ratioIncomePovertyLevelArray[0] = [ ucwords($geoType), 'YEAR', 'RATIO', 'POPULATION'];
		//******* 27 Poverty Status **************
		$povertyStatusArray[0] = [ ucwords($geoType), 'YEAR', 'STATUS', 'NUMBER'];
		//******* 28 PovertyStatus- FamType Children **************
		$povertyStatusByFamilyTypeArray[0] = 
		[ ucwords($geoType), 'YEAR', 'FAMILY TYPE', 'ABOVE POVERTY With Children Under 18', 
				'ABOVE POVERTY With No Child Under 18', 'BELOW POVERTY With Children Under 18','BELOW POVERTY With No Child Under 18'];
			
			
			$raceEthnicityIterator = 1;
			$ageSexIterator = 1;
			$maritalStatusIterator = 1;
			$householdGroupQtrsIterator = 1;
			$ethnicityAgeGroupIterator = 1;
			$languageSpokenIterator = 1;
			$educationalAttainmentIterator = 1;
			$schoolEnrollmentIterator = 1;
			$householdsTypeByUnder18Iterator = 1;
			$housingUnitsTypeIterator = 1;
			$housingValueIterator = 1;
			$yrHouseBuiltIterator = 1;
			$houseTenureOccupiedIterator = 1;
			$contractRentIterator = 1;
			$grossRentHouseholdIncomeIterator = 1;
			$vehicleAvailabilityIterator = 1;
			$placeofWorkIterator = 1;
			$transportationtoWorkIterator = 1;
			$travelTimetoWorkIterator = 1;
			$employmentStatusIterator = 1;
			$occupationIterator = 1;
			$industryIterator = 1;
			$householdIncomeIterator = 1;
			$earningsAndIncomeIterator = 1;
			$ratioIncomePovertyLevelIterator = 1;
			$povertyStatusIterator = 1;
			$povertyStatusByFamilyTypeIterator = 1;
			
			foreach($zones as $zone)
			{
				
				//******* 1 Race-Ethnicity***************************************
				$raceEthnicity_file_name = strtolower(join("_", array('raceEthnicity', "censusacs", $year, $geoType, $zone)).".json");
				$raceEthnicity_file_path = join(DIRECTORY_SEPARATOR, array(".","json","censusacs",$year,$geoType, $raceEthnicity_file_name));			
				$raceEthnicityZoneArray = json_decode(file_get_contents($raceEthnicity_file_path), true);
				
				if (is_array($raceEthnicityZoneArray)) {	
					
					foreach($raceEthnicityZoneArray as $arr){
						$raceEthnicityArray[$raceEthnicityIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['ethnicity_short_name'], $arr['population']];
					}
				}

				//******* 2. Age and Sex***************************************
				$ageSex_file_name = strtolower(join("_", array('ageSex', "censusacs", $year, $geoType, $zone)).".json");
				$ageSex_file_path = join(DIRECTORY_SEPARATOR, array(".","json","censusacs",$year,$geoType, $ageSex_file_name));			
				$ageSexZoneArray = json_decode(file_get_contents($ageSex_file_path), true);
				
				if (is_array($ageSexZoneArray)) {	
					
					foreach($ageSexZoneArray as $arr)
						$ageSexArray[$ageSexIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['age_group_name'], $arr['total'], $arr['male'], $arr['female']];
				}
				// add median age to ageSexArray
				$ageSexMedian_file_name = strtolower(join("_", array('ageSexMedian', "censusacs", $year, $geoType, $zone)).".json");
				$ageSexMedian_file_path = join(DIRECTORY_SEPARATOR, array(".","json","censusacs",$year,$geoType, $ageSexMedian_file_name));	
				$ageSexMedianZoneArray = json_decode(file_get_contents($ageSexMedian_file_path), true);
				
				if (is_array($ageSexMedianZoneArray)) {	
					
					foreach($ageSexMedianZoneArray as $arr)
						$ageSexArray[$ageSexIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['medianage'], $arr['median_age'], $arr['median_age_male'], $arr['median_age_female']];
				}
				//
				//******* 3.  MARITAL STATUS***************************************
				$maritalStatus_file_name = strtolower(join("_", array('maritalStatus', "censusacs", $year, $geoType, $zone)).".json");
				$maritalStatus_file_path = join(DIRECTORY_SEPARATOR, array(".","json","censusacs",$year,$geoType, $maritalStatus_file_name));
				$maritalStatusZoneArray = json_decode(file_get_contents($maritalStatus_file_path), true);
				if (is_array($maritalStatusZoneArray)) {	
					
					foreach($maritalStatusZoneArray as $arr)
						$maritalStatusArray[$maritalStatusIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['maritalstatus'], $arr['number']];
				}
				//******* 4 HouseholdGroupQuarters***************************************
				$householdGroupQtrs_file_name = strtolower(join("_", array('householdGroupQtrs', "censusacs", $year, $geoType, $zone)).".json");
				$householdGroupQtrs_file_path = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType, $householdGroupQtrs_file_name));
				$householdGroupQtrsZoneArray = json_decode(file_get_contents($householdGroupQtrs_file_path), true);
				if (is_array($householdGroupQtrsZoneArray)) {	
					
					foreach($householdGroupQtrsZoneArray as $arr)
						$householdGroupQtrsArray[$householdGroupQtrsIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['housing_type'], $arr['population']];
				}		
				//******* 5. Age, Race-Ethnicity***************************************
				$ethnicityAgeGroup_file_name = strtolower(join("_", array('ethnicityAgeGroup', "censusacs", $year, $geoType, $zone)).".json");
				$ethnicityAgeGroup_file_path = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType, $ethnicityAgeGroup_file_name));
				$ethnicityAgeGroupZoneArray = json_decode(file_get_contents($ethnicityAgeGroup_file_path), true);
				if (is_array($ethnicityAgeGroupZoneArray)) {	
					
					foreach($ethnicityAgeGroupZoneArray as $arr)
						$ethnicityAgeGroupArray[$ethnicityAgeGroupIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['age_group_name'], $arr['Hispanic'], $arr['White'], $arr['Black'], $arr['American Indian']	, $arr['Asian'], $arr['Pacific Islander'], $arr['Other'], $arr['Two or More']]; 
					
				}		
				//median age
				$ethnicityAgeGroupMedian_file_name = strtolower(join("_", array('ethnicityAgeGroupMedian', "censusacs", $year, $geoType, $zone)).".json");
				$ethnicityAgeGroupMedian_file_path = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType, $ethnicityAgeGroupMedian_file_name));
				$ethnicityAgeGroupMedianZoneArray = json_decode(file_get_contents($ethnicityAgeGroupMedian_file_path), true);
				if (is_array($ethnicityAgeGroupMedianZoneArray)) {	
					
					foreach($ethnicityAgeGroupMedianZoneArray as $arr)
						$ethnicityAgeGroupArray[$ethnicityAgeGroupIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['medianage'], $arr['Hispanic'], $arr['White'], $arr['Black'], $arr['American Indian'], $arr['Asian'], $arr['Pacific Islander'], $arr['Other'], $arr['Two or More']]; 			
				}		
				
				//******* 6. LANGUAGE SPOKEN AT HOME***************************************	
				$languageSpoken_file_name = strtolower(join("_", array('languageSpoken', "censusacs", $year, $geoType, $zone)).".json");
				$languageSpoken_file_path = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType, $languageSpoken_file_name));
				$languageSpokenZoneArray = json_decode(file_get_contents($languageSpoken_file_path), true);
				if (is_array($languageSpokenZoneArray)) {	
					
					foreach($languageSpokenZoneArray as $arr)
						$languageSpokenArray[$languageSpokenIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['population'], $arr['total']];
				}
				//*******7. EDUCATIONAL ATTAINMENT***************************************
				$educationalAttainment_file_name = strtolower(join("_", array('educationalAttainment', "censusacs", $year, $geoType, $zone)).".json");
				$educationalAttainment_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$educationalAttainment_file_path = join(DIRECTORY_SEPARATOR, array($educationalAttainment_file_dir, $educationalAttainment_file_name));
				$educationalAttainmentZoneArray = json_decode(file_get_contents($educationalAttainment_file_path), true);
				if (is_array($educationalAttainmentZoneArray)) {	
					
					foreach($educationalAttainmentZoneArray as $arr)
						$educationalAttainmentArray[$educationalAttainmentIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['level'], $arr['population']];
				}
				//*******8.  SchoolEnrollment***************************************
				$schoolEnrollment_file_name = strtolower(join("_", array('schoolEnrollment', "censusacs", $year, $geoType, $zone)).".json");
				$schoolEnrollment_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$schoolEnrollment_file_path = join(DIRECTORY_SEPARATOR, array($schoolEnrollment_file_dir, $schoolEnrollment_file_name));
				$schoolEnrollmentZoneArray = json_decode(file_get_contents($schoolEnrollment_file_path), true);
				if (is_array($schoolEnrollmentZoneArray)) {	
					
					foreach($schoolEnrollmentZoneArray as $arr)
						$schoolEnrollmentArray[$schoolEnrollmentIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['description'], $arr['total'], $arr['public'], $arr['private']];
				}
				//******* 10 Households by Type and Presence of Children Under 18************	
				$householdsTypeByUnder18_file_name = strtolower(join("_", array('householdsTypeByUnder18', "censusacs", $year, $geoType, $zone)).".json");
				$householdsTypeByUnder18_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$householdsTypeByUnder18_file_path = join(DIRECTORY_SEPARATOR, array($householdsTypeByUnder18_file_dir, $householdsTypeByUnder18_file_name));
				$householdsTypeByUnder18ZoneArray = json_decode(file_get_contents($householdsTypeByUnder18_file_path), true);	
				if (is_array($householdsTypeByUnder18ZoneArray)) {	
					
					foreach($householdsTypeByUnder18ZoneArray as $arr)
						$householdsTypeByUnder18Array[$householdsTypeByUnder18Iterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['familytype'], $arr['total'], $arr['withpersonsunder18'], $arr['withoutpersonsunder18']];
				}	
				//******* 11. HousingUnitsType***************************************	
				$housingUnitsType_file_name = strtolower(join("_", array('housingUnitsType', "censusacs", $year, $geoType, $zone)).".json");
				$housingUnitsType_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$housingUnitsType_file_path = join(DIRECTORY_SEPARATOR, array($housingUnitsType_file_dir, $housingUnitsType_file_name));
				$housingUnitsTypeZoneArray = json_decode(file_get_contents($housingUnitsType_file_path), true);	
				if (is_array($housingUnitsTypeZoneArray)) {	
					
					foreach($housingUnitsTypeZoneArray as $arr)
						$housingUnitsTypeArray[$housingUnitsTypeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['structure_type'], $arr['units'], $arr['occupied'], $arr['percent_of_units']];
				}
				//******* 12.  HOUSING VALUE***************************************
				$housingValue_file_name = strtolower(join("_", array('housingValue', "censusacs", $year, $geoType, $zone)).".json");
				$housingValue_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$housingValue_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValue_file_name));
				$housingValueZoneArray = json_decode(file_get_contents($housingValue_file_path), true);	
				if (is_array($housingValueZoneArray)) {	
					
					foreach($housingValueZoneArray as $arr)
						$housingValueArray[$housingValueIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['description'], $arr['total']];
				}
				// need median value here
				$housingValueMedian_file_name = strtolower(join("_", array('housingValueMedian', "censusacs", $year, $geoType, $zone)).".json");
				$housingValueMedian_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$housingValueMedian_file_path = join(DIRECTORY_SEPARATOR, array($housingValueMedian_file_dir, $housingValueMedian_file_name));
				$housingValueMedianZoneArray = json_decode(file_get_contents($housingValueMedian_file_path), true);	
				if (is_array($housingValueMedianZoneArray)) {	
					
					foreach($housingValueMedianZoneArray as $arr)
						$housingValueArray[$housingValueIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['medianvalue'], $arr['median_housing_value']];
				}
				//
				//******* 13. Yr House Built***************************************			
				$yrHouseBuilt_file_name = strtolower(join("_", array('yrHouseBuilt', "censusacs", $year, $geoType, $zone)).".json");
				$yrHouseBuilt_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$yrHouseBuilt_file_path = join(DIRECTORY_SEPARATOR, array($yrHouseBuilt_file_dir, $yrHouseBuilt_file_name));
				$yrHouseBuiltZoneArray = json_decode(file_get_contents($yrHouseBuilt_file_path), true);	
				if (is_array($yrHouseBuiltZoneArray)) {	
					
					foreach($yrHouseBuiltZoneArray as $arr)
						$yrHouseBuiltArray[$yrHouseBuiltIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['yearbuilt'], $arr['builtnumber']];
				}
				//******* 14. House Tenure and OccupRoom ***************************************
				$houseTenureOccupied_file_name = strtolower(join("_", array('houseTenureOccupied', "censusacs", $year, $geoType, $zone)).".json");
				$houseTenureOccupied_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$houseTenureOccupied_file_path = join(DIRECTORY_SEPARATOR, array($houseTenureOccupied_file_dir, $houseTenureOccupied_file_name));
				$houseTenureOccupiedZoneArray = json_decode(file_get_contents($houseTenureOccupied_file_path), true);	
				if (is_array($houseTenureOccupiedZoneArray)) {	
					
					foreach($houseTenureOccupiedZoneArray as $arr)
						$houseTenureOccupiedArray[$houseTenureOccupiedIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['occupants'], $arr['total'], $arr['renter'], $arr['owner']];
				}
				//******* 15. ContractRent***************************************	
				$contractRent_file_name = strtolower(join("_", array('contractRent', "censusacs", $year, $geoType, $zone)).".json");
				$contractRent_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$contractRent_file_path = join(DIRECTORY_SEPARATOR, array($contractRent_file_dir, $contractRent_file_name));
				$contractRentZoneArray = json_decode(file_get_contents($contractRent_file_path), true);	
				if (is_array($contractRentZoneArray)) {	
					
					foreach($contractRentZoneArray as $arr)
						$contractRentArray[$contractRentIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['cost'], $arr['number']];
				}
				// contract rent median
				$contractRentMedian_file_name = strtolower(join("_", array('contractRentMedian', "censusacs", $year, $geoType, $zone)).".json");
				$contractRentMedian_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$contractRentMedian_file_path = join(DIRECTORY_SEPARATOR, array($contractRent_file_dir, $contractRentMedian_file_name));
				$contractRentMedianZoneArray = json_decode(file_get_contents($contractRentMedian_file_path), true);	
				if (is_array($contractRentMedianZoneArray)) {	
					
					foreach($contractRentMedianZoneArray as $arr)
						$contractRentArray[$contractRentIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['mediancontractrent'], $arr['median_contract_rent']];
				}
				//******* 16. Gross Rent Household Income***************************************
				$grossRentHouseholdIncome_file_name = strtolower(join("_", array('grossRentHouseholdIncome', "censusacs", $year, $geoType, $zone)).".json");
				$grossRentHouseholdIncome_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$grossRentHouseholdIncome_file_path = join(DIRECTORY_SEPARATOR, array($grossRentHouseholdIncome_file_dir, $grossRentHouseholdIncome_file_name));
				$grossRentHouseholdIncomeZoneArray = json_decode(file_get_contents($grossRentHouseholdIncome_file_path), true);	
				if (is_array($grossRentHouseholdIncomeZoneArray)) {	
					
					foreach($grossRentHouseholdIncomeZoneArray as $arr)
						$grossRentHouseholdIncomeArray[$grossRentHouseholdIncomeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['percentage'], $arr['number']];
				}
				//******* 17. Vehicle Availability***************************************
				$vehicleAvailability_file_name = strtolower(join("_", array('vehicleAvailability', "censusacs", $year, $geoType, $zone)).".json");
				$vehicleAvailability_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$vehicleAvailability_file_path = join(DIRECTORY_SEPARATOR, array($vehicleAvailability_file_dir, $vehicleAvailability_file_name));
				$vehicleAvailabilityZoneArray = json_decode(file_get_contents($vehicleAvailability_file_path), true);	
				if (is_array($vehicleAvailabilityZoneArray)) {	
					
					foreach($vehicleAvailabilityZoneArray as $arr)
						$vehicleAvailabilityArray[$vehicleAvailabilityIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['availability'], $arr['number']];
				}
				//******* 18 Place of work( CountyLevel) ***************************************
				$placeofWork_file_name = strtolower(join("_", array('placeofWork', "censusacs", $year, $geoType, $zone)).".json");
				$placeofWork_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$placeofWork_file_path = join(DIRECTORY_SEPARATOR, array($placeofWork_file_dir, $placeofWork_file_name));
				$placeofWorkZoneArray = json_decode(file_get_contents($placeofWork_file_path), true);	
				if (is_array($placeofWorkZoneArray)) {	
					
					foreach($placeofWorkZoneArray as $arr)
						$placeofWorkArray[$placeofWorkIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['placeofwork'], $arr['number']];
				}
				//******* 19 Transportation to Work ***************************************
				$transportationtoWork_file_name = strtolower(join("_", array('transportationtoWork', "censusacs", $year, $geoType, $zone)).".json");
				$transportationtoWork_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$transportationtoWork_file_path = join(DIRECTORY_SEPARATOR, array($transportationtoWork_file_dir, $transportationtoWork_file_name));
				$transportationtoWorkZoneArray = json_decode(file_get_contents($transportationtoWork_file_path), true);	
				if (is_array($transportationtoWorkZoneArray)){
					
					foreach($transportationtoWorkZoneArray as $arr)
						$transportationtoWorkArray[$transportationtoWorkIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['means_of_trans'], $arr['number']];
				}
				//******* 20 Travel Time to Work ***************************************
				$travelTimetoWork_file_name = strtolower(join("_", array('travelTimetoWork', "censusacs", $year, $geoType, $zone)).".json");
				$travelTimetoWork_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$travelTimetoWork_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWork_file_dir, $travelTimetoWork_file_name));
				$travelTimetoWorkZoneArray = json_decode(file_get_contents($travelTimetoWork_file_path), true);	
				if (is_array($travelTimetoWorkZoneArray)){
					
					foreach($travelTimetoWorkZoneArray as $arr)
						$travelTimetoWorkArray[$travelTimetoWorkIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['traveltime'], $arr['number']];
				}
				// average time to work
				$travelTimetoWorkAverage_file_name = strtolower(join("_", array('travelTimetoWorkAverage', "censusacs", $year, $geoType, $zone)).".json");
				$travelTimetoWorkAverage_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$travelTimetoWorkAverage_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWorkAverage_file_dir, $travelTimetoWorkAverage_file_name));
				$travelTimetoWorkAverageZoneArray = json_decode(file_get_contents($travelTimetoWorkAverage_file_path), true);	
				if (is_array($travelTimetoWorkAverageZoneArray)){
					
					foreach($travelTimetoWorkAverageZoneArray as $arr)
						$travelTimetoWorkArray[$travelTimetoWorkIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['averagetimetowork'], $arr['acs_avg_travel_time_to_work']];
				}
				//******* 21 Employment Status ***************************************
				$employmentStatus_file_name = strtolower(join("_", array('employmentStatus', "censusacs", $year, $geoType, $zone)).".json");
				$employmentStatus_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$employmentStatus_file_path = join(DIRECTORY_SEPARATOR, array($employmentStatus_file_dir, $employmentStatus_file_name));
				$employmentStatusZoneArray = json_decode(file_get_contents($employmentStatus_file_path), true);	
				if (is_array($employmentStatusZoneArray)){
					
					foreach($employmentStatusZoneArray as $arr)
						$employmentStatusArray[$employmentStatusIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['status'], $arr['male'], $arr['female']];
				}
				//******* 22 Occupation ***************************************
				$occupation_file_name = strtolower(join("_", array('occupation', "censusacs", $year, $geoType, $zone)).".json");
				$occupation_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$occupation_file_path = join(DIRECTORY_SEPARATOR, array($occupation_file_dir, $occupation_file_name));
				$occupationZoneArray = json_decode(file_get_contents($occupation_file_path), true);	
				if (is_array($occupationZoneArray)){
					
					foreach($occupationZoneArray as $arr)
						$occupationArray[$occupationIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['occupation'], $arr['total']];
				}
				//******* 23 Industry ***************************************
				$industry_file_name = strtolower(join("_", array('industry', "censusacs", $year, $geoType, $zone)).".json");
				$industry_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$industry_file_path = join(DIRECTORY_SEPARATOR, array($industry_file_dir, $industry_file_name));
				$industryZoneArray = json_decode(file_get_contents($industry_file_path), true);	
				if (is_array($industryZoneArray)){
					
					foreach($industryZoneArray as $arr)
						$industryArray[$industryIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['industry'], $arr['number']];
				}
				//******* 24 Household Income ***************************************
				$householdIncome_file_name = strtolower(join("_", array('householdIncome', "censusacs", $year, $geoType, $zone)).".json");
				$householdIncome_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$householdIncome_file_path = join(DIRECTORY_SEPARATOR, array($householdIncome_file_dir, $householdIncome_file_name));
				$householdIncomeZoneArray = json_decode(file_get_contents($householdIncome_file_path), true);	
				if (is_array($householdIncomeZoneArray)){
					
					foreach($householdIncomeZoneArray as $arr)
						$householdIncomeArray[$householdIncomeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['income_group_name'], $arr['households']];
				}
				// median income
				$householdIncomeMedian_file_name = strtolower(join("_", array('householdIncomeMedian', "censusacs", $year, $geoType, $zone)).".json");
				$householdIncomeMedian_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$householdIncomeMedian_file_path = join(DIRECTORY_SEPARATOR, array($householdIncomeMedian_file_dir, $householdIncomeMedian_file_name));
				$householdIncomeMedianZoneArray = json_decode(file_get_contents($householdIncomeMedian_file_path), true);	
				if (is_array($householdIncomeMedianZoneArray)){
					
					foreach($householdIncomeMedianZoneArray as $arr)
						$householdIncomeArray[$householdIncomeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['medianincome'], $arr['median_inc']];
				}
				//******* 25 Earnings and Income ***************************************
				$earningsAndIncome_file_name = strtolower(join("_", array('earningsAndIncome', "censusacs", $year, $geoType, $zone)).".json");
				$earningsAndIncome_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$earningsAndIncome_file_path = join(DIRECTORY_SEPARATOR, array($earningsAndIncome_file_dir, $earningsAndIncome_file_name));
				$earningsAndIncomeZoneArray = json_decode(file_get_contents($earningsAndIncome_file_path), true);	
				if (is_array($earningsAndIncomeZoneArray)){
					
					foreach($earningsAndIncomeZoneArray as $arr)
						$earningsAndIncomeArray[$earningsAndIncomeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['households'], $arr['number']];
				}
				//******* 26 Ratio Income to Poverty Level ***************************************
				$ratioIncomePovertyLevel_file_name = strtolower(join("_", array('ratioIncomePovertyLevel', "censusacs", $year, $geoType, $zone)).".json");
				$ratioIncomePovertyLevel_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$ratioIncomePovertyLevel_file_path = join(DIRECTORY_SEPARATOR, array($ratioIncomePovertyLevel_file_dir, $ratioIncomePovertyLevel_file_name));
				$ratioIncomePovertyLevelZoneArray = json_decode(file_get_contents($ratioIncomePovertyLevel_file_path), true);	
				if (is_array($ratioIncomePovertyLevelZoneArray)){
					
					foreach($ratioIncomePovertyLevelZoneArray as $arr)
						$ratioIncomePovertyLevelArray[$ratioIncomePovertyLevelIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['households'], $arr['number']];
				}
				//******* 27 Poverty Status ***************************************
				$povertyStatus_file_name = strtolower(join("_", array('povertyStatus', "censusacs", $year, $geoType, $zone)).".json");
				$povertyStatus_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$povertyStatus_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatus_file_dir, $povertyStatus_file_name));
				$povertyStatusZoneArray = json_decode(file_get_contents($povertyStatus_file_path), true);	
				if (is_array($povertyStatusZoneArray)){
					
					foreach($povertyStatusZoneArray as $arr)
						$povertyStatusArray[$povertyStatusIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['status'], $arr['number']];
				}
				//******* 28 PovertyStatus- FamType Children ***************************************
				$povertyStatusByFamilyType_file_name = strtolower(join("_", array('povertyStatusByFamilyType', "censusacs", $year, $geoType, $zone)).".json");
				$povertyStatusByFamilyType_file_dir = join(DIRECTORY_SEPARATOR, array(".","json", "censusacs", $year, $geoType));
				$povertyStatusByFamilyType_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatusByFamilyType_file_dir, $povertyStatusByFamilyType_file_name));
				$povertyStatusByFamilyTypeZoneArray = json_decode(file_get_contents($povertyStatusByFamilyType_file_path), true);	
				if (is_array($povertyStatusByFamilyTypeZoneArray)){
					
					foreach($povertyStatusByFamilyTypeZoneArray as $arr)
						$povertyStatusByFamilyTypeArray[$povertyStatusByFamilyTypeIterator++] = [$arr[$geoType], $arr['yearnumber'], $arr['familytype'], $arr['abovepovertywithchildrenunder18'],$arr['abovepovertynochildunder18'],$arr['belowpovertywithchildrenunder18'],$arr['belowpovertynochildunder18']];
				}
				
			}
			
			$objPHPExcel = new XLSXWriter();
			$objPHPExcel->setAuthor("San Diego Association of Governments");
			//******* 1 Race-Ethnicity**************
			$objPHPExcel->writeSheet($raceEthnicityArray, 'Race-Ethnicity');
			//******* 2. Age and Sex**************
			$objPHPExcel->writeSheet($ageSexArray, 'Age and Sex');
			//******* 3. Marital Status**************
			$objPHPExcel->writeSheet($maritalStatusArray, 'Marital Status');
			//******* 4 HouseholdGroupQuarters**************	
			$objPHPExcel->writeSheet($householdGroupQtrsArray,'HouseholdGroupQuarters' );	
			//******* 5. Age, Race-Ethnicity**************	
			$objPHPExcel->writeSheet($ethnicityAgeGroupArray, 'Ethnicity by Age');
			//******* 6. LANGUAGE SPOKEN AT HOME**************
			$objPHPExcel->writeSheet($languageSpokenArray, 'Language Spoken at Home');
			//******* 7. EDUCATIONAL ATTAINMENT**************
			$objPHPExcel->writeSheet($educationalAttainmentArray, 'Educational Attainment');
			//******* 8. SchoolEnrollment**************
			$objPHPExcel->writeSheet($schoolEnrollmentArray, 'School Enrollment');
			//******* 9. Disability**************
			// will not be included
			//********10. Household TypePresenceUnder 18 **************
			$objPHPExcel->writeSheet($householdsTypeByUnder18Array, 'Households Type by Persons U18');
			//******* 11.  HousingUnitsType**************
			$objPHPExcel->writeSheet($housingUnitsTypeArray,'Housing Units Type');
			//******* 12.  HOUSING VALUE**************
			$objPHPExcel->writeSheet($housingValueArray, 'Housing Value');
			//******* 13. Yr House Built**************
			$objPHPExcel->writeSheet($yrHouseBuiltArray, 'Year Structure Built');		
			//******* 14. House Tenure and OccupRoom **************
			$objPHPExcel->writeSheet($houseTenureOccupiedArray,'House Tenure and OccupRoom');
			//******* 15. ContractRent  **************
			$objPHPExcel->writeSheet($contractRentArray, 'ContractRent' );
			//******* 16. Gross Rent Household Income **************
			$objPHPExcel->writeSheet($grossRentHouseholdIncomeArray, 'Gross Rent HouseholdIncome');
			//******* 17 Vehicle Availability **************
			$objPHPExcel->writeSheet($vehicleAvailabilityArray, 'Vehicle Availability');		
			//******* 18 Place of work( CountyLevel) **************
			$objPHPExcel->writeSheet($placeofWorkArray, 'Vehicle Availability');					
			//******* 19 Transportation to Work **************
			$objPHPExcel->writeSheet($transportationtoWorkArray,'Transportation to Work');
			//******* 20 Travel Time to Work **************
			$objPHPExcel->writeSheet($travelTimetoWorkArray, 'Travel Time to Work');
			//******* 21 Employment Status **************
			$objPHPExcel->writeSheet($employmentStatusArray, 'Employment Status');
			//******* 22 Occupation **************
			$objPHPExcel->writeSheet($occupationArray, 'Occupation' );
			//******* 23 Industry **************
			$objPHPExcel->writeSheet($industryArray, 'Industry' );
			//******* 24 Household Income **************
			$objPHPExcel->writeSheet($householdIncomeArray, 'Household Income' );
			//******* 25 Earnings and Income **************
			$objPHPExcel->writeSheet($earningsAndIncomeArray, 'Earnings and Income' );
			//******* 26 Ratio Income to Poverty Level **************
			$objPHPExcel->writeSheet($ratioIncomePovertyLevelArray, 'Ratio Income to Poverty Level');
			//******* 27 Poverty Status **************
			$objPHPExcel->writeSheet($povertyStatusArray, 'Poverty Status' );		
			//******* 28 PovertyStatus- FamType Children **************
			$objPHPExcel->writeSheet($povertyStatusByFamilyTypeArray, 'PovertyStatus - FamilyType');		

			
			$objPHPExcel->writeToStdOut();
			/*})->conditions(array( 'year' => '(\d){2,4}'));*/
		})->conditions(array( 'year' => '2010'));
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
