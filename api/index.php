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
require 'PHPExcel.php';

\Slim\Slim::registerAutoloader();

//define column mappings
$geotypes = [
		"jurisdiction" => "city_name",
		"region" => "region_name",
		"zip" => "zip",
		"msa" => "msa_name",
		"sra" => "sra_name",
		"ct2000" => "ct2000",
		"tract" => "tract",
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
				2011 => 3,
				2012 => 4,
				2013 => 10,
		],
		"census" =>
		[
		//        1990 =>
				2000 => 12,
				2010 => 5
		]
];

$yearCheck = function($request) 
{
// 	$year = $request["params"]["year"];
	
// 	if(!array_key_exists($year, $GLOBALS['datasources'][$datasource]))
// 	{
// 		$app->halt(400, 'Invalid year or series id');
// 	}
};

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
    $app->render('home.html');
});

$app->get('/:datasource', function ($datasource) use ($app)
{
	$labels = ["forecast" => "series", "census"=>"year", "estimate"=>"year"];
	$response = array();
	
	foreach($GLOBALS['datasources'][$datasource] as $key => $id)
		$response[] = [$labels[$datasource], $key];

	echo json_encode($response);
	
})->conditions(array('datasource' => 'census|forecast|estimate'));

$app->get('/:datasource/:year', $yearCheck, function ($datasource, $year) use ($app)
{
	$response = array();
	
	foreach($GLOBALS['geotypes'] as $key => $id)
		$response[] = ['zone', $key]; 

	echo json_encode($response);

})->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

//Get Information - Zone Names for Geotype
$app->get('/:datasource/:year/:geotype', function ($datasource, $series, $geoType)
{
	$columnName = $GLOBALS['geotypes'][$geoType];
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

	$sql = "select ".$columnName." as ".$geoType." from dim.mgra where series_id = ? group by ".$columnName;
	
	echo Query::getInstance()->getResultAsJson($sql, $params);
	
})->conditions(array('datasource' => 'census|forecast|estimate', 'year' => '(\d){2,4}'));

 //Census / Estimate - Housing
$app->get('/:datasource/:year/:geotype/:zone/housing', function ($datasource, $year, $geoType, $zone)
{
        $columnName = $GLOBALS['geotypes'][$geoType];
        $datasource_id = $GLOBALS['datasources'][$datasource][$year];
        $params = [$datasource_id, $zone];
    
        $sql = "SELECT s.long_name, SUM(units) as units, SUM(occupied) as occupied, SUM(units - occupied) as unoccupied".
        ",CASE WHEN SUM(units) = 0 THEN NULL ELSE SUM(units - occupied) / CAST(SUM(units) as float)	END as vacancy_rate ".
        "FROM fact.housing h JOIN dim.datasource d on h.datasource_id = d.datasource_id	JOIN dim.mgra m ON h.mgra_id = m.mgra_id ".
        "JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id ".
        "WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? GROUP BY s.long_name";
    
        echo Query::getInstance()->getResultAsJson($sql, $params);
})->conditions(array('datasource' => 'census|estimate'));

//Census / Estimate - Ethnicity
$app->get('/:datasource/:year/:geotype/:zone/ethnicity', function ($datasource, $year, $geoType, $zone)
{
    $columnName = $GLOBALS['geotypes'][$geoType];
    $datasource_id = $GLOBALS['datasources'][$datasource][$year];
    $params = [$datasource_id, $zone];
    
    $sql = "SELECT e.long_name, SUM(population) FROM fact.age_sex_ethnicity ase ".
        "JOIN dim.datasource d on ase.datasource_id = d.datasource_id ".
        "JOIN dim.mgra m ON ase.mgra_id = m.mgra_id ".
        "JOIN dim.ethnicity e ON ase.ethnicity_id = e.ethnicity_id ".
        "WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? ".
        "GROUP BY e.long_name";
    
    echo Query::getInstance()->getResultAsJson($sql, $params);
})->conditions(array('datasource' => 'census|estimate'));

//Census / Estimate - Age
$app->get('/:datasource/:year/:geotype/:zone/age', function ($datasource, $year, $geoType, $zone)
{
    $columnName = $GLOBALS['geotypes'][$geoType];
    $datasource_id = $GLOBALS['datasources'][$datasource][$year];
    $params = [$datasource_id, $zone];
    
    $sql = "SELECT s.sex, a.group_10yr, SUM(ase.population) as population ".
        "FROM fact.age_sex_ethnicity ase ".
        "JOIN dim.datasource d on ase.datasource_id = d.datasource_id ".
        "JOIN dim.mgra m on ase.mgra_id = m.mgra_id	".
        "JOIN dim.age_group a on ase.age_group_id = a.age_group_id ".
        "JOIN dim.sex s on ase.sex_id = s.sex_id ".
        "WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? ".
        "GROUP BY s.sex, a.group_10yr";
    
    echo Query::getInstance()->getResultAsJson($sql, $params);
})->conditions(array('datasource' => 'census|estimate'));

//Census / Estimate - Income
$app->get('/:datasource/:year/:geotype/:zone/income', function ($datasource, $year, $geoType, $zone) use ($app)
{
	if($datasource == 'census' && $year == 2000)
		$app->halt(501, 'Census 2000 data are not implemented yet!');
	
	$columnName = $GLOBALS['geotypes'][$geoType];
    $datasource_id = $GLOBALS['datasources'][$datasource][$year];
    $params = [$datasource_id, $zone];
	
	$sql = "SELECT g.name as income_group ,sum(i.households) as households ".
		"FROM fact.household_income i ".
		"JOIN dim.income_group g ON i.income_group_id = g.income_group_id ".
		"JOIN dim.datasource d ON i.datasource_id = d.datasource_id ".
		"JOIN dim.mgra m ON i.mgra_id = m.mgra_id ".
		"WHERE d.datasource_id = ? AND lower(m.".$columnName.") = ? ".
		"GROUP BY i.income_group_id, g.name ".
		"ORDER BY i.income_group_id";
	
	echo Query::getInstance()->getResultAsJson($sql, $params);
	
})->conditions(array('datasource' => 'census|estimate'));

//Forecast Export to Excel
$app->get('/:datasource/:year/:geotype/export/xlsx/:zones+', function ($datasource, $year, $geoType, $zones) use ($app)
{
	natcasesort($zones);
	$file_name = join("_", array($datasource, $year, $geoType)).join("_",$zones).".xlsx";
	$file_path = join(DIRECTORY_SEPARATOR, array(".","xlsx",$datasource,$year,$geoType, $file_name));

 	$res = $app->response();
 	$res['Content-Description'] = 'File Transfer';
 	$res['Content-Type'] = 'application/vnd.ms-excel';
 	$res['Content-Disposition'] ='attachment; filename='.$file_name;
 	$res['Content-Transfer-Encoding'] = 'binary';
 	$res['Expires'] = '0';
 	$res['Cache-Control'] = 'must-revalidate';
 	$res['Pragma'] = 'public';

	if (file_exists($file_path))
	{
		$res['Content-Length'] = filesize($file_path);
		readfile($file_path);
	} else
	{
		$param_holder = array_fill(0, count($zones), "?");
		$columnName = $GLOBALS['geotypes'][$geoType];
		$datasource_id = $GLOBALS['datasources'][$datasource][$year];

		$params = [$datasource_id];
		foreach ($zones as $zone)
			$params[] = $zone;

		$sql_parameter = "(".implode(",", array_fill(0, count($zones), "?")).")";

		$ageSql = "SELECT zone as ".$geoType.",year,ethnicity,sex,[Under 10],[10 to 19],[20 to 29],[30 to 39],[40 to 49],[50 to 59],[60 to 69],[70 to 79],[80+] ".
				"FROM ".
				"(SELECT m.".$columnName." as zone,ase.year,e.long_name as ethnicity,s.sex,a.group_10yr as age_group,sum(ase.population) as population ".
				"FROM fact.age_sex_ethnicity ase ".
				"JOIN dim.datasource d on ase.datasource_id = d.datasource_id ".
				"JOIN dim.mgra m on ase.mgra_id = m.mgra_id ".
				"JOIN dim.age_group a on ase.age_group_id = a.age_group_id ".
				"JOIN dim.ethnicity e on ase.ethnicity_id = e.ethnicity_id ".
				"JOIN dim.sex s on ase.sex_id = s.sex_id ".
				"WHERE d.datasource_id = ? and lower(m.".$columnName.") in ".$sql_parameter." ".
				"GROUP BY m.".$columnName.", ase.year, e.long_name, s.sex, a.group_10yr) p PIVOT ".
				"(SUM(population) for age_group in ([Under 10],[10 to 19],[20 to 29],[30 to 39],[40 to 49],[50 to 59],[60 to 69],[70 to 79],[80+])) as piv ORDER BY zone, year, ethnicity, sex";

		$housingSql = "SELECT m.".$columnName." as ".$geoType.", h.year, s.long_name as unit_type, SUM(units) as units, SUM(occupied) as occupied, SUM(units - occupied) as unoccupied, CASE WHEN SUM(units) = 0 THEN NULL ELSE SUM(units - occupied) / CAST(SUM(units) as float) END as vacancy_rate ".
				"FROM fact.housing h ".
				"JOIN dim.datasource d on h.datasource_id = d.datasource_id ".
				"JOIN dim.mgra m ON h.mgra_id = m.mgra_id ".
				"JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id ".
				"WHERE d.datasource_id = ? and lower(m.".$columnName.") in ".$sql_parameter." ".
				"GROUP BY m.".$columnName.", h.year, s.long_name ORDER BY m.".$columnName.", year, s.long_name";

		$populationSql = "SELECT zone as ".$geoType.",year,[Household],[Group Quarter - Other],[Group Quarters - Military] ".
				"FROM ".
				"(SELECT m.".$columnName." as zone,p.year,h.long_name as housing_type,sum(p.population) as population ".
				"FROM fact.population p	JOIN dim.mgra m on p.mgra_id = m.mgra_id JOIN dim.housing_type h on p.housing_type_id = h.housing_type_id ".
				"WHERE p.datasource_id = ? and lower(m.".$columnName.") in ".$sql_parameter." ".
				"GROUP BY m.".$columnName.", p.year, h.long_name) p PIVOT ".
				"(SUM(population) for housing_type in ([Household],[Group Quarter - Other], [Group Quarters - Military])) piv ".
				"ORDER BY zone, year";

		$jobsSql = "SELECT zone as ".$geoType.", year, [Military],[Agriculture and Mining],[Construction],[Manufacturing],[Wholesale Trade], ".
				"[Retail Trade],[Transportation, Warehousing, and Utilities],[Information Systems],[Finance and Real Estate], ".
				"[Professional and Business Services],[Education and Healthcare],[Liesure and Hospitality], ".
				"[Office Services],[Government],[Self-Employed] ".
				"FROM (select m.".$columnName." as zone,j.year,e.full_name,sum(j.jobs) as jobs ".
				"FROM fact.jobs j JOIN dim.mgra m ON j.mgra_id = m.mgra_id JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id ".
				"WHERE j.datasource_id = ?	AND lower(m.".$columnName.") IN ".$sql_parameter." ".
				"GROUP BY m.".$columnName.", j.year, e.full_name) p ".
				"PIVOT (SUM(jobs) FOR full_name in ([Military],[Agriculture and Mining],[Construction],[Manufacturing], ".
				"[Wholesale Trade],[Retail Trade],[Transportation, Warehousing, and Utilities],[Information Systems], ".
				"[Finance and Real Estate],[Professional and Business Services],[Education and Healthcare],[Liesure and Hospitality] ".
				",[Office Services],[Government],[Self-Employed])) as piv ORDER BY zone, year";

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("San Diego Association of Governments");
		if("census"==$datasource)
			$objPHPExcel->getProperties()->setTitle("SANDAG Census ".$year." Profile");
		elseif ("estimate" == $datasource)
		$objPHPExcel->getProperties()->setTitle("SANDAG ".$year." Estimate Profile");
		elseif("forecast"==$datasource)
			$objPHPExcel->getProperties()->setTitle("SANDAG Series ".$year." Forecast Profile");
		$objPHPExcel->getProperties()->setCompany("San Diego Association of Governments");

		$ageSheet = $objPHPExcel->getActiveSheet();
		$ageSheet->setTitle('Age_Ethnicity_Sex', true);
		Query::getInstance()->getResultAsSheet($ageSql, $params, $ageSheet);

		$housingSheet = $objPHPExcel->createSheet(NULL);
		$housingSheet->setTitle('Housing',true);
		Query::getInstance()->getResultAsSheet($housingSql, $params, $housingSheet);

		$populationSheet = $objPHPExcel->createSheet(NULL);
		$populationSheet->setTitle('Population',true);
		Query::getInstance()->getResultAsSheet($populationSql, $params, $populationSheet);

		if("forecast" == $datasource)
		{
			$jobsSheet = $objPHPExcel->createSheet(NULL);
			$jobsSheet->setTitle('Jobs',true);
			Query::getInstance()->getResultAsSheet($jobsSql, $params, $jobsSheet);
		}

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		$objWriter->save($file_path);
		$objWriter->save('php://output');
	}
})->conditions(array('datasource' => 'census|estimate|forecast'));

//Forecast - Housing
$app->get('/forecast/:series/:geotype/:zone/housing', function ($series, $geoType, $zone)
{
	$columnName = $GLOBALS['geotypes'][$geoType];
	$datasource_id = $GLOBALS['datasources']['forecast'][$series];
	$params = [$datasource_id, $zone];
	
	$sql = "SELECT s.long_name, h.year, SUM(units) as units, SUM(occupied) as occupied, SUM(units - occupied) as unoccupied ".
    	",CASE	WHEN SUM(units) = 0 THEN NULL ELSE SUM(units - occupied) / CAST(SUM(units) as float) END as vacancy_rate ".
		"FROM fact.housing h ".
		"JOIN dim.datasource d on h.datasource_id = d.datasource_id ".
		"JOIN dim.mgra m ON h.mgra_id = m.mgra_id ".
		"JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id ". 
		"WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? ".
		"GROUP BY h.year, s.long_name";
	
	echo Query::getInstance()->getResultAsJson($sql, $params);
			
})->name('forecast_housing');

//Forecast - Ethnicity
$app->get('/forecast/:series/:geotype/:zone/ethnicity', function ($series, $geoType, $zone)
{
	$columnName = $GLOBALS['geotypes'][$geoType];
	$datasource_id = $GLOBALS['datasources']['forecast'][$series];
	$params = [$datasource_id, $zone];

	$sql = "SELECT ase.year, e.long_name as ethnicity, sum(ase.population) as population ".
		"FROM fact.age_sex_ethnicity ase ".
		"JOIN dim.datasource d on ase.datasource_id = d.datasource_id ".
		"JOIN dim.mgra m on ase.mgra_id = m.mgra_id ".
		"JOIN dim.ethnicity e on ase.ethnicity_id = e.ethnicity_id ".
		"WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? ".
		"GROUP BY ase.year, e.long_name";
	  
	echo Query::getInstance()->getResultAsJson($sql, $params);

})->name('forecast_ethnicity');

//Forecast - Age
$app->get('/forecast/:series/:geotype/:zone/age', function ($series, $geoType, $zone)
{
    $columnName = $GLOBALS['geotypes'][$geoType];
    $datasource_id = $GLOBALS['datasources']['forecast'][$series];
    $params = [$datasource_id, $zone];
    
    $sql = "SELECT ase.year, s.sex, a.group_10yr, sum(ase.population) as population ".
        "FROM fact.age_sex_ethnicity ase ".
        "JOIN dim.datasource d on ase.datasource_id = d.datasource_id ".
        "JOIN dim.mgra m on ase.mgra_id = m.mgra_id ".
        "JOIN dim.age_group a on ase.age_group_id = a.age_group_id ".
        "JOIN dim.sex s on ase.sex_id = s.sex_id ".
        "WHERE d.datasource_id = ? and lower(m.".$columnName.") = ? ".
        "GROUP BY ase.year, s.sex, a.group_10yr";
    
    echo Query::getInstance()->getResultAsJson($sql, $params);
    
})->name('forecast_age');

$app->get('/forecast/:series/:geotype/:zone/income', function ($series, $geoType, $zone) use ($app)
{
	$columnName = $GLOBALS['geotypes'][$geoType];
	$datasource_id = $GLOBALS['datasources']['forecast'][$series];
	$params = [$datasource_id, $zone];
	
	$sql = "SELECT i.yr as year, g.name as income_group ,sum(i.households) as households ".
			"FROM fact.household_income i ".
			"JOIN dim.income_group g ON i.income_group_id = g.income_group_id ".
			"JOIN dim.datasource d ON i.datasource_id = d.datasource_id ".
			"JOIN dim.mgra m ON i.mgra_id = m.mgra_id ".
			"WHERE d.datasource_id = ? AND lower(m.".$columnName.") = ? ".
			"GROUP BY i.yr, i.income_group_id, g.name ".
			"ORDER BY i.yr, i.income_group_id";
	
	echo Query::getInstance()->getResultAsJson($sql, $params);
	
})->name('forecast_income');

$app->get('/forecast/:series/:geotype/:zone/jobs', function ($series, $geoType, $zone) use ($app)
{
	$columnName = $GLOBALS['geotypes'][$geoType];
	$datasource_id = $GLOBALS['datasources']['forecast'][$series];
	$params = [$datasource_id, $zone];
	
	$sql = "select j.year, e.full_name as category, sum(j.jobs) as jobs ".
		"FROM fact.jobs j ".
		"JOIN dim.mgra m ON j.mgra_id = m.mgra_id ".
		"JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id ".
		"WHERE j.datasource_id = ?	AND e.civilian = 1 AND lower(m.".$columnName.") = ? ".
		"GROUP BY j.year, e.full_name";
	
	echo Query::getInstance()->getResultAsJson($sql, $params);
	
})->name('forecast_jobs');

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
