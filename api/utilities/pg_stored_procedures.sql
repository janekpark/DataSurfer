CREATE EXTENSION app.tablefunc;

DROP FUNCTION IF EXISTS app.fn_age_profile(datasourceId int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_age_profile(datasourceId int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr smallint, sex varchar(6), group_10yr varchar(50), population numeric) AS $$
DECLARE
  query_field varchar;
  age_sql varchar;
BEGIN

CREATE TEMP TABLE age (yr smallint, sex_id smallint, age_group_id smallint, population bigint) ON COMMIT DROP;
CREATE TEMP TABLE crossTbl (yr smallint, sex_id smallint, sex varchar(6), age_group_id smallint, group_10yr varchar(10)) ON COMMIT DROP;

SELECT 
  CASE data_type 
    WHEN 'integer' THEN 'm.' || geography_name
    WHEN 'smallint' THEN 'm.' || geography_name
    WHEN 'bigint' THEN 'm.' || geography_name
    ELSE 'lower(m.' || geography_name || ')'
  END INTO query_field
FROM
  information_schema.columns
WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;

age_sql := format('
             INSERT INTO age
               SELECT 
                 ase.yr
                 ,ase.sex_id
                 ,ase.age_group_id
                 ,sum(ase.population) as population
               FROM
                 fact.age_sex_ethnicity_master ase
                 JOIN dim.mgra m on ase.mgra_id = m.mgra_id
               WHERE ase.datasource_id = $1 AND %1$s = $2 and ase.population > 0
               GROUP BY
                 ase.yr, ase.sex_id, ase.age_group_id;', query_field);

IF left(query_field,5) = 'lower' THEN
  EXECUTE age_sql USING datasourceId, geography_zone;
ELSE 
  EXECUTE age_sql USING datasourceId, cast(geography_zone as int);
END IF;

INSERT INTO crossTbl
  SELECT 
    f.yr
    ,s.sex_id
    ,s.sex
    ,a.age_group_id
    ,a.group_10yr
  FROM
    dim.forecast_year f, dim.sex s, dim.age_group a
  WHERE 
    f.datasource_id = datasourceId;

RETURN QUERY (
  SELECT
    c.yr
    ,c.sex
    ,c.group_10yr
    ,sum(coalesce(a.population, 0)) as population
  FROM
    crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id
  GROUP BY c.yr, c.sex, c.group_10yr
  UNION ALL
  SELECT
    c.yr
    ,c.sex
    ,'Total Population'
    ,sum(coalesce(a.population, 0)) as population
  FROM
    crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id
  GROUP BY c.yr, c.sex
  UNION ALL
  SELECT
    c.yr
    ,'Total'
    ,'Total Population'
    ,sum(coalesce(a.population, 0)) as population
  FROM
    crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id
  GROUP BY
    c.yr);

END;
$$ LANGUAGE plpgsql;


DROP FUNCTION IF EXISTS app.fn_ethnicity_profile(datasourceId int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_ethnicity_profile(datasourceId int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr smallint, ethnicity varchar(35), population bigint) AS $$
DECLARE
  return_sql varchar;
  query_field varchar;
  f_sql varchar;
BEGIN

CREATE TEMP TABLE f (ethnicity_id smallint, yr smallint, pop int) ON COMMIT DROP;
CREATE TEMP TABLE e (yr smallint, ethnicity_id smallint, short_name varchar(35)) ON COMMIT DROP;

SELECT 
  CASE data_type 
    WHEN 'integer' THEN 'm.' || geography_name
    WHEN 'smallint' THEN 'm.' || geography_name
    WHEN 'bigint' THEN 'm.' || geography_name
    ELSE 'lower(m.' || geography_name || ')'
  END INTO query_field
FROM
  information_schema.columns
WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;

f_sql := format('INSERT INTO f
                   SELECT 
                     ase.ethnicity_id
                     ,ase.yr
                     ,sum(ase.population) pop
                   FROM 
                     fact.age_sex_ethnicity_master ase 
                     LEFT JOIN dim.mgra m on ase.mgra_id = m.mgra_id 
                   WHERE ase.datasource_id = $1 AND %1$s = $2 and ase.population > 0
                   GROUP BY 
                     ase.ethnicity_id, ase.yr;', query_field);

IF left(query_field,5) = 'lower' THEN
  EXECUTE f_sql USING datasourceId, geography_zone;
ELSE 
  EXECUTE f_sql USING datasourceId, cast(geography_zone as int);
END IF;

INSERT INTO e
  SELECT 
    f.yr
    ,e.ethnicity_id
    ,e.short_name
  FROM
    dim.forecast_year f, dim.ethnicity e 
  WHERE f.datasource_id = datasourceId;

RETURN QUERY (
  SELECT
    e.yr
    ,e.short_name ethnicity
    ,coalesce(f.pop, 0) pop
  FROM
    e LEFT JOIN f ON e.yr = f.yr AND e.ethnicity_id = f.ethnicity_id 
  UNION ALL
  SELECT
    e.yr
    ,'Total Population'
    ,SUM(coalesce(f.pop, 0)) pop
  FROM
    e LEFT JOIN f ON e.yr = f.yr AND e.ethnicity_id = f.ethnicity_id 
  GROUP BY 
    e.yr);

END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS app.fn_housing_profile(datasource_id int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_housing_profile(datasource_id int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr smallint, unit_type varchar(35), units bigint, occupied bigint, unoccupied bigint, vacancy_rate float) AS $$
DECLARE
  return_sql varchar;
  query_field varchar;
BEGIN
  SELECT 
    CASE data_type 
      WHEN 'integer' THEN 'm.' || geography_name
      WHEN 'smallint' THEN 'm.' || geography_name
      WHEN 'bigint' THEN 'm.' || geography_name
      ELSE 'lower(m.' || geography_name || ')'
    END INTO query_field
  FROM information_schema.columns WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;

  return_sql := format('
      SELECT
      h.yr
      ,s.long_name as unit_type
      ,SUM(units) as units
      ,SUM(occupied) as occupied
      ,SUM(units - occupied) as unoccupied
      ,CASE
        WHEN SUM(units) = 0 THEN NULL 
        ELSE SUM(units - occupied) / CAST(SUM(units) as float) 
      END as vacancy_rate
    FROM
      fact.housing h
      JOIN dim.mgra m ON h.mgra_id = m.mgra_id
      JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id
    WHERE 
      h.datasource_id = $1 and %1$s = $2
    GROUP BY 
      h.yr, s.long_name
    UNION ALL
    SELECT 
      h.yr
      ,''Total Units'' as unit_type
      ,SUM(units) as units
      ,SUM(occupied) as occupied
      ,SUM(units - occupied) as unoccupied
      ,CASE 
        WHEN SUM(units) = 0 THEN NULL 
        ELSE SUM(units - occupied) / CAST(SUM(units) as float) 
      END as vacancy_rate
    FROM
      fact.housing h
      JOIN dim.mgra m ON h.mgra_id = m.mgra_id
      JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id
    WHERE 
      h.datasource_id = $1 and %1$s = $2
    GROUP BY
     h.yr
  ', query_field);
  
  IF left(query_field,5) = 'lower' THEN
    RETURN QUERY EXECUTE return_sql USING datasource_id, geography_zone;
  ELSE 
    RETURN QUERY EXECUTE return_sql USING datasource_id, cast(geography_zone as int);
  END IF;
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS app.fn_income_profile(datasource_id int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_income_profile(datasource_id int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr int, income_group varchar(50), households bigint) AS $$
DECLARE
  return_sql varchar;
  query_field varchar;
BEGIN
  SELECT 
    CASE data_type 
      WHEN 'integer' THEN 'm.' || geography_name
      WHEN 'smallint' THEN 'm.' || geography_name
      WHEN 'bigint' THEN 'm.' || geography_name
      ELSE 'lower(m.' || geography_name || ')'
    END INTO query_field
  FROM information_schema.columns WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;
  
  return_sql := format('
    SELECT 
      i.yr as year
      ,g.name as income_group
      ,sum(i.households) as households
    FROM 
      fact.household_income i
      JOIN dim.income_group g ON i.income_group_id = g.income_group_id
      JOIN dim.mgra m ON i.mgra_id = m.mgra_id
    WHERE 
      i.datasource_id = $1 and %1$s = $2
    GROUP BY 
      i.yr, g.income_group, g.name
    ORDER BY
      i.yr, g.income_group', query_field);
  
  IF left(query_field,5) = 'lower' THEN
    RETURN QUERY EXECUTE return_sql USING datasource_id, geography_zone;
  ELSE 
    RETURN QUERY EXECUTE return_sql USING datasource_id, cast(geography_zone as int);
  END IF;
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS app.fn_median_income(datasource_id int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_median_income(datasource_id int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr int, income int) AS $$
DECLARE
  return_sql varchar;
  query_field varchar;
BEGIN
  SELECT 
    CASE data_type 
      WHEN 'integer' THEN 'm.' || geography_name
      WHEN 'smallint' THEN 'm.' || geography_name
      WHEN 'bigint' THEN 'm.' || geography_name
      ELSE 'lower(m.' || geography_name || ')'
    END INTO query_field
  FROM information_schema.columns WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;

  return_sql := format('WITH num_hh AS (
    SELECT 
      i.yr
      ,SUM(i.households) as hh
    FROM 
      fact.household_income i
      INNER JOIN dim.mgra m ON m.mgra_id = i.mgra_id
    WHERE 
      datasource_id = $1 AND %1$s = $2
    GROUP BY i.yr)
  ,inc_dist AS (
    SELECT 
      i.yr
      ,i.income_group_id
      ,ig.lower_bound
      ,ig.upper_bound
      ,ig.upper_bound - ig.lower_bound + 1 as interval_width
      ,SUM(i.households) hh
    FROM 
      fact.household_income i
      INNER JOIN dim.mgra m ON m.mgra_id = i.mgra_id
      INNER JOIN dim.income_group ig ON i.income_group_id = ig.income_group_id
    WHERE 
      datasource_id = $1 AND %1$s = $2
    GROUP BY 
      i.yr,i.income_group_id,ig.lower_bound,ig.upper_bound)
  ,cum_dist AS (
    SELECT 
      ROW_NUMBER() OVER (PARTITION BY a.yr ORDER BY a.yr, a.income_group_id) as row_num
      ,a.yr
      ,a.income_group_id
      ,a.lower_bound
      ,a.upper_bound
      ,a.interval_width
      ,a.hh
      ,SUM(b.hh) as cum_sum
    FROM 
      inc_dist a
      INNER JOIN inc_dist b ON a.income_group_id >= b.income_group_id AND a.yr = b.yr
      INNER JOIN num_hh ON num_hh.yr = a.yr
    GROUP BY 
      a.yr,a.income_group_id,a.lower_bound,a.upper_bound,a.interval_width,a.hh,num_hh.hh
    HAVING 
      SUM(b.hh) > (num_hh.hh / 2.0))

  SELECT 
    cum_dist.yr
    ,CAST(ROUND((lower_bound + ((num_hh.hh / 2.0 - (cum_sum - cum_dist.hh)) / cum_dist.hh) * interval_width), 0) as INT) as median_inc
  FROM 
    cum_dist
    INNER JOIN num_hh ON num_hh.yr = cum_dist.yr AND cum_dist.row_num = 1
  ORDER BY 
    cum_dist.yr', query_field);
  
  IF left(query_field,5) = 'lower' THEN
    RETURN QUERY EXECUTE return_sql USING datasource_id, geography_zone;
  ELSE 
    RETURN QUERY EXECUTE return_sql USING datasource_id, cast(geography_zone as int);
  END IF;
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS app.fn_forecast_ethnicity_change(aDatasourceId int, aGeographyName varchar, aGeographyZone varchar);

CREATE FUNCTION app.fn_forecast_ethnicity_change(aDatasourceId int, aGeographyName varchar, aGeographyZone varchar)
RETURNS TABLE (ethnicity varchar(35), pct_chg_byear_to_2020 float, pct_chg_2020_to_2025 float,
               pct_chg_2025_to_2030 float, pct_chg_2030_to_2035 float, pct_chg_2035_to_2040 float,
               pct_chg_2040_to_2045 float, pct_chg_2045_to_2050 float, pct_chg_base_to_horizon float) AS $$
DECLARE 
BEGIN

CREATE TEMP TABLE p (ethnicity varchar(35), bYear float, p2020 float, p2025 float, p2030 float, p2035 float, p2040 float, p2045 float, hYear float) ON COMMIT DROP;

INSERT INTO p
  SELECT * FROM app.CROSSTAB(
    format('SELECT ethnicity, yr, population FROM app.fn_ethnicity_profile (%1$s, ''%2$s'', ''%3$s'') ORDER BY ethnicity, yr',aDatasourceId, aGeographyName, aGeographyZone)) as ct
  (ethnicity varchar(35), "bYear" bigint, "2020" bigint, "2025" bigint, "2030" bigint, "2035" bigint, "2040" bigint, "2045" bigint, "hYear" bigint);
  
RETURN QUERY (SELECT
  p.ethnicity
  ,CASE WHEN bYear > 0 THEN p2020 / bYear - 1 ELSE NULL END as pct_chg_byear_to_2020
  ,CASE WHEN p2020 > 0 THEN p2025 / p2020 - 1 ELSE NULL END as pct_chg_2020_to_2025
  ,CASE WHEN p2025 > 0 THEN p2030 / p2025 - 1 ELSE NULL END as pct_chg_2025_to_2030
  ,CASE WHEN p2030 > 0 THEN p2035 / p2030 - 1 ELSE NULL END as pct_chg_2030_to_2035
  ,CASE WHEN p2035 > 0 THEN p2040 / p2035 - 1 ELSE NULL END as pct_chg_2035_to_2040
  ,CASE WHEN p2040 > 0 THEN p2045 / p2040 - 1 ELSE NULL END as pct_chg_2040_to_2045
  ,CASE WHEN p2045 > 0 THEN hYear / p2045 - 1 ELSE NULL END as pct_chg_2045_to_2050
  ,CASE WHEN bYear > 0 THEN hYear / bYear - 1 ELSE NULL END as pct_chg_base_to_horizon
FROM p);
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS app.fn_jobs_profile(datasource_id int, geography_name varchar, geography_zone varchar);

CREATE FUNCTION app.fn_jobs_profile(datasource_id int, geography_name varchar, geography_zone varchar) 
RETURNS TABLE (yr smallint, category varchar, jobs bigint) AS $$
DECLARE
  query_field varchar;
  return_sql varchar;
BEGIN

SELECT 
  CASE data_type 
    WHEN 'integer' THEN 'm.' || geography_name
    WHEN 'smallint' THEN 'm.' || geography_name
    WHEN 'bigint' THEN 'm.' || geography_name
    ELSE 'lower(m.' || geography_name || ')'
  END INTO query_field
FROM
  information_schema.columns
WHERE table_name = 'mgra' and table_schema = 'dim' and column_name = geography_name;

return_sql := format('SELECT 
                 j.yr
                 ,e.full_name as category
                 ,sum(j.jobs) as jobs
               FROM
                 fact.jobs j
                 LEFT JOIN dim.mgra m ON j.mgra_id = m.mgra_id
                 LEFT JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id
               WHERE
                 j.datasource_id = $1 and %1$s = $2 AND e.civilian = TRUE
               GROUP BY
                 j.yr, e.full_name
               UNION ALL
               SELECT 
                 j.yr
                 ,''Total Civilian Jobs''
                 ,sum(j.jobs) as jobs
               FROM
                 fact.jobs j
                 LEFT JOIN dim.mgra m ON j.mgra_id = m.mgra_id
                 LEFT JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id
               WHERE
                 j.datasource_id = $1 and %1$s = $2 AND e.civilian = TRUE
               GROUP BY j.yr', query_field);

  IF left(query_field,5) = 'lower' THEN
    RETURN QUERY EXECUTE return_sql USING datasource_id, geography_zone;
  ELSE 
    RETURN QUERY EXECUTE return_sql USING datasource_id, cast(geography_zone as int);
  END IF;

END;
$$ LANGUAGE plpgsql;


/*SELECT 
  m.city_name as zone
  ,ase.yr
  ,e.long_name as ethnicity
  ,s.sex
  ,a.group_10yr as age_group
  ,sum(ase.population) as population
FROM 
  fact.age_sex_ethnicity ase
    JOIN dim.mgra m on ase.mgra_id = m.mgra_id
    JOIN dim.age_group a on ase.age_group_id = a.age_group_id
    JOIN dim.ethnicity e on ase.ethnicity_id = e.ethnicity_id
    JOIN dim.sex s on ase.sex_id = s.sex_id
  WHERE 
    ase.datasource_id = 14 and lower(m.city_name) in ('la mesa', 'el cajon')
  GROUP BY 
    m.city_name, ase.yr, e.long_name, s.sex, a.group_10yr*/

/*HOUSING*/
/*
SELECT 
  m.city_name as jurisdiction
  , h.yr
  , s.long_name as unit_type
  , SUM(units) as units
  , SUM(occupied) as occupied
  , SUM(units - occupied) as unoccupied
  , CASE WHEN SUM(units) = 0 THEN NULL ELSE SUM(units - occupied) / CAST(SUM(units) as float) END as vacancy_rate
FROM fact.housing h 
  LEFT JOIN dim.mgra m ON h.mgra_id = m.mgra_id
  LEFT JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id 
WHERE
  h.datasource_id = 14 and lower(m.city_name) in ('la mesa', 'el cajon')
GROUP BY
  m.city_name, h.yr, s.long_name 
ORDER BY
  m.city_name, h.yr, s.long_name
*/

/*POPULATION*/
/*
SELECT 
  p.yr
  ,m.city_name as jurisdiction
  ,h.long_name
  ,sum(p.population) as population
FROM 
  fact.population p
    LEFT JOIN dim.mgra m on p.mgra_id = m.mgra_id
    LEFT JOIN dim.housing_type h on p.housing_type_id = h.housing_type_id
WHERE 
  p.datasource_id = 13  and lower(m.city_name) in ('carlsbad','chula vista','coronado','la mesa', 'el cajon','san diego', 'unincorporated')
GROUP 
  BY m.city_name, p.yr, h.long_name
ORDER BY p.yr, m.city_name, h.long_name*/

/*JOBS*/
select 
  m.city_name as jurisdiction
  ,j.yr
  ,e.full_name
  ,sum(j.jobs) as jobs
FROM 
  fact.jobs j 
    LEFT JOIN dim.mgra m ON j.mgra_id = m.mgra_id 
    LEFT JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id
WHERE 
 j.datasource_id = 13
 and lower(m.city_name) in ('carlsbad')--,'chula vista','coronado','la mesa', 'el cajon','san diego', 'unincorporated')
GROUP BY m.city_name, j.yr, e.full_name

 