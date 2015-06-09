--CREATE EXTENSION app.tablefunc;

CREATE OR REPLACE FUNCTION app.fn_age_profile(
    IN datasource_id integer,
    IN geo_name character varying,
    IN geo_zone character varying)
  RETURNS TABLE(yr smallint, geography_zone character varying, sex character varying, group_10yr character varying, population numeric) AS
$BODY$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(lower(geo_zone), ',');

      RETURN QUERY
        WITH crossTbl AS
        (
          SELECT 
            f.yr
            ,m.geozone
            ,s.sex_id
            ,s.sex
            ,a.age_group_id
            ,a.group_10yr 
            ,a.group_10yr_lower_bound
          FROM
            dim.forecast_year f, dim.sex s, dim.age_group a,
            (SELECT m.geozone FROM dim.mgra m WHERE m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1) AND lower(m.geozone) = ANY(zones) AND m.geotype = $2 GROUP BY m.geozone) m
          WHERE 
            f.datasource_id = $1
        ),
        age AS
        (
          SELECT 
            ase.yr
            ,m.geozone
            ,ase.sex_id
            ,ase.age_group_id
            ,sum(ase.population) as population
         FROM
           fact.age_sex_ethnicity ase
           JOIN dim.mgra m on ase.mgra_id = m.mgra_id
         WHERE 
           ase.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
         GROUP BY
           m.geozone, ase.yr, ase.sex_id, ase.age_group_id
        ),
        SUMMARY AS (
        SELECT
          c.yr
          ,c.geozone
          ,c.sex
          ,c.group_10yr
          ,c.group_10yr_lower_bound
          ,sum(coalesce(a.population, 0)) as population
        FROM
          crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id AND c.geozone = a.geozone
        GROUP BY 
          c.yr, c.geozone,c.sex, c.group_10yr, group_10yr_lower_bound
        UNION ALL
        SELECT
          c.yr
          ,c.geozone
          ,c.sex
          ,'Total Population'
          ,NULL as group_10yr_lower_bound
          ,sum(coalesce(a.population, 0)) as population
        FROM
          crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id AND c.geozone = a.geozone
        GROUP BY 
          c.yr, c.geozone,c.sex
        UNION ALL
        SELECT
          c.yr
          ,c.geozone
          ,'Total'
          ,'Total Population'
          ,NULL as group_10yr_lower_bound
          ,sum(coalesce(a.population, 0)) as population
        FROM
          crossTbl c LEFT JOIN age a ON c.yr = a.yr AND c.sex_id = a.sex_id AND c.age_group_id = a.age_group_id AND c.geozone = a.geozone
        GROUP BY 
          c.yr, c.geozone)

        SELECT s.yr, s.geozone, s.sex, s.group_10yr, s.population  FROM SUMMARY s ORDER BY geozone, yr, sex, group_10yr_lower_bound;
      END;
$BODY$
  LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION app.fn_ethnicity_profile(
    IN datasource_id integer,
    IN geo_name character varying,
    IN geo_zone character varying)
  RETURNS TABLE(yr smallint, geography_zone character varying, ethnicity character varying, population numeric) AS
$BODY$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(geo_zone, ',');

      RETURN QUERY
        WITH crossTbl AS
          (SELECT 
             f.yr, m.geozone, e.ethnicity_id, e.short_name 
           FROM 
             dim.forecast_year f, dim.ethnicity e,
             (SELECT m.geozone FROM dim.mgra m WHERE m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1) AND lower(m.geozone) = ANY(zones) AND m.geotype = $2 GROUP BY m.geozone) m
           WHERE f.datasource_id = $1)
        ,ethnic AS
          (SELECT ase.yr, m.geozone, ase.ethnicity_id, sum(ase.population) pop 
            FROM fact.age_sex_ethnicity ase JOIN dim.mgra m on ase.mgra_id = m.mgra_id 
            WHERE ase.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
            GROUP BY ase.yr, m.geozone, ase.ethnicity_id)
        ,SUMMARY AS 
          (SELECT crossTbl.yr, crossTbl.geozone, crossTbl.short_name ethnicity, ethnic.ethnicity_id, coalesce(ethnic.pop, 0) pop FROM crossTbl LEFT JOIN ethnic ON crossTbl.yr = ethnic.yr AND crossTbl.ethnicity_id = ethnic.ethnicity_id and crossTbl.geozone = ethnic.geozone
           UNION ALL
           SELECT crossTbl.yr, crossTbl.geozone, 'Total Population' ethnicity, 99 as ethnicity_id, SUM(coalesce(ethnic.pop, 0)) pop FROM crossTbl LEFT JOIN ethnic ON crossTbl.yr = ethnic.yr AND crossTbl.ethnicity_id = ethnic.ethnicity_id GROUP BY crossTbl.yr, crossTbl.geozone)

        SELECT s.yr, s.geozone, s.ethnicity, s.pop  FROM SUMMARY s ORDER BY s.geozone, s.yr, s.ethnicity_id;
      END;
    $BODY$
  LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION app.fn_housing_profile(datasource_id int, geo_name varchar, geo_zone varchar)
  RETURNS TABLE (yr smallint, geography_zone varchar(50), unit_type varchar, units bigint, occupied bigint, unoccupied bigint, vacancy_rate double precision) AS $$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(geo_zone, ',');

      RETURN QUERY
        WITH SUMMARY AS (
        SELECT
          h.yr
          ,m.geozone
          ,s.structure_type_id
          ,s.long_name as unit_type
          ,SUM(h.units) as units
          ,SUM(h.occupied) as occupied
          ,SUM(h.units - h.occupied) as unoccupied
          ,CASE
            WHEN SUM(h.units) = 0 THEN NULL 
            ELSE SUM(h.units - h.occupied) / CAST(SUM(h.units) as float) 
          END as vacancy_rate
        FROM
          fact.housing h
          JOIN dim.mgra m ON h.mgra_id = m.mgra_id
          JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id
        WHERE 
          h.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
        GROUP BY 
          h.yr, m.geozone, s.structure_type_id, s.long_name
        UNION ALL
        SELECT 
          h.yr
         ,m.geozone
         ,99 as structure_type_id
         ,'Total Units' as unit_type
         ,SUM(h.units) as units
         ,SUM(h.occupied) as occupied
         ,SUM(h.units - h.occupied) as unoccupied
         ,CASE 
           WHEN SUM(h.units) = 0 THEN NULL 
           ELSE SUM(h.units - h.occupied) / CAST(SUM(h.units) as float) 
         END as vacancy_rate
      FROM
        fact.housing h
        JOIN dim.mgra m ON h.mgra_id = m.mgra_id
        JOIN dim.structure_type s ON h.structure_type_id = s.structure_type_id
      WHERE 
        h.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
      GROUP BY
        h.yr, m.geozone)

      SELECT s.yr, s.geozone, s.unit_type, s.units, s.occupied, s.unoccupied, s.vacancy_rate from SUMMARY s ORDER BY s.geozone, s.yr, s.structure_type_id;
      END;
    $$ LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION app.fn_population_profile(datasource_id int, geo_name varchar, geo_zone varchar)
  RETURNS TABLE (yr smallint, geography_zone varchar(50), housing_type varchar, population bigint) AS $$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(geo_zone, ',');

      RETURN QUERY
      SELECT 
        p.yr
        ,m.geozone
        ,h.long_name as housing_type
        ,sum(p.population) as population
      FROM 
        fact.population p	
        JOIN dim.mgra m on p.mgra_id = m.mgra_id 
	JOIN dim.housing_type h on p.housing_type_id = h.housing_type_id
      WHERE
        p.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
      GROUP BY 
        m.geozone, p.yr, h.housing_type_id, h.long_name
      ORDER BY
        m.geozone, p.yr, h.housing_type_id;

    END;
    $$ LANGUAGE 'plpgsql';
	
CREATE OR REPLACE FUNCTION app.fn_income_profile(
    IN datasource_id integer,
    IN geo_name character varying,
    IN geo_zone character varying)
  RETURNS TABLE(yr integer, geography_zone character varying, income_group character varying, households bigint) AS
$BODY$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(geo_zone, ',');

      RETURN QUERY
        WITH SUMMARY AS (
        SELECT 
          i.yr as yr
          ,m.geozone
          ,g.income_group income_group
          ,g.name income_group_name
          ,sum(i.households) as households
        FROM 
          fact.household_income i
          JOIN dim.income_group g ON i.income_group_id = g.income_group_id
          JOIN dim.mgra m ON i.mgra_id = m.mgra_id
        WHERE 
          i.datasource_id = $1 AND m.geotype = $2  AND lower(m.geozone) = ANY(zones)
        GROUP BY 
          i.yr, m.geozone, g.income_group, g.name)

        SELECT s.yr, s.geozone, s.income_group_name, s.households  FROM SUMMARY s ORDER BY s.geozone, s.yr, s.income_group;
      END;
    $BODY$
  LANGUAGE plpgsql;



CREATE OR REPLACE FUNCTION app.fn_median_income(datasource_id int, geo_name varchar, geo_zone varchar)
  RETURNS TABLE (yr integer, geography_zone varchar(50), median_inc integer) AS $$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(geo_zone, ',');
        RETURN QUERY
        WITH num_hh AS (
          SELECT 
            i.yr
            ,m.geozone
            ,SUM(i.households) as hh
          FROM 
            fact.household_income i
            INNER JOIN dim.mgra m ON m.mgra_id = i.mgra_id
          WHERE 
            i.datasource_id = $1 AND m.geotype = $2 AND lower(m.geozone) = $3
          GROUP BY 
            i.yr, m.geozone)
  , inc_dist AS (
    SELECT 
      i.yr
      ,m.geozone
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
      i.datasource_id = $1 AND m.geotype = $2 AND lower(m.geozone) = $3
    GROUP BY 
      i.yr,m.geozone,i.income_group_id,ig.lower_bound,ig.upper_bound)
  ,cum_dist AS (
    SELECT 
      ROW_NUMBER() OVER (PARTITION BY a.yr ORDER BY a.yr, a.income_group_id) as row_num
      ,a.yr
      ,num_hh.geozone
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
      a.yr,num_hh.geozone,a.income_group_id,a.lower_bound,a.upper_bound,a.interval_width,a.hh,num_hh.hh
    HAVING 
      SUM(b.hh) > (num_hh.hh / 2.0))

    SELECT 
      cum_dist.yr
      ,cum_dist.geozone
      ,CAST(ROUND((lower_bound + ((num_hh.hh / 2.0 - (cum_sum - cum_dist.hh)) / cum_dist.hh) * interval_width), 0) as INT) as median_inc
    FROM cum_dist INNER JOIN num_hh ON num_hh.yr = cum_dist.yr AND cum_dist.row_num = 1 ORDER BY cum_dist.yr;
  END;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION app.fn_forecast_ethnicity_change(aDatasourceId int, aGeographyName varchar, aGeographyZone varchar)
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

CREATE OR REPLACE FUNCTION app.fn_jobs_profile(datasource_id int, geo_name varchar, geo_zone varchar)
  RETURNS TABLE (yr smallint, geography_zone varchar(50), employment_type varchar, jobs numeric) AS $$
    DECLARE
      zones varchar[];
    BEGIN
      zones = string_to_array(lower(geo_zone), ',');

      RETURN QUERY
        WITH crossTbl AS (
          SELECT
            f.yr
            ,z.geozone
            ,e.employment_type_id
            ,e.full_name
          FROM
            dim.forecast_year f, dim.employment_type e, (SELECT geozone FROM dim.mgra WHERE geotype = $2 AND lower(geozone) = ANY(zones) GROUP BY geozone) z
          WHERE
            f.datasource_id = $1 and civilian = TRUE
        ),
        job AS (
          SELECT 
            j.yr
            ,m.geozone
            ,e.employment_type_id
            ,e.full_name as category
            ,sum(j.jobs) as jobs
          FROM
            fact.jobs j
            LEFT JOIN dim.mgra m ON j.mgra_id = m.mgra_id
            LEFT JOIN dim.employment_type e ON j.employment_type_id = e.employment_type_id
          WHERE
            j.datasource_id = $1 AND m.geotype = $2 AND lower(m.geozone) = ANY(zones) and e.civilian = true
          GROUP BY
          j.yr, m.geozone, e.employment_type_id, e.full_name
        ),
        summary as (
          SELECT crossTbl.yr, crossTbl.geozone, crossTbl.full_name, crossTbl.employment_type_id, coalesce(job.jobs,0) as jobs FROM crossTbl LEFT JOIN job ON crossTbl.yr = job.yr and crossTbl.employment_type_id = job.employment_type_id
          UNION ALL
          SELECT crossTbl.yr, crossTbl.geozone, 'Total Jobs', 99, sum(coalesce(job.jobs,0)) as jobs FROM crossTbl LEFT JOIN job ON crossTbl.yr = job.yr and crossTbl.employment_type_id = job.employment_type_id GROUP BY crossTbl.yr, crossTbl.geozone
        )

      SELECT summary.yr, summary.geozone, summary.full_name, summary.jobs FROM summary ORDER BY summary.geozone, summary.yr, summary.employment_type_id;
      END;
    $$ LANGUAGE 'plpgsql';