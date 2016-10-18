<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
//ini_set('display_errors', 1);

require ("api/ACSQuery.php");


/*  new excel format-- test with estimate */

function generateACSjson($datasource, $year, $geotype, $zone) 
{
	
	
	//******* 1 Race-Ethnicity**************
	$raceEthnicityHeader = array(
				strtoupper($geotype) => 'string',
				'YEAR' => 'string',
				'RACE' => 'string',
				'NUMBER' => 'integer'
				);
	
	
		$raceEthnicitySql = " select geozone as {$geotype}
		,{$year} Yearnumber
		,ethnicity_short_name
		,population
		 from app.acs_ethnicity( $1, $2, $3)";
		
			$raceEthnicityJson = Query::getInstance()->getResultAsJson( $raceEthnicitySql, 2, $geotype, $zone);
			$raceEthnicity_file_name = strtolower(join("_", array('raceEthnicity', "censusacs", $year, $geotype, $zone)).".json");
			$raceEthnicity_all_file_name = strtolower(join("_", array('raceEthnicity', "censusacs", $year, $geotype, "all")).".json");
			$raceEthnicity_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
			$raceEthnicity_file_path = join(DIRECTORY_SEPARATOR, array($raceEthnicity_file_dir, $raceEthnicity_file_name));
			$raceEthnicity_all_file_path = join(DIRECTORY_SEPARATOR, array($raceEthnicity_file_dir, $raceEthnicity_all_file_name));
	//		mkdir($raceEthnicity_file_dir, 0777 );
			$fp = fopen($raceEthnicity_file_path, "w");			
			$fpp = fopen($raceEthnicity_all_file_path, "a");
			fwrite($fp, $raceEthnicityJson);
			$raceEthnicityJson_trim = str_replace('[', '',$raceEthnicityJson);
			$raceEthnicityJson_trim = preg_replace( '/]/','', $raceEthnicityJson);
			//$raceEthnicityJson_trim = str_replace('}]', '},', $raceEthnicityJson_trim);
			//$raceEthnicityJson_trim = str_replace('},]', '},', $raceEthnicityJson_trim);
			fwrite($fpp, $raceEthnicityJson_trim);
			fclose($fp);
			fclose($fpp);
			
			//echo $raceEthnicityJson;
	//******* 2. Age and Sex*************
	
	$ageSexHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'SEX' => 'string',
		'AGE GROUP-5 YEARS' => 'string',
		'POPULATION' => 'integer'
		
		);
	$ageSexSql = "select agesex.geozone {$geotype} , {$year} Yearnumber, 
		ds.sex, agesex.age_group_name, agesex.population
		from app.acs_pop_by_age_and_sex( $1, $2, $3 ) agesex
		inner join dim.sex ds
		on ds.sex_id = agesex.sex_id
		order by agesex.sex_id, agesex.age_group_id;";
		
	$ageSexSqlpivot = "select  geozone {$geotype}, {$year} Yearnumber,
		age_group_name,	
		(sum( case when sex_id='2' then population end) + sum( case when sex_id='1' then population end)) as Total,	
		sum( case when sex_id='2' then population end) as Male,
		sum( case when sex_id='1' then population end) as Female
		from  app.acs_pop_by_age_and_sex($1, $2, $3 )
		group by geozone, geotype, age_group_name, age_group_id
		order by age_group_id;	 	";
		
	//$ageSexArray = Query::getInstance()->getResultAsArray($ageSexSql, null, $geotype, $zone);
	$ageSexJson = Query::getInstance()->getResultAsJson( $ageSexSqlpivot, 5, $geotype, $zone);
	$ageSex_file_name = strtolower(join("_", array('ageSex', "censusacs", $year, $geotype, $zone)).".json");
	$ageSex_all_file_name = strtolower(join("_", array('ageSex', "censusacs", $year, $geotype, "all")).".json");
	$ageSex_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ageSex_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSex_file_name));
	$ageSex_all_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSex_all_file_name));
	//echo $ageSex_file_dir;
	//		mkdir($raceEthnicity_file_dir, 0777 );
	$fp = fopen($ageSex_file_path, "w");		
	$fpp = fopen($ageSex_all_file_path, "a");		
	fwrite($fp, $ageSexJson);
	$ageSexJson_trim = str_replace('[', '',$ageSexJson);
	$ageSexJson_trim = str_replace('}]', '},', $ageSexJson_trim);
	fwrite($fpp, $ageSexJson_trim);
	fclose($fp);
	fclose($fpp);
	
	//todo median ages
	$ageSexMedianSql = "select geography_zone {$geotype}, {$year} Yearnumber,
		'median' Medianage,
		median_age,
		median_age_male,
		median_age_female
		from app.fn_median_age( $1, $2, $3); ";
		
	$ageSexMedianJson = Query::getInstance()->getResultAsJson( $ageSexMedianSql, 5, $geotype, $zone);
	$ageSexMedian_file_name = strtolower(join("_", array('ageSexMedian', "censusacs", $year, $geotype, $zone)).".json");
	$ageSexMedian_all_file_name = strtolower(join("_", array('ageSexMedian', "censusacs", $year, $geotype, "all")).".json");
	$ageSex_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ageSexMedian_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSexMedian_file_name));
	$ageSexMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($ageSex_file_dir, $ageSexMedian_all_file_name));
	//echo $ageSex_file_dir;
	//		mkdir($raceEthnicity_file_dir, 0777 );
	$fp = fopen($ageSexMedian_file_path, "w");			
	fwrite($fp, $ageSexMedianJson);
	$fpp = fopen($ageSexMedian_all_file_path, "a");			
	$ageSexMedianJson_trim = str_replace('[', '',$ageSexMedianJson);
	$ageSexMedianJson_trim = str_replace('}]', '},', $ageSexMedianJson_trim);
	fwrite($fpp, $ageSexMedianJson_trim);
	fclose($fpp);
		
	//******* 3.  MARITAL STATUS*******
	
	$maritalStatusHeader = array(
		strtoupper($geotype) => 'string',
		'Total age 15 and over' => 'integer',
		'Never Married' => 'integer',
		'Married, excluding separated' => 'integer',
		'Separated' => 'integer',
		'Widowed' => 'integer',
		'Divorced' => 'integer'
		);

	$maritalStatusSql = "select geography_zone {$geotype}, {$year} Yearnumber,
			unnest(array[   'Never Married',
					'Married, excluding separated',
					'Separated',
					'Windowed',
					'Divorced'
				 ]) AS MaritalStatus,
			unnest(array[	pop_15plus_no_married,
					pop_15plus_married,
					pop_15plus_separated,
					pop_15plus_widowed,
					pop_15plus_divorced		
				]) AS Number		
			from app.acs_pop_by_marital_status(  $1, $2, $3);";

	//$maritalStatusArray = Query::getInstance()->getResultAsArray($maritalStatusSql2, null, $geotype, $zone);
	$maritalStatusJson = Query::getInstance()->getResultAsJson( $maritalStatusSql, 5, $geotype, $zone);
	$maritalStatus_file_name = strtolower(join("_", array('maritalStatus', "censusacs", $year, $geotype, $zone)).".json");
	$maritalStatus_all_file_name = strtolower(join("_", array('maritalStatus', "censusacs", $year, $geotype, "all")).".json");
	$maritalStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$maritalStatus_file_path = join(DIRECTORY_SEPARATOR, array($maritalStatus_file_dir, $maritalStatus_file_name));
	$maritalStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($maritalStatus_file_dir, $maritalStatus_all_file_name));
	$fp = fopen($maritalStatus_file_path, "w");			
	fwrite($fp, $maritalStatusJson);
	fclose($fp);
	$fpp = fopen($maritalStatus_all_file_path, "a");	
	$maritalStatusJson_trim = str_replace('[', '',$maritalStatusJson);
	$maritalStatusJson_trim = str_replace('}]', '},', $maritalStatusJson_trim);		
	fwrite($fpp, $maritalStatusJson_trim);
	fclose($fpp);
	
	//******* 4 HouseholdGroupQuarters***********
	$householdGroupQtrsHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'STATUS' => 'string',
		'NUMBER' => 'integer'
		);
	$householdGroupQtrsSql = "SELECT geozone {$geotype} , {$year} Yearnumber,
			housing_type, population
			from app.acs_group_quarters($1, $2, $3);";
	//$housholdGroupQtrsArray = Query::getInstance()->getResultAsArray($householdGroupQtrsSql, 5, $geotype, $zone);
	$householdGroupQtrsJson = Query::getInstance()->getResultAsJson( $householdGroupQtrsSql, 5, $geotype, $zone);
	$householdGroupQtrs_file_name = strtolower(join("_", array('householdGroupQtrs', "censusacs", $year, $geotype, $zone)).".json");
	$householdGroupQtrs_all_file_name = strtolower(join("_", array('householdGroupQtrs', "censusacs", $year, $geotype, "all")).".json");
	$householdGroupQtrs_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdGroupQtrs_file_path = join(DIRECTORY_SEPARATOR, array($householdGroupQtrs_file_dir, $householdGroupQtrs_file_name));
	$householdGroupQtrs_all_file_path = join(DIRECTORY_SEPARATOR, array($householdGroupQtrs_file_dir, $householdGroupQtrs_all_file_name));
	$fp = fopen($householdGroupQtrs_file_path, "w");			
	fwrite($fp, $householdGroupQtrsJson);
	fclose($fp);
	$fpp = fopen($householdGroupQtrs_all_file_path, "a");	
	$householdGroupQtrsJson_trim = str_replace('[', '',$householdGroupQtrsJson);
	$householdGroupQtrsJson_trim = str_replace('}]', '},', $householdGroupQtrsJson_trim);		
	fwrite($fpp, $householdGroupQtrsJson_trim);
	fclose($fpp);
	//******* 5. Age, Race-Ethnicity*************
	$ethnicityAgeGroupTopArray = array();
	$ethnicityAgeGroupHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'AGE' => 'string',
		'HISPANIC' => 'integer',
		'WHITE' => 'integer',
		'BLACK' => 'integer',
		'AMERICAN INDIAN' => 'integer',
		'ASIAN' => 'integer',
		'PACIFIC ISLANDER' => 'integer',
		'OTHER' => 'integer',
		'TWO OR MORE' => 'integer'
		);
	
	/*$ethnicityAgeGroupSql = "SELECT geozone {$geotype} , {$year} Yearnumber,
			age_group_name,  short_name, population 
			from app.acs_pop_by_race_ethnicity_age( $1, $2, $3)
			order by age_group_id, ethnicityid;";*/
			
	$ethnicityAgeGroupSql ="SELECT geozone {$geotype} , {$year} Yearnumber,
			age_group_name,  
			sum( case when ethnicityid = '1' then population end) as \"Hispanic\",
			sum( case when ethnicityid = '2' then population end)  as  \"White\",
			sum( case when ethnicityid = '3' then population end)  as   \"Black\",
			sum( case when ethnicityid = '4' then population end)  as   \"American Indian\",
			sum( case when ethnicityid = '5' then population end)  as   \"Asian\",
			sum( case when ethnicityid = '6' then population end)  as   \"Pacific Islander\",
			sum( case when ethnicityid = '7' then population end)  as   \"Other\",
			sum( case when ethnicityid = '8' then population end)  as   \"Two or More\"
			from app.acs_pop_by_race_ethnicity_age( $1, $2, $3 )
			group by geozone, age_group_name,  age_group_id
			order by age_group_id;";
			
	$ethnicityAgeGroupMedianSql = "SELECT  geography_zone {$geotype} , {$year} Yearnumber, 
			'median age'  medianage,
			sum( case when ethnicity_id = 1 then median_age end) as \"Hispanic\",
			sum( case when ethnicity_id = 2 then median_age end) as \"White\",
			sum( case when ethnicity_id = 3 then median_age end) as  \"Black\",
			sum( case when ethnicity_id = 4 then median_age end) as  \"American Indian\",
			sum( case when ethnicity_id = 5 then median_age end) as  \"Asian\",
			sum( case when ethnicity_id = 6 then median_age end) as  \"Pacific Islander\",
			sum( case when ethnicity_id = 7 then median_age end) as  \"Other\",
			sum( case when ethnicity_id = 8 then median_age end) as  \"Two or More\"
			from app.fn_median_age_by_ethnicity( $1, $2, $3)
			group by geography_zone;";
			
	/*
	$ethnicityAgeGroupArray =  Query::getInstance()->getResultAsArray($ethnicityAgeGroupSql, null, $geotype, $zone);
	$ethnicityAgeGroupMedianArray = Query::getInstance()->getResultAsArray($ethnicityAgeGroupMedianSql, null, $geotype, $zone);
	*/
	$ethnicityAgeGroupJson = Query::getInstance()->getResultAsJson( $ethnicityAgeGroupSql, 5, $geotype, $zone);
	$ethnicityAgeGroup_file_name = strtolower(join("_", array('ethnicityAgeGroup', "censusacs", $year, $geotype, $zone)).".json");
	$ethnicityAgeGroup_all_file_name = strtolower(join("_", array('ethnicityAgeGroup', "censusacs", $year, $geotype, "all")).".json");
	$ethnicityAgeGroup_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ethnicityAgeGroup_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroup_file_dir, $ethnicityAgeGroup_file_name));
	$ethnicityAgeGroup_all_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroup_file_dir, $ethnicityAgeGroup_all_file_name));
	$fp = fopen($ethnicityAgeGroup_file_path, "w");			
	fwrite($fp, $ethnicityAgeGroupJson);
	fclose($fp);
	$fpp = fopen($ethnicityAgeGroup_all_file_path, "a");			
	$ethnicityAgeGroupJson_trim = str_replace('[', '',$ethnicityAgeGroupJson);
	$ethnicityAgeGroupJson_trim = str_replace('}]', '},', $ethnicityAgeGroupJson_trim);
	fwrite($fpp, $ethnicityAgeGroupJson_trim);
	fclose($fpp);
	
	//median age
	$ethnicityAgeGroupMedianJson = Query::getInstance()->getResultAsJson( $ethnicityAgeGroupMedianSql, 5, $geotype, $zone);
	$ethnicityAgeGroupMedian_file_name = strtolower(join("_", array('ethnicityAgeGroupMedian', "censusacs", $year, $geotype, $zone)).".json");
	$ethnicityAgeGroupMedian_all_file_name = strtolower(join("_", array('ethnicityAgeGroupMedian', "censusacs", $year, $geotype, "all")).".json");
	$ethnicityAgeGroupMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ethnicityAgeGroupMedian_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroupMedian_file_dir, $ethnicityAgeGroupMedian_file_name));
	$ethnicityAgeGroupMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($ethnicityAgeGroupMedian_file_dir, $ethnicityAgeGroupMedian_all_file_name));
	$fp = fopen($ethnicityAgeGroupMedian_file_path, "w");			
	fwrite($fp, $ethnicityAgeGroupMedianJson);
	fclose($fp);
	$fpp = fopen($ethnicityAgeGroupMedian_all_file_path, "a");			
	$ethnicityAgeGroupMedianJson_trim = str_replace('[', '',$ethnicityAgeGroupMedianJson);
	$ethnicityAgeGroupMedianJson_trim = str_replace('}]', '},', $ethnicityAgeGroupMedianJson_trim);
	fwrite($fpp, $ethnicityAgeGroupMedianJson_trim);
	fclose($fpp);
	
	//******* 6. LANGUAGE SPOKEN AT HOME************//
	$languageSpokenHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'POPULATION' => 'string',
		'TOTAL' => 'integer'
		);	
	
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
			unnest(array[pop_5plus , pop_5plus_english , pop_5plus_spanish , pop_5plus_spanish_english , pop_5plus_spanish_no_english , pop_5plus_asian , pop_5plus_asian_english , pop_5plus_asian_no_english , 
			pop_5plus_other , pop_5plus_other_english , pop_5plus_other_no_english] ) as total
			from app.acs_pop_by_language_spoken( $1, $2, $3);";
	
	//$languageSpokenArray = Query::getInstance()->getResultAsArray($languageSpokenSql, null, $geotype, $zone);
	$languageSpokenJson = Query::getInstance()->getResultAsJson( $languageSpokenSql, 9, $geotype, $zone);
	$languageSpoken_file_name = strtolower(join("_", array('languageSpoken', "censusacs", $year, $geotype, $zone)).".json");
	$languageSpoken_all_file_name = strtolower(join("_", array('languageSpoken', "censusacs", $year, $geotype, "all")).".json");
	$languageSpoken_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$languageSpoken_file_path = join(DIRECTORY_SEPARATOR, array($languageSpoken_file_dir, $languageSpoken_file_name));
	$languageSpoken_all_file_path = join(DIRECTORY_SEPARATOR, array($languageSpoken_file_dir, $languageSpoken_all_file_name));
	$fp = fopen($languageSpoken_file_path, "w");			
	fwrite($fp, $languageSpokenJson);
	fclose($fp);
	$fpp = fopen($languageSpoken_all_file_path, "a");			
	$languageSpokenJson_trim = str_replace('[', '',$languageSpokenJson);
	$languageSpokenJson_trim = str_replace('}]', '},', $languageSpokenJson_trim);
	fwrite($fpp, $languageSpokenJson_trim);
	fclose($fpp);
	
	//*******7. EDUCATIONAL ATTAINMENT**********
	$educationalAttainmentHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'LEVEL' => 'string',
		'TOTAL' => 'integer'
		);

	$educationalAttainmentSql = " select  geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array[ 'Total Population age 25 and older',
				'Less than 9th grade' ,
				'9th through 12th grade, no diploma',
				'High School grad (Incl. equivalency)',
				'Some college, no degree',
				'Associate degree',
				'Bachelor''s degree' ,
				'Master''s degree' ,
				'Professional school degree',
				'Doctorate degree' 
				 ]) AS Level,	
			unnest(array[pop_25plus_edu,
			pop_25plus_edu_lt9,
			pop_25plus_edu_9to12_no_degree,
			pop_25plus_edu_high_school,
			pop_25plus_edu_col_no_degree,
			pop_25plus_edu_associate,
			pop_25plus_edu_bachelor,
			pop_25plus_edu_master,
			pop_25plus_edu_prof,
			pop_25plus_edu_doctorate		
			]) as population
			from app.acs_pop_by_educational_attainment( $1, $2, $3 );";
	//$educationalAttainmentArray = Query::getInstance()->getResultAsArray($educationalAttainmentSql, null, $geotype, $zone);
	//$educationalAttainmentArray = Query::getInstance()->getResultAsArray($educationalAttainmentSql, null, $geotype, $zone);
	$educationalAttainmentJson = Query::getInstance()->getResultAsJson( $educationalAttainmentSql, 9, $geotype, $zone);
	$educationalAttainment_file_name = strtolower(join("_", array('educationalAttainment', "censusacs", $year, $geotype, $zone)).".json");
	$educationalAttainment_all_file_name = strtolower(join("_", array('educationalAttainment', "censusacs", $year, $geotype, "all")).".json");
	$educationalAttainment_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$educationalAttainment_file_path = join(DIRECTORY_SEPARATOR, array($educationalAttainment_file_dir, $educationalAttainment_file_name));
	$educationalAttainment_all_file_path = join(DIRECTORY_SEPARATOR, array($educationalAttainment_file_dir, $educationalAttainment_all_file_name));
	$fp = fopen($educationalAttainment_file_path, "w");			
	fwrite($fp, $educationalAttainmentJson);
	fclose($fp);
	$fpp = fopen($educationalAttainment_all_file_path, "a");			
	$educationalAttainmentJson_trim = str_replace('[', '',$educationalAttainmentJson);
	$educationalAttainmentJson_trim = str_replace('}]', '},', $educationalAttainmentJson_trim);
	$educationalAttainmentJson_trim = str_replace('},]', '},', $educationalAttainmentJson_trim);
	fwrite($fpp, $educationalAttainmentJson_trim);
	fclose($fpp);
	//*******8.  SchoolEnrollment*******
	$schoolEnrollmentHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'DESCRIPTION' => 'string',
		'TOTAL' => 'integer',
		'PUBLIC' => 'integer',
		'PRIVATE' => 'integer'
		);

	$schoolEnrollmentSql = "select geography_zone  {$geotype} , {$year} Yearnumber,
				unnest(array[ 'Total Population age 3 and older',
					'Nusery/Preschool'  ,
					'Kindergarden to grade 4',
					'Grade 5 to grade 8',
					'Grade 9 to grade 12',
					'College, undergraduate',
					'Graduate or professional school' ,
					'Not enrolled in school'  
					 ]) AS description,	
				unnest(array[pop_3plus,
				pop_3plus_sch_pre,
				pop_3plus_sch_Kto4,
				pop_3plus_sch_5to8,
				pop_3plus_sch_9to12,
				pop_3plus_sch_college,
				pop_3plus_sch_grad,
				pop_3plus_no_enroll		
				]) as Total,
				unnest(array[pop_3plus_sch_public,
				pop_3plus_sch_pre_public,
				pop_3plus_sch_Kto4_public,
				pop_3plus_sch_5to8_public,
				pop_3plus_sch_9to12_public,
				pop_3plus_sch_college_public,
				pop_3plus_sch_grad_public,
				null		
				]) as Public,
				unnest(array[pop_3plus_sch_private,
				pop_3plus_sch_pre_private,
				pop_3plus_sch_Kto4_private,
				pop_3plus_sch_5to8_private,
				pop_3plus_sch_9to12_private,
				pop_3plus_sch_college_private,
				pop_3plus_sch_grad_private,
				null		
				]) as Private
				from app.acs_pop_by_school_enrollment($1, $2, $3); ";

	//$schoolEnrollmentArray = Query::getInstance()->getResultAsArray($schoolEnrollmentSql, null, $geotype,  $zone);
	$schoolEnrollmentJson = Query::getInstance()->getResultAsJson( $schoolEnrollmentSql, 5, $geotype, $zone);
	$schoolEnrollment_file_name = strtolower(join("_", array('schoolEnrollment', "censusacs", $year, $geotype, $zone)).".json");
	$schoolEnrollment_all_file_name = strtolower(join("_", array('schoolEnrollment', "censusacs", $year, $geotype, "all")).".json");
	$schoolEnrollment_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$schoolEnrollment_file_path = join(DIRECTORY_SEPARATOR, array($schoolEnrollment_file_dir, $schoolEnrollment_file_name));
	$schoolEnrollment_all_file_path = join(DIRECTORY_SEPARATOR, array($schoolEnrollment_file_dir, $schoolEnrollment_all_file_name));
	$fp = fopen($schoolEnrollment_file_path, "w");			
	fwrite($fp, $schoolEnrollmentJson);
	fclose($fp);
	$fpp = fopen($schoolEnrollment_all_file_path, "a");			
	$schoolEnrollmentJson_trim = str_replace('[', '',$schoolEnrollmentJson);
	$schoolEnrollmentJson_trim = str_replace('}]', '},', $schoolEnrollmentJson_trim);
	fwrite($fpp, $schoolEnrollmentJson_trim);
	fclose($fpp);
	
	//*******9.  Disability*******
	//******* 10 Households by Type and Presence of Children Under 18************
	$householdsTypeByUnder18Header =  array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'FAMILY TYPE' => 'string',
		'TOTAL' =>	'integer',
		'WITH PERSONS U18' => 'integer',
		'WITHOUT PERSONS U18' => 'integer'
		);

	$householdsTypeByUnder18Sql = "select geography_zone {$geotype} , {$year} Yearnumber,
				unnest(array[ 'Family households',
					'Married couple family'  ,
					'Other Family' ,
					'Male householder no wife',
					'Female householder no husband',
					'Nonfamily households',
					'Householder Living alone' ,
					'Other nonfamily households' 
					 ]) AS FamilyType,	
				unnest(array[hh_fam_u18 + hh_fam_n18,
				hh_fam_mar_u18 + hh_fam_mar_n18,
				hh_fam_oth,
				hh_fam_oth_m,
				hh_fam_oth_f,
				hh_nfam_alone + hh_nfam_other,
				hh_nfam_alone,
				hh_nfam_other		
				]) as Total,
				unnest(array[hh_fam_u18,
				hh_fam_mar_u18,
				hh_fam_oth_u18,
				hh_fam_oth_m_u18,
				hh_fam_oth_f_u18,
				hh_nfam_u18,
				null,
				null		
				]) as WithPersonsUnder18,
				unnest(array[hh_fam_n18,
				hh_fam_mar_n18,
				hh_fam_oth_n18,
				hh_fam_oth_m_n18,
				hh_fam_oth_f_n18,
				hh_nfam_n18,
				null,
				null		
				]) as WithoutPersonsUnder18
				from app.acs_pop_by_household_by_type_children( $1, $2, $3);";
	//$householdsTypeByUnder18Array = Query::getInstance()->getResultAsArray($householdsTypeByUnder18Sql, null, $geotype,  $zone);
	$householdsTypeByUnder18Json = Query::getInstance()->getResultAsJson( $householdsTypeByUnder18Sql, 5, $geotype, $zone);
	$householdsTypeByUnder18_file_name = strtolower(join("_", array('householdsTypeByUnder18', "censusacs", $year, $geotype, $zone)).".json");
	$householdsTypeByUnder18_all_file_name = strtolower(join("_", array('householdsTypeByUnder18', "censusacs", $year, $geotype, "all")).".json");
	$householdsTypeByUnder18_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdsTypeByUnder18_file_path = join(DIRECTORY_SEPARATOR, array($householdsTypeByUnder18_file_dir, $householdsTypeByUnder18_file_name));
	$householdsTypeByUnder18_all_file_path = join(DIRECTORY_SEPARATOR, array($householdsTypeByUnder18_file_dir, $householdsTypeByUnder18_all_file_name));
	$fp = fopen($householdsTypeByUnder18_file_path, "w");			
	fwrite($fp, $householdsTypeByUnder18Json);
	fclose($fp);
	$fpp = fopen($householdsTypeByUnder18_all_file_path, "a");			
	$householdsTypeByUnder18Json_trim = str_replace('[', '',$householdsTypeByUnder18Json);
	$householdsTypeByUnder18Json_trim = str_replace('}]', '},', $householdsTypeByUnder18Json_trim);
	fwrite($fpp, $householdsTypeByUnder18Json_trim);
	fclose($fpp);
	
	//******* 11. HousingUnitsType**********
	$housingUnitsTypeHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'UNIT TYPE' => 'string',
		'UNITS' => 'int',
		'OCCUPIED'  => 'int',
		'PERCENTAGE' => 'float'
		);
	//$housingUnitsTypeSql = "SELECT * from  app.acs_housing_units( $1, $2, $3);";
	$housingUnitsTypeSql = "SELECT geography_zone {$geotype}, {$year} Yearnumber, structure_type, units, occupied,
			percent_of_units from app.acs_housing_units( $1, $2, $3);";
	//$housingUnitsTypeArray = Query::getInstance()->getResultAsArray($housingUnitsTypeSql, null, $geotype,  $zone);
	$housingUnitsTypeJson = Query::getInstance()->getResultAsJson( $housingUnitsTypeSql, 9, $geotype, $zone);
	$housingUnitsType_file_name = strtolower(join("_", array('housingUnitsType', "censusacs", $year, $geotype, $zone)).".json");
	$housingUnitsType_all_file_name = strtolower(join("_", array('housingUnitsType', "censusacs", $year, $geotype, "all")).".json");
	$housingUnitsType_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$housingUnitsType_file_path = join(DIRECTORY_SEPARATOR, array($housingUnitsType_file_dir, $housingUnitsType_file_name));
	$housingUnitsType_all_file_path = join(DIRECTORY_SEPARATOR, array($housingUnitsType_file_dir, $housingUnitsType_all_file_name));
	$fp = fopen($housingUnitsType_file_path, "w");			
	fwrite($fp, $housingUnitsTypeJson);
	fclose($fp);
	$fpp = fopen($housingUnitsType_all_file_path, "a");			
	$housingUnitsTypeJson_trim = str_replace('[', '',$housingUnitsTypeJson);
	$housingUnitsTypeJson_trim = str_replace('}]', '},', $housingUnitsTypeJson_trim);
	fwrite($fpp, $housingUnitsTypeJson_trim);
	fclose($fpp);
	
	//******* 12.  HOUSING VALUE**********
	$housingValueHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'UNITS' => 'string',
		'NUMBER' => 'integer'
		);

	$housingValueSql = "select  geography_zone  {$geotype} , {$year} Yearnumber,
			unnest(array[ 'Total units',
				'Less than $150,000' ,
				'$150,000 to $199,999',
				'$200,000 to $249,999',
				'$250,000 to $299,999',
				'$300,000 to $399,999',
				'$400,000 to $499,999' ,
				'$500,000 to $749,999' ,
				'$750,000 to $999,999',
				'$1,000,000 or more' 
				 ]) AS description,	
			unnest(array[hh_owner_value,
			hh_owner_value_lt150K,
			hh_owner_value_150Kto200K,
			hh_owner_value_200Kto250K,
			hh_owner_value_250Kto300K,
			hh_owner_value_300Kto400K,
			hh_owner_value_400Kto500K,
			hh_owner_value_500Kto750K,
			hh_owner_value_750Kto1M,
			hh_owner_value_1Mplus		
			]) as Total
			from app.acs_pop_housing_values( $1, $2, $3);";
	
	//$housingValueArray = Query::getInstance()->getResultAsArray($housingValueSql, null, $geotype,  $zone);
	$housingValueJson = Query::getInstance()->getResultAsJson( $housingValueSql, 5, $geotype, $zone);
	$housingValue_file_name = strtolower(join("_", array('housingValue', "censusacs", $year, $geotype, $zone)).".json");
	$housingValue_all_file_name = strtolower(join("_", array('housingValue', "censusacs", $year, $geotype, "all")).".json");
	$housingValue_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$housingValue_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValue_file_name));
	$housingValue_all_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValue_all_file_name));
	$fp = fopen($housingValue_file_path, "w");			
	fwrite($fp, $housingValueJson);
	fclose($fp);
	$fpp = fopen($housingValue_all_file_path, "a");			
	$housingValueJson_trim = str_replace('[', '',$housingValueJson);
	$housingValueJson_trim = str_replace('}]', '},', $housingValueJson_trim);
	fwrite($fpp, $housingValueJson_trim);
	fclose($fpp);
	// todo need median values
	$housingValueMedianSql = "select  geography_zone  {$geotype} , {$year} Yearnumber,
			'median value' medianValue,
			median_housing_value
			from app.acs_median_housing_value( $1, $2, $3);";
	$housingValueMedianJson = Query::getInstance()->getResultAsJson( $housingValueMedianSql, 9, $geotype, $zone);
	$housingValueMedian_file_name = strtolower(join("_", array('housingValueMedian', "censusacs", $year, $geotype, $zone)).".json");
	$housingValueMedian_all_file_name = strtolower(join("_", array('housingValueMedian', "censusacs", $year, $geotype, "all")).".json");
	$housingValueMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$housingValueMedian_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValueMedian_file_name));
	$housingValueMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($housingValue_file_dir, $housingValueMedian_all_file_name));
	$fp = fopen($housingValueMedian_file_path, "w");			
	fwrite($fp, $housingValueMedianJson);
	fclose($fp);
	$fpp = fopen($housingValueMedian_all_file_path, "a");			
	$housingValueMedianJson_trim = str_replace('[', '',$housingValueMedianJson);
	$housingValueMedianJson_trim = str_replace('}]', '},', $housingValueMedianJson_trim);
	fwrite($fpp, $housingValueMedianJson_trim);
	fclose($fpp);		
	//******* 13. Yr House Built***************************************
	$yrHouseBuiltHeader  = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'BUILT YR' => 'string',
		'NUMBER' => 'integer'
		);

	$yrHouseBuiltSql = "select geography_zone {$geotype} ,{$year} Yearnumber,
				unnest(array[ 'Total units' ,
					'2005 or Later'  ,
					'2000 to 2004',
					'1990 to 1999',
					'1980 to 1989',
					'1970 to 1979',
					'1960 to 1969',
					'1950 to 1959' ,
					'1940 to 1949',
					'1939 or earlier'
					 ]) AS YearBuilt ,	
				unnest(array[hs,
				hs_2005later,
				hs_2000to2004,
				hs_1990to1999,
				hs_1980to1989,
				hs_1970to1979,
				hs_1960to1969,
				hs_1950to1959,
				hs_1940to1949,
				hs_1939before
				]) as BuiltNumber	
				from app.acs_year_structure( $1, $2, $3);";
	//$yrHouseBuiltArray = Query::getInstance()->getResultAsArray($yrHouseBuiltSql, null, $geotype,  $zone);
	$yrHouseBuiltJson = Query::getInstance()->getResultAsJson( $yrHouseBuiltSql, 5, $geotype, $zone);
	$yrHouseBuilt_file_name = strtolower(join("_", array('yrHouseBuilt', "censusacs", $year, $geotype, $zone)).".json");
	$yrHouseBuilt_all_file_name = strtolower(join("_", array('yrHouseBuilt', "censusacs", $year, $geotype, "all")).".json");
	$yrHouseBuilt_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$yrHouseBuilt_file_path = join(DIRECTORY_SEPARATOR, array($yrHouseBuilt_file_dir, $yrHouseBuilt_file_name));
	$yrHouseBuilt_all_file_path = join(DIRECTORY_SEPARATOR, array($yrHouseBuilt_file_dir, $yrHouseBuilt_all_file_name));
	$fp = fopen($yrHouseBuilt_file_path, "w");			
	fwrite($fp, $yrHouseBuiltJson);
	fclose($fp);
	$fpp = fopen($yrHouseBuilt_all_file_path, "a");			
	$yrHouseBuiltJson_trim = str_replace('[', '',$yrHouseBuiltJson);
	$yrHouseBuiltJson_trim = str_replace('}]', '},', $yrHouseBuiltJson_trim);
	fwrite($fpp, $yrHouseBuiltJson_trim);
	fclose($fpp);
	//******* 14. House Tenure and OccupRoom *********
	$houseTenureOccupiedHeader   = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'OCCUPANT PER ROOM' => 'string',
		'TOTAL' => 'string',
		'RENTER OCCUPIED' => 'integer',
		'OWNER OCCUPIED' => 'integer'
		);

	$houseTenureOccupiedSql = " select geography_zone {$geotype} ,  {$year}  Yearnumber,
			unnest(array[ '1.00 occupant housing per room or less',
				'1.01 to 1.50 occupants per room' ,
				'1.51 to 2.00 occupants per room' ,
				'2.01 or more occupants per room'
				 ]) AS Occupants ,
			unnest(array[ hh_occupant_lt1 ,
				hh_occupant_1to1dot5,
				hh_occupant_1dot5to2,
				hh_occupant_2more
				]) as Total,	
			unnest(array[ hh_rent_occupant_lt1,
				hh_rent_occupant_1to1dot5 ,
				hh_rent_occupant_1dot5to2 ,
				hh_rent_occupant_2more
				]) as Renter,
			unnest( array[ hh_own_occupant_lt1,
				hh_own_occupant_1to1dot5 ,
				hh_own_occupant_1dot5to2 ,
				hh_own_occupant_2more
				]) as Owner
			from app.acs_housing_units_by_tenure( $1, $2, $3 );";

	//$houseTenureOccupiedArray  = Query::getInstance()->getResultAsArray($houseTenureOccupiedSql, null, $geotype,  $zone);	
	$houseTenureOccupiedJson = Query::getInstance()->getResultAsJson( $houseTenureOccupiedSql, 5, $geotype, $zone);
	$houseTenureOccupied_file_name = strtolower(join("_", array('houseTenureOccupied', "censusacs", $year, $geotype, $zone)).".json");
	$houseTenureOccupied_all_file_name = strtolower(join("_", array('houseTenureOccupied', "censusacs", $year, $geotype, "all")).".json");
	$houseTenureOccupied_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$houseTenureOccupied_file_path = join(DIRECTORY_SEPARATOR, array($houseTenureOccupied_file_dir, $houseTenureOccupied_file_name));
	$houseTenureOccupied_all_file_path = join(DIRECTORY_SEPARATOR, array($houseTenureOccupied_file_dir, $houseTenureOccupied_all_file_name));
	$fp = fopen($houseTenureOccupied_file_path, "w");			
	fwrite($fp, $houseTenureOccupiedJson);
	fclose($fp);
	$fpp = fopen($houseTenureOccupied_all_file_path, "a");			
	$houseTenureOccupiedJson_trim = str_replace('[', '',$houseTenureOccupiedJson);
	$houseTenureOccupiedJson_trim = str_replace('}]', '},', $houseTenureOccupiedJson_trim);
	fwrite($fpp, $houseTenureOccupiedJson_trim);
	fclose($fpp);
	//******* 15. ContractRent*******************************	
	$contractRentHeader   = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',	
		'COST' => 'integer'	,
		'Number' => 'integer'			
		);

	$contractRentSql = "select geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array[ 'Total Units' ,
				'Less than $500' ,
				'$500 to $599' ,
				'$600 to $699' ,
				'$700 to $799',
				'$800 to $899',
				'$900 to $999',
				'$1,000 to $1,249',
				'$1,250 to $1,499' ,
				'$1,500 to $1,999' ,
				'$2,000 or more' ,
				'No cash rent' 
				 ]) AS Cost ,
			unnest(array[hh_rent ,
				hh_rent_lt500 ,
				hh_rent_500to599,
				hh_rent_600to699 ,
				hh_rent_700to799 ,
				hh_rent_800to899,
				hh_rent_900to999,
				hh_rent_1000to1249,
				hh_rent_1250to1499,
				hh_rent_1500to1999,
				hh_rent_2000plus,
				hh_rent_nocash
				]) as Number	
	
			from app.acs_rent( $1, $2, $3);";
	//$contractRentArray = Query::getInstance()->getResultAsArray($contractRentSql, null, $geotype,  $zone);
	$contractRentJson = Query::getInstance()->getResultAsJson( $contractRentSql, 9, $geotype, $zone);
	$contractRent_file_name = strtolower(join("_", array('contractRent', "censusacs", $year, $geotype, $zone)).".json");
	$contractRent_all_file_name = strtolower(join("_", array('contractRent', "censusacs", $year, $geotype, "all")).".json");
	$contractRent_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$contractRent_file_path = join(DIRECTORY_SEPARATOR, array($contractRent_file_dir, $contractRent_file_name));
	$contractRent_all_file_path = join(DIRECTORY_SEPARATOR, array($contractRent_file_dir, $contractRent_all_file_name));
	$fp = fopen($contractRent_file_path, "w");			
	fwrite($fp, $contractRentJson);
	fclose($fp);
	$fpp = fopen($contractRent_all_file_path, "a");			
	$contractRentJson_trim = str_replace('[', '',$contractRentJson);
	$contractRentJson_trim = str_replace('}]', '},', $contractRentJson_trim);
	fwrite($fpp, $contractRentJson_trim);
	fclose($fpp);
	// need median contract rent
	$contractRentMedianSql = "select geography_zone {$geotype} ,{$year} Yearnumber,
		'Median contract rent' mediancontractrent,
		median_contract_rent
		from app.acs_median_contract_rent( $1, $2, $3);";
	$contractRentMedianJson = Query::getInstance()->getResultAsJson( $contractRentMedianSql, 9, $geotype, $zone);
	$contractRentMedian_file_name = strtolower(join("_", array('contractRentMedian', "censusacs", $year, $geotype, $zone)).".json");
	$contractRentMedian_all_file_name = strtolower(join("_", array('contractRentMedian', "censusacs", $year, $geotype, "all")).".json");
	$contractRentMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$contractRentMedian_file_path = join(DIRECTORY_SEPARATOR, array($contractRentMedian_file_dir, $contractRentMedian_file_name));
	$contractRentMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($contractRentMedian_file_dir, $contractRentMedian_all_file_name));
	$fp = fopen($contractRentMedian_file_path, "w");			
	fwrite($fp, $contractRentMedianJson);
	fclose($fp);
	$fpp = fopen($contractRentMedian_all_file_path, "a");			
	$contractRentMedianJson_trim = str_replace('[', '',$contractRentMedianJson);
	$contractRentMedianJson_trim = str_replace('}]', '},', $contractRentMedianJson_trim);
	fwrite($fpp, $contractRentMedianJson_trim);
	fclose($fpp);	
	//******* 16. Gross Rent Household Income*********
	$grossRentHouseholdIncomeHeader  = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'PERCENT' => 'string',
		'NUMBER' => 'integer'
		);

	$grossRentHouseholdIncomeSql = "select geography_zone {$geotype}, {$year} Yearnumber,
			unnest(array[   'Total',
					'Less than 20 percent',
					'20.0 to 24.9 percent',
					'25.0 to 29.9 percent',
					'30.0 to 34.9 percent',
					'35.0 to 39.9 percent',
					'40.0 to 49.9 percent',
					'50.0 percent or more',
					'Not Computed'		
				 ]) AS Percentage,
			unnest(array[	hh_rent,
					hh_rent_income_lt20pct,
					hh_rent_income_20to25pct,
					hh_rent_income_25to30pct,
					hh_rent_income_30to35pct,
					hh_rent_income_35to40pct,
					hh_rent_income_40to50pct,
					hh_rent_income_50pct_plus,
					hh_rent-hh_rent_income_lt20pct-hh_rent_income_20to25pct-hh_rent_income_25to30pct-hh_rent_income_30to35pct-hh_rent_income_35to40pct-hh_rent_income_40to50pct-hh_rent_income_50pct_plus
					]) AS Number		
			from app.acs_gross_rent_percentage_income( $1, $2, $3);";
	//$grossRentHouseholdIncomeArray = Query::getInstance()->getResultAsArray($grossRentHouseholdIncomeSql, null, $geotype, $zone);
	$grossRentHouseholdIncomeJson = Query::getInstance()->getResultAsJson( $grossRentHouseholdIncomeSql, 9, $geotype, $zone);
	$grossRentHouseholdIncome_file_name = strtolower(join("_", array('grossRentHouseholdIncome', "censusacs", $year, $geotype, $zone)).".json");
	$grossRentHouseholdIncome_all_file_name = strtolower(join("_", array('grossRentHouseholdIncome', "censusacs", $year, $geotype, "all")).".json");
	$grossRentHouseholdIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$grossRentHouseholdIncome_file_path = join(DIRECTORY_SEPARATOR, array($grossRentHouseholdIncome_file_dir, $grossRentHouseholdIncome_file_name));
	$grossRentHouseholdIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($grossRentHouseholdIncome_file_dir, $grossRentHouseholdIncome_all_file_name));
	$fp = fopen($grossRentHouseholdIncome_file_path, "w");			
	fwrite($fp, $grossRentHouseholdIncomeJson);
	fclose($fp);
	$fpp = fopen($grossRentHouseholdIncome_all_file_path, "a");			
	$grossRentHouseholdIncomeJson_trim = str_replace('[', '',$grossRentHouseholdIncomeJson);
	$grossRentHouseholdIncomeJson_trim = str_replace('}]', '},', $grossRentHouseholdIncomeJson_trim);
	fwrite($fpp, $grossRentHouseholdIncomeJson_trim);
	fclose($fpp);
	
	//******* 17. Vehicle Availability*********
	$vehicleAvailabilityHeader= array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'AVAILABILITY' => 'string',
		'NUMBER' => 'integer'
		);

	$vehicleAvailabilitySql = "select geography_zone  {$geotype} ,{$year} Yearnumber,
			unnest(array[  'No vehicle' ,
				 '1 vehicle' ,
				 '2 vehicles' ,
				'3 vehicles' ,
				'4 or more vehicles'
				 ]) AS Availability ,
			unnest(array[hh_no_auto ,
				hh_auto_1 ,
				hh_auto_2 ,
				hh_auto_3 ,
				hh_auto_4plus	
				]) as Number		
			from app.acs_vehicle_availability( $1, $2, $3 );";
	//$vehicleAvailabilityArray = Query::getInstance()->getResultAsArray($vehicleAvailabilitySql, null, $geotype,  $zone);
	$vehicleAvailabilityJson = Query::getInstance()->getResultAsJson( $vehicleAvailabilitySql, 5, $geotype, $zone);
	$vehicleAvailability_file_name = strtolower(join("_", array('vehicleAvailability', "censusacs", $year, $geotype, $zone)).".json");
	$vehicleAvailability_all_file_name = strtolower(join("_", array('vehicleAvailability', "censusacs", $year, $geotype, "all")).".json");
	$vehicleAvailability_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$vehicleAvailability_file_path = join(DIRECTORY_SEPARATOR, array($vehicleAvailability_file_dir, $vehicleAvailability_file_name));
	$vehicleAvailability_all_file_path = join(DIRECTORY_SEPARATOR, array($vehicleAvailability_file_dir, $vehicleAvailability_all_file_name));
	$fp = fopen($vehicleAvailability_file_path, "w");			
	fwrite($fp, $vehicleAvailabilityJson);
	fclose($fp);
	$fpp = fopen($vehicleAvailability_all_file_path, "a");			
	$vehicleAvailabilityJson_trim = str_replace('[', '',$vehicleAvailabilityJson);
	$vehicleAvailabilityJson_trim = str_replace('}]', '},', $vehicleAvailabilityJson_trim);
	fwrite($fpp, $vehicleAvailabilityJson_trim);
	fclose($fpp);
	
	//******* 18 Place of work( CountyLevel) **************
	$placeofWorkHeader= array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'PLACE OF WORK' => 'string',
		'NUMBER' => 'integer'
		);

	$placeofWorkSql = "select  geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array[ 'Worked in state of residence',
				'Worked in county of residence',
				'Worked outside county of residence',
				'Worked outside state of residence' 
				 ]) AS placeofwork,	
			unnest(array[emp_place_in_state,
			emp_place_in_state_in_county,
			emp_place_in_state_out_county,
			emp_place_out_state
	
			]) as Number
			from app.acs_place_of_work($1, $2, $3);";
	//$placeofWorkArray = Query::getInstance()->getResultAsArray($placeofWorkSql, null, $geotype,  $zone);
	$placeofWorkJson = Query::getInstance()->getResultAsJson( $placeofWorkSql, 9, $geotype, $zone);
	$placeofWork_file_name = strtolower(join("_", array('placeofWork', "censusacs", $year, $geotype, $zone)).".json");
	$placeofWork_all_file_name = strtolower(join("_", array('placeofWork', "censusacs", $year, $geotype, "all")).".json");
	$placeofWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$placeofWork_file_path = join(DIRECTORY_SEPARATOR, array($placeofWork_file_dir, $placeofWork_file_name));
	$placeofWork_all_file_path = join(DIRECTORY_SEPARATOR, array($placeofWork_file_dir, $placeofWork_all_file_name));
	$fp = fopen($placeofWork_file_path, "w");			
	fwrite($fp, $placeofWorkJson);
	fclose($fp);
	$fpp = fopen($placeofWork_all_file_path, "a");			
	$placeofWorkJson_trim = str_replace('[', '',$placeofWorkJson);
	$placeofWorkJson_trim = str_replace('}]', '},', $placeofWorkJson_trim);
	fwrite($fpp, $placeofWorkJson_trim);
	fclose($fpp);
	
	//******* 19 Transportation to Work **************
	$transportationtoWorkHeader= array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'MEANS OF TRANSPORTATION' => 'string',
		'NUMBER' => 'integer'
		);

	$transportationtoWorkSql = " select  geography_zone {$geotype} ,{$year} Yearnumber,
					unnest(array['Car, truck or van',
						'Drove Alone',
						'Carpooled',
						'Public Transportation' ,
						'Bus', 
						'Trolley / Streetcar',
						'Railroad', 
						'Other public transportation' ,
						'Motorcycle' ,
						'Bicycle',
						'Walked',
						'Other means',
						'Worked at home' ]) AS means_of_trans,	
					unnest(array[emp_mode_auto,
					emp_mode_auto_drive_alone,
					emp_mode_auto_carpool,
					emp_mode_transit,
					emp_mode_transit_bus, 
					emp_mode_transit_trolley,
					emp_mode_transit_rail,
					emp_mode_transit_others,
					emp_mode_motor,
					emp_mode_bike,
					emp_mode_walk,
					emp_mode_others,
					emp_mode_home
					]) as Number
					from app.acs_means_of_trans($1, $2, $3 );";
	//$transporationtoWorkArray = Query::getInstance()->getResultAsArray($transporationtoWorkSql, null, $geotype,  $zone);
	$transportationtoWorkJson = Query::getInstance()->getResultAsJson( $transportationtoWorkSql, 9, $geotype, $zone);
	$transportationtoWork_file_name = strtolower(join("_", array('transportationtoWork', "censusacs", $year, $geotype, $zone)).".json");
	$transportationtoWork_all_file_name = strtolower(join("_", array('transportationtoWork', "censusacs", $year, $geotype, "all")).".json");
	$transportationtoWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$transportationtoWork_file_path = join(DIRECTORY_SEPARATOR, array($transportationtoWork_file_dir, $transportationtoWork_file_name));
	$transportationtoWork_all_file_path = join(DIRECTORY_SEPARATOR, array($transportationtoWork_file_dir, $transportationtoWork_all_file_name));
	$fp = fopen($transportationtoWork_file_path, "w");			
	fwrite($fp, $transportationtoWorkJson);
	fclose($fp);
	$fpp = fopen($transportationtoWork_all_file_path, "a");			
	$transportationtoWorkJson_trim = str_replace('[', '',$transportationtoWorkJson);
	$transportationtoWorkJson_trim = str_replace('}]', '},', $transportationtoWorkJson_trim);
	fwrite($fpp, $transportationtoWorkJson_trim);
	fclose($fpp);
	
	//******* 20 Travel Time to Work **************
	$travelTimetoWorkHeader= array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'TRAVEL TIME TO WORK' => 'string',
		'NUMBER' => 'integer'
		);

	$travelTimetoWorkSql = "select geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array['Did not work at home',
			'Less than 10 minutes',
			'10 to 19 minutes' ,
			'20 to 29 minutes',
			'30 to 44 minutes',
			'45 to 59 minutes',
			'60 to 89 minutes',
			'90 minutes or more',
			 'Worked at home' ]) AS TravelTime,	
			unnest(array[emp_travel_no_home,
			emp_travel_no_home_lt10min,
			emp_travel_no_home_10to19min,
			emp_travel_no_home_20to29min,
			emp_travel_no_home_30to44min,
			emp_travel_no_home_45to59min,
			emp_travel_no_home_60to89min,
			emp_travel_no_home_90plusmin,
			emp_mode_home
			]) as Number
			from app.acs_travel_time( $1, $2, $3 );";
	
	//*UNION ALL
	//select  geography_zone as {$geotype} ,{$year} as Yearnumber, 'Average time to work' as TravelTime,
	//* from app.acs_avg_travel_time_to_work( 9, :geotype, :zonelist) ;";
	//*/
	//$travelTimetoWorkArray = Query::getInstance()->getResultAsArray($travelTimetoWorkSql2, null, $geotype, $zone);
	$travelTimetoWorkJson = Query::getInstance()->getResultAsJson( $travelTimetoWorkSql, 9, $geotype, $zone);
	$travelTimetoWork_file_name = strtolower(join("_", array('travelTimetoWork', "censusacs", $year, $geotype, $zone)).".json");
	$travelTimetoWork_all_file_name = strtolower(join("_", array('travelTimetoWork', "censusacs", $year, $geotype, "all")).".json");
	$travelTimetoWork_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$travelTimetoWork_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWork_file_dir, $travelTimetoWork_file_name));
	$travelTimetoWork_all_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWork_file_dir, $travelTimetoWork_all_file_name));
	$fp = fopen($travelTimetoWork_file_path, "w");			
	fwrite($fp, $travelTimetoWorkJson);
	fclose($fp);
	$fpp = fopen($travelTimetoWork_all_file_path, "a");			
	$travelTimetoWorkJson_trim = str_replace('[', '',$travelTimetoWorkJson);
	$travelTimetoWorkJson_trim = str_replace('}]', '},', $travelTimetoWorkJson_trim);
	fwrite($fpp, $travelTimetoWorkJson_trim);
	fclose($fpp);
	
	
	$travelTimetoWorkAverageSql = "select  '$zone' as {$geotype} ,{$year} as Yearnumber, 
			'Average time to work' as AverageTimeToWork,
			acs_avg_travel_time_to_work 
			from app.acs_avg_travel_time_to_work( $1, $2, $3 );";
			
	$travelTimetoWorkAverageJson = Query::getInstance()->getResultAsJson( $travelTimetoWorkAverageSql, 9, $geotype, $zone);
	$travelTimetoWorkAverage_file_name = strtolower(join("_", array('travelTimetoWorkAverage', "censusacs", $year, $geotype, $zone)).".json");
	$travelTimetoWorkAverage_all_file_name = strtolower(join("_", array('travelTimetoWorkAverage', "censusacs", $year, $geotype, "all")).".json");
	$travelTimetoWorkAverage_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$travelTimetoWorkAverage_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWorkAverage_file_dir, $travelTimetoWorkAverage_file_name));
	$travelTimetoWorkAverage_all_file_path = join(DIRECTORY_SEPARATOR, array($travelTimetoWorkAverage_file_dir, $travelTimetoWorkAverage_all_file_name));
	$fp = fopen($travelTimetoWorkAverage_file_path, "w");			
	fwrite($fp, $travelTimetoWorkAverageJson);
	fclose($fp);
	$fpp = fopen($travelTimetoWorkAverage_all_file_path, "a");			
	$travelTimetoWorkAverageJson_trim = str_replace('[', '',$travelTimetoWorkAverageJson);
	$travelTimetoWorkAverageJson_trim = str_replace('}]', '},', $travelTimetoWorkAverageJson_trim);
	fwrite($fpp, $travelTimetoWorkAverageJson_trim);
	fclose($fpp);
	
	//******* 21 Employment Status **************
	$employmentStatusHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'STATUS' => 'string',
		'MALE' => 'integer' ,
		'FEMALE' => 'integer' );
	$employmentStatusSql = "select '$zone' as {$geotype} ,{$year}  Yearnumber,
			unnest( array[ 'Population age 16 and older',
				'In labor force',
				'Armed forces',
				'Civilian(employed)',
				'Civilian(unemployed)',
				'Not in labor force'])
				as Status,			
			unnest(	array[ pop_16plus_male,
				in_labor_force_male,
				in_armed_forces_male,
				emp_civ_male,
				unemployed_male,
				not_in_labor_force_male])	
				as male,
			unnest(	array[ pop_16plus_female,
				in_labor_force_female,
				in_armed_forces_female,
				emp_civ_female,
				unemployed_female,
				not_in_labor_force_female])	
				as female
			from app.acs_employment_status_acs( $1, $2, $3);";
	$employmentStatusJson = Query::getInstance()->getResultAsJson( $employmentStatusSql, 9, $geotype, $zone);
	$employmentStatus_file_name = strtolower(join("_", array('employmentStatus', "censusacs", $year, $geotype, $zone)).".json");
	$employmentStatus_all_file_name = strtolower(join("_", array('employmentStatus', "censusacs", $year, $geotype, "all")).".json");
	$employmentStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$employmentStatus_file_path = join(DIRECTORY_SEPARATOR, array($employmentStatus_file_dir, $employmentStatus_file_name));
	$employmentStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($employmentStatus_file_dir, $employmentStatus_all_file_name));
	$fp = fopen($employmentStatus_file_path, "w");			
	fwrite($fp, $employmentStatusJson);
	fclose($fp);	
	$fpp = fopen($employmentStatus_all_file_path, "a");			
	$employmentStatusJson_trim = str_replace('[', '',$employmentStatusJson);
	$employmentStatusJson_trim = str_replace('}]', '},', $employmentStatusJson_trim);
	fwrite($fpp, $employmentStatusJson_trim);
	fclose($fpp);	
	
	//******* 22 Occupation **************
	$occupationHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'OCCUPATION' => 'string',
		'NUMBER' => 'integer' );
	
	$occupationSql = "select geography_zone {$geotype} ,{$year}  Yearnumber,
				unnest(array['Total employed civilian age 16+',
				'Management, professional & related',
				'Management (incl. farm managers)',
				'Business & financial',
				'Computer & mathematical',
				'Architecture & engineering',
				'Life, physical & social science',
				'Community & social service',
				'Legal',
				'Education, training, & library',
				'Art, entertainment, sports & media',
				'Healthcare practitioners',
				'Service',
				'Healthcare support',
				'Protective serice',
				'Food preparation & serving',
				'Building & ground cleaning/maintenance',
				'Personal care & service',
				'Sales & office',
				'Farming, fishing & forestry',
				'Construction, extraction & maintenance',
				'Production, transport & material moving'	
				]) as occupation,
				unnest(array[emp_occu,
				emp_occu_mgmt_prof,
				emp_occu_mgmt_prof_management,
				emp_occu_mgmt_prof_business,
				emp_occu_mgmt_prof_computer,
				emp_occu_mgmt_prof_engineer,
				emp_occu_mgmt_prof_science,
				emp_occu_mgmt_prof_service,
				emp_occu_mgmt_prof_legal,
				emp_occu_mgmt_prof_education,
				emp_occu_mgmt_prof_art,
				emp_occu_mgmt_prof_health,
				emp_occu_service,
				emp_occu_service_health,
				emp_occu_service_protective,
				emp_occu_service_food,
				emp_occu_service_maint,
				emp_occu_service_person,
				emp_occu_saleoffice,
				emp_occu_farmfishing,
				emp_occu_construction,
				emp_occu_transport
				]) as total
				from app.acs_occupation( $1, $2, $3 );";
	//$occupationArray = Query::getInstance()->getResultAsArray($occupationSql, null, $geotype, $zone);
	$occupationJson = Query::getInstance()->getResultAsJson( $occupationSql, 2, $geotype, $zone);
	$occupation_file_name = strtolower(join("_", array('occupation', "censusacs", $year, $geotype, $zone)).".json");
	$occupation_all_file_name = strtolower(join("_", array('occupation', "censusacs", $year, $geotype, "all")).".json");
	$occupation_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$occupation_file_path = join(DIRECTORY_SEPARATOR, array($occupation_file_dir, $occupation_file_name));
	$occupation_all_file_path = join(DIRECTORY_SEPARATOR, array($occupation_file_dir, $occupation_all_file_name));
	$fp = fopen($occupation_file_path, "w");			
	fwrite($fp, $occupationJson);
	fclose($fp);
	$fpp = fopen($occupation_all_file_path, "a");			
	$occupationJson_trim = str_replace('[', '',$occupationJson);
	$occupationJson_trim = str_replace('}]', '},', $occupationJson_trim);
	fwrite($fpp, $occupationJson_trim);
	fclose($fpp);
	
	//******* 23 Industry **************
	$industryHeader = array( 
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'INDUSTRY' => 'string',
		'NUMBER' => 'integer'
		);
	$industrySql = " select geography_zone {$geotype} ,{$year} Yearnumber,
			unnest(array[  'Agriculture, forestry, mining',
					'Utilities',
					'Construction',
					'Manufacturing',
					'Wholesale trade',
					'Retail trade',
					'Transportation & warehousing',
					'Information & communications',
					'Finance, insurance, & real estate',
					'Professional, scientific, management, administration',
					'Educational, social, & health services',
					'Art, entertainment, recreation., accommodations, food',
					'Other services',
							'Public administration'
				 ]) AS Industry,
			unnest(array[emp_indu_agriculture ,
				emp_indu_utilities,
				emp_indu_construnction,
				emp_indu_manufacturing,
				emp_indu_wholesale,
				emp_indu_retail,
				emp_indu_transport,
				emp_indu_communication,
				emp_indu_finance,
				emp_indu_professional,
				emp_indu_education,
				emp_indu_art,
				emp_indu_other_services,
				emp_indu_public		
				]) as Number			
			from app.acs_industry( $1, $2, $3 );";
	
	//$industryArray = Query::getInstance()->getResultAsArray($industrySql, null, $geotype, $zone);
	$industryJson = Query::getInstance()->getResultAsJson( $industrySql, 5, $geotype, $zone);
	$industry_file_name = strtolower(join("_", array('industry', "censusacs", $year, $geotype, $zone)).".json");
	$industry_all_file_name = strtolower(join("_", array('industry', "censusacs", $year, $geotype, "all")).".json");
	$industry_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$industry_file_path = join(DIRECTORY_SEPARATOR, array($industry_file_dir, $industry_file_name));
	$industry_all_file_path = join(DIRECTORY_SEPARATOR, array($industry_file_dir, $industry_all_file_name));
	$fp = fopen($industry_file_path, "w");			
	fwrite($fp, $industryJson);
	fclose($fp);
	$fpp = fopen($industry_all_file_path, "a");			
	$industryJson_trim = str_replace('[', '',$industryJson);
	$industryJson_trim = str_replace('}]', '},', $industryJson_trim);
	fwrite($fpp, $industryJson_trim);
	fclose($fpp);
	
	
	//******* 24 Household Income **************
	$householdIncomeHeader =  array( 
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'INCOME' => 'string',
		'NUMBER' => 'integer'
		);
	$householdIncomeSql = " SELECT geozone {$geotype}, {$year} Yearnumber,
				income_group_name, households
				from app.acs_household_income($1, $2, $3);";
	
	
	//$householdIncomeArray = Query::getInstance()->getResultAsArray($householdIncomeSql, null, $geotype, $zone);
	$householdIncomeJson = Query::getInstance()->getResultAsJson( $householdIncomeSql, 5, $geotype, $zone);
	$householdIncome_file_name = strtolower(join("_", array('householdIncome', "censusacs", $year, $geotype, $zone)).".json");
	$householdIncome_all_file_name = strtolower(join("_", array('householdIncome', "censusacs", $year, $geotype, "all")).".json");
	$householdIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdIncome_file_path = join(DIRECTORY_SEPARATOR, array($householdIncome_file_dir, $householdIncome_file_name));
	$householdIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($householdIncome_file_dir, $householdIncome_all_file_name));
	$fp = fopen($householdIncome_file_path, "w");			
	fwrite($fp, $householdIncomeJson);
	fclose($fp);
	$fpp = fopen($householdIncome_all_file_path, "a");			
	$householdIncomeJson_trim = str_replace('[', '',$householdIncomeJson);
	$householdIncomeJson_trim = str_replace('}]', '},', $householdIncomeJson_trim);
	fwrite($fpp, $householdIncomeJson_trim);
	fclose($fpp);
	
	// median income
	$householdIncomeMedianSql = "SELECT geozone {$geotype}, {$year} Yearnumber,
				'median income' medianincome,
				median_inc
				from app.acs_household_income_median( $1, $2, $3 );";
	$householdIncomeMedianJson = Query::getInstance()->getResultAsJson( $householdIncomeMedianSql, 5, $geotype, $zone);
	$householdIncomeMedian_file_name = strtolower(join("_", array('householdIncomeMedian', "censusacs", $year, $geotype, $zone)).".json");
	$householdIncomeMedian_all_file_name = strtolower(join("_", array('householdIncomeMedian', "censusacs", $year, $geotype, "all")).".json");
	$householdIncomeMedian_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$householdIncomeMedian_file_path = join(DIRECTORY_SEPARATOR, array($householdIncomeMedian_file_dir, $householdIncomeMedian_file_name));
	$householdIncomeMedian_all_file_path = join(DIRECTORY_SEPARATOR, array($householdIncomeMedian_file_dir, $householdIncomeMedian_all_file_name));
	$fp = fopen($householdIncomeMedian_file_path, "w");			
	fwrite($fp, $householdIncomeMedianJson);
	fclose($fp);			
	$fpp = fopen($householdIncomeMedian_all_file_path, "a");			
	$householdIncomeMedianJson_trim = str_replace('[', '',$householdIncomeMedianJson);
	$householdIncomeMedianJson_trim = str_replace('}]', '},', $householdIncomeMedianJson_trim);
	fwrite($fpp, $householdIncomeMedianJson_trim);
	fclose($fpp);
	//******* 25 Earnings and Income **************
	$earningsAndIncomeHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'HOUSEHOLDS' => 'string',
		'NUMBER' => 'integer'
		);
	$earningsAndIncomeSql = "select geography_zone  {$geotype}, {$year} Yearnumber,
				unnest(array[   'With earnings',
						'With wage/salary income',
						'With self-employment income',
						'With interest, dividend, or net rental income',
						'With Social Security income',
						'With Supplemental Security income',
						'With public assistance income',
						'With retirement income',
						'With other types of income'
					 ]) AS Households,
				unnest(array[hh_inc_earning ,
					hh_inc_wage,
					hh_inc_self_emp,
					hh_inc_interest,
					hh_inc_social,
					hh_inc_supplemental,
					hh_inc_public,
					hh_inc_retirement,
					hh_inc_other	
					]) as Number			
				from app.acs_earnings_income( $1, $2, $3 );";
	//$earningsAndIncomeArray = Query::getInstance()->getResultAsArray($earningsAndIncomeSql, null, $geotype, $zone);
	$earningsAndIncomeJson = Query::getInstance()->getResultAsJson( $earningsAndIncomeSql, 9, $geotype, $zone);
	$earningsAndIncome_file_name = strtolower(join("_", array('earningsAndIncome', "censusacs", $year, $geotype, $zone)).".json");
	$earningsAndIncome_all_file_name = strtolower(join("_", array('earningsAndIncome', "censusacs", $year, $geotype, "all")).".json");
	$earningsAndIncome_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$earningsAndIncome_file_path = join(DIRECTORY_SEPARATOR, array($earningsAndIncome_file_dir, $earningsAndIncome_file_name));
	$earningsAndIncome_all_file_path = join(DIRECTORY_SEPARATOR, array($earningsAndIncome_file_dir, $earningsAndIncome_all_file_name));
	$fp = fopen($earningsAndIncome_file_path, "w");			
	fwrite($fp, $earningsAndIncomeJson);
	fclose($fp);
	$fpp = fopen($earningsAndIncome_all_file_path, "a");			
	$earningsAndIncomeJson_trim = str_replace('[', '',$earningsAndIncomeJson);
	$earningsAndIncomeJson_trim = str_replace('}]', '},', $earningsAndIncomeJson_trim);
	fwrite($fpp, $earningsAndIncomeJson_trim);
	fclose($fpp);
	
	//******* 26 Ratio Income to Poverty Level **************
	$ratioIncomePovertyLevelHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'RATIO' => 'string',
		'POPULATION' => 'integer');
	$ratioIncomePovertyLevelSql = "select geography_zone  {$geotype}, {$year} Yearnumber,
			unnest(array[   'Under .50',
					'.50 to .99',
					'1.00 to 1.24',
					'1.25 to 1.49',
					'1.50 to 1.84',
					'1.85 to 1.99',
					'2.00 and over'			
				 ]) AS Households,
			unnest(array[pop_poverty_lt0dot5 ,
				pop_poverty_0dot5to0dot99,
				pop_poverty_1to1dot24,
				pop_poverty_1dot25to1dot49,
				pop_poverty_1dot5to1dot84,
				pop_poverty_1dot85to1dot99,
				pop_poverty_2plus
				]) as Number			
			from app.acs_income_poverty_lvl( $1, $2, $3 );";
	//$ratioIncomePovertyLevelArray = Query::getInstance()->getResultAsArray($ratioIncomePovertyLevelSql, null, $geotype, $zone);
	$ratioIncomePovertyLevelJson = Query::getInstance()->getResultAsJson( $ratioIncomePovertyLevelSql, 9, $geotype, $zone);
	$ratioIncomePovertyLevel_file_name = strtolower(join("_", array('ratioIncomePovertyLevel', "censusacs", $year, $geotype, $zone)).".json");
	$ratioIncomePovertyLevel_all_file_name = strtolower(join("_", array('ratioIncomePovertyLevel', "censusacs", $year, $geotype, "all")).".json");
	$ratioIncomePovertyLevel_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$ratioIncomePovertyLevel_file_path = join(DIRECTORY_SEPARATOR, array($ratioIncomePovertyLevel_file_dir, $ratioIncomePovertyLevel_file_name));
	$ratioIncomePovertyLevel_all_file_path = join(DIRECTORY_SEPARATOR, array($ratioIncomePovertyLevel_file_dir, $ratioIncomePovertyLevel_all_file_name));
	$fp = fopen($ratioIncomePovertyLevel_file_path, "w");			
	fwrite($fp, $ratioIncomePovertyLevelJson);
	fclose($fp);
	$fpp = fopen($ratioIncomePovertyLevel_all_file_path, "a");			
	$ratioIncomePovertyLevelJson_trim = str_replace('[', '',$ratioIncomePovertyLevelJson);
	$ratioIncomePovertyLevelJson_trim = str_replace('}]', '},', $ratioIncomePovertyLevelJson_trim);
	fwrite($fpp, $ratioIncomePovertyLevelJson_trim);
	fclose($fpp);
	
	//******* 27 Poverty Status **************
	$povertyStatusHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'STATUS' => 'string',
		'NUMBER' => 'integer'
		);
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
	//$povertyStatusArray = Query::getInstance()->getResultAsArray($povertyStatusSql, null, $geotype, $zone);
	$povertyStatusJson = Query::getInstance()->getResultAsJson( $povertyStatusSql, 9, $geotype, $zone);
	$povertyStatus_file_name = strtolower(join("_", array('povertyStatus', "censusacs", $year, $geotype, $zone)).".json");
	$povertyStatus_all_file_name = strtolower(join("_", array('povertyStatus', "censusacs", $year, $geotype, "all")).".json");
	$povertyStatus_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$povertyStatus_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatus_file_dir, $povertyStatus_file_name));
	$povertyStatus_all_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatus_file_dir, $povertyStatus_all_file_name));
	$fp = fopen($povertyStatus_file_path, "w");			
	fwrite($fp, $povertyStatusJson);
	fclose($fp);
	$fpp = fopen($povertyStatus_all_file_path, "a");			
	$povertyStatusJson_trim = str_replace('[', '',$povertyStatusJson);
	$povertyStatusJson_trim = str_replace('}]', '},', $povertyStatusJson_trim);
	fwrite($fpp, $povertyStatusJson_trim);
	fclose($fpp);
	
	//******* 28 PovertyStatus- FamType Children **************
	$povertyStatusByFamilyTypeHeader = array(
		strtoupper($geotype) => 'string',
		'YEAR' => 'string',
		'FAMILY TYPE' => 'string',
		'ABOVE POVERTY With Children Under 18' => 'string',
		'ABOVE POVERTY With No Child Under 18' => 'string',
		'BELOW POVERTY With Children Under 18'=> 'string',
		'BELOW POVERTY With No Child Under 18'=> 'string'
		);
	$povertyStatusByFamilyTypeSql = "select geography_zone {$geotype} ,{$year} Yearnumber,
				unnest(array[   'Married couple family',
						'Male householder no wife',
						'Female householder, no husband'
					 ]) AS familytype,
				unnest(array[	hh_fam_mar_u18_above_poverty,
						hh_fam_m_u18_above_poverty,
						hh_fam_f_u18_above_poverty
					]) AS abovePovertyWithChildrenUnder18 ,
				unnest(array[	hh_fam_mar_n18_above_poverty,
						hh_fam_m_n18_above_poverty,
						hh_fam_f_n18_above_poverty
					]) as  abovePovertyNoChildUnder18 ,
				unnest( array[	hh_fam_mar_u18_below_poverty,
						hh_fam_m_u18_below_poverty,
						hh_fam_f_u18_below_poverty
					]) AS  belowPovertyWithChildrenUnder18 ,
				unnest(array[	hh_fam_mar_n18_below_poverty,
						hh_fam_m_n18_below_poverty,
						hh_fam_f_n18_below_poverty
					]) as  belowPovertyNoChildUnder18			
				from app.acs_poverty_status_of_families($1, $2, $3 );";
	//$povertyStatusByFamilyTypeArray = Query::getInstance()->getResultAsArray($povertyStatusByFamilyTypeSql, null, $geotype, $zone);		
	$povertyStatusByFamilyTypeJson = Query::getInstance()->getResultAsJson( $povertyStatusByFamilyTypeSql, 5, $geotype, $zone);
	$povertyStatusByFamilyType_file_name = strtolower(join("_", array('povertyStatusByFamilyType', "censusacs", $year, $geotype, $zone)).".json");
	$povertyStatusByFamilyType_all_file_name = strtolower(join("_", array('povertyStatusByFamilyType', "censusacs", $year, $geotype, "all")).".json");
	$povertyStatusByFamilyType_file_dir = join('\\', array(".","api","json", "censusacs", $year, $geotype));
	$povertyStatusByFamilyType_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatusByFamilyType_file_dir, $povertyStatusByFamilyType_file_name));
	$povertyStatusByFamilyType_all_file_path = join(DIRECTORY_SEPARATOR, array($povertyStatusByFamilyType_file_dir, $povertyStatusByFamilyType_all_file_name));
	$fp = fopen($povertyStatusByFamilyType_file_path, "w");			
	fwrite($fp, $povertyStatusByFamilyTypeJson);
	fclose($fp);
	$fpp = fopen($povertyStatusByFamilyType_all_file_path, "a");			
	$povertyStatusByFamilyTypeJson_trim = str_replace('[', '',$povertyStatusByFamilyTypeJson);
	$povertyStatusByFamilyTypeJson_trim = str_replace('}]', '},', $povertyStatusByFamilyTypeJson_trim);
	fwrite($fpp, $povertyStatusByFamilyTypeJson_trim);
	fclose($fpp);
	
			return true;
			}
	
