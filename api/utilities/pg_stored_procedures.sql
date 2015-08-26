CREATE EXTENSION IF NOT EXISTS tablefunc WITH schema app;

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
  RETURNS TABLE(yr integer, geography_zone character varying, ordinal int, income_group character varying, households bigint) AS
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

        SELECT s.yr, s.geozone, s.income_group ordinal, s.income_group_name, s.households  FROM SUMMARY s ORDER BY s.geozone, s.yr, s.income_group;
      END;
    $BODY$
  LANGUAGE plpgsql VOLATILE;

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

CREATE OR REPLACE FUNCTION app.fn_forecast_ethnicity_change(
    IN adatasourceid integer,
    IN ageographyname character varying,
    IN ageographyzone character varying)
  RETURNS TABLE(ethnicity character varying, pct_chg_byear_to_2020 double precision, pct_chg_2020_to_2025 double precision, pct_chg_2025_to_2030 double precision, pct_chg_2030_to_2035 double precision, pct_chg_2035_to_2040 double precision, pct_chg_2040_to_2045 double precision, pct_chg_2045_to_2050 double precision, pct_chg_base_to_horizon double precision) AS
$BODY$
DECLARE 
BEGIN

CREATE TEMP TABLE p (ethnicity varchar(35), bYear float, p2020 float, p2025 float, p2030 float, p2035 float, p2040 float, p2045 float, hYear float) ON COMMIT DROP;

INSERT INTO p
  SELECT * FROM app.CROSSTAB(
    format('SELECT ethnicity, yr, population FROM app.fn_ethnicity_profile (%1$s, ''%2$s'', ''%3$s'') ORDER BY ethnicity, yr',aDatasourceId, aGeographyName, aGeographyZone)) as ct
  (ethnicity varchar(35), "bYear" numeric, "2020" numeric, "2025" numeric, "2030" numeric, "2035" numeric, "2040" numeric, "2045" numeric, "hYear" numeric);
  
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

DROP TABLE p;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

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
	
CREATE OR REPLACE FUNCTION app.fn_summarize_age(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, sex character varying, age_group character varying, population numeric) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_age
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,sex varchar
  ,age_group varchar
  ,population numeric
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_age
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,age.geography_zone as geozone
      ,age.yr as yr
      ,age.sex as sex
      ,age.group_10yr as age_group
      ,age.population as population
    FROM
      app.fn_age_profile(r.datasource, lower(r.geotype), lower(r.geozone)) age;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_age;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_ethnicity(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, ethnicity character varying, population numeric) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_ethnic
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,ethnic varchar
  ,population numeric
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_ethnic
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,e.geography_zone as geozone
      ,e.yr as yr
      ,e.ethnicity as ethnic
      ,e.population as population
    FROM
      app.fn_ethnicity_profile(r.datasource, lower(r.geotype), lower(r.geozone)) e;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_ethnic;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_ethnicity_change(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, ethnicity character varying, pct_chg_byear_to_2020 double precision, pct_chg_2020_to_2025 double precision, pct_chg_2025_to_2030 double precision, pct_chg_2030_to_2035 double precision, pct_chg_2035_to_2040 double precision, pct_chg_2040_to_2045 double precision, pct_chg_2045_to_2050 double precision, pct_chg_base_to_horizon double precision) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_change
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,ethnicity varchar
  ,pct_chg_byear_to_2020 double precision
  ,pct_chg_2020_to_2025 double precision
  ,pct_chg_2025_to_2030 double precision
  ,pct_chg_2030_to_2035 double precision
  ,pct_chg_2035_to_2040 double precision
  ,pct_chg_2040_to_2045 double precision
  ,pct_chg_2045_to_2050 double precision
  ,pct_chg_base_to_horizon double precision
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_change
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,r.geozone as geozone
      ,e.ethnicity as ethnicity
      ,e.pct_chg_byear_to_2020
      ,e.pct_chg_2020_to_2025
      ,e.pct_chg_2025_to_2030
      ,e.pct_chg_2030_to_2035
      ,e.pct_chg_2035_to_2040
      ,e.pct_chg_2040_to_2045
      ,e.pct_chg_2045_to_2050
      ,e.pct_chg_base_to_horizon
    FROM
      app.fn_forecast_ethnicity_change(r.datasource, lower(r.geotype), lower(r.geozone)) e;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_change;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_housing(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, unit_type character varying, units bigint, occupied bigint, unoccupied bigint, vacancy_rate double precision) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_housing
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,unit_type varchar
  ,units bigint
  ,occupied bigint
  ,unoccupied bigint
  ,vacancy_rate double precision
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_housing
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,h.geography_zone as geozone
      ,h.yr as yr
      ,h.unit_type
      ,h.units
      ,h.occupied
      ,h.unoccupied
      ,h.vacancy_rate
    FROM
      app.fn_housing_profile(r.datasource, lower(r.geotype), lower(r.geozone)) h;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_housing;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_income(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, ordinal integer, income_group character varying, households bigint) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_income
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,ordinal int
  ,income_group varchar
  ,households bigint
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_income
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,i.geography_zone as geozone
      ,i.yr as yr
      ,i.ordinal
      ,i.income_group
      ,i.households
    FROM
      app.fn_income_profile(r.datasource, lower(r.geotype), lower(r.geozone)) i;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_income;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_jobs(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, employment_type character varying, jobs numeric) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_jobs
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,employment_type varchar
  ,jobs numeric
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_jobs
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,j.geography_zone as geozone
      ,j.yr as yr
      ,j.employment_type
      ,j.jobs
    FROM
      app.fn_jobs_profile(r.datasource, lower(r.geotype), lower(r.geozone)) j;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_jobs;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_median_income(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, median_inc integer) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_income_median
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,median_inc int
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_income_median
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,i.geography_zone as geozone
      ,i.yr as yr
      ,i.median_inc
    FROM
      app.fn_median_income(r.datasource, lower(r.geotype), lower(r.geozone)) i;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_income_median;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

CREATE OR REPLACE FUNCTION app.fn_summarize_population(IN ds_id integer)
  RETURNS TABLE(datasource_id integer, geotype character varying, geozone character varying, yr smallint, housing_type character varying, population bigint) AS
$BODY$
DECLARE r RECORD;
BEGIN

CREATE TEMP TABLE summarize_population
(
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,housing_type varchar
  ,population bigint
) ON COMMIT DROP;

FOR r IN 
  SELECT cast($1 as integer) as datasource, m.geotype, m.geozone 
    FROM dim.mgra m 
    WHERE  m.series IN (SELECT d.series FROM dim.datasource d WHERE d.datasource_id = $1)
    GROUP BY m.series, m.geotype, m.geozone
    ORDER BY m.series, m.geotype, m.geozone LOOP

  RAISE NOTICE 'datasource: %, geotype: %, geozone: %', r.datasource, r.geotype, r.geozone;

  INSERT INTO summarize_population
    SELECT
      r.datasource as datasource_id
      ,cast(r.geotype as varchar) as geotype
      ,h.geography_zone as geozone
      ,h.yr as yr
      ,h.housing_type
      ,h.population
    FROM
      app.fn_population_profile(r.datasource, lower(r.geotype), lower(r.geozone)) h;
END LOOP;

RETURN QUERY 
 SELECT * FROM summarize_population;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;

INSERT INTO fact.summary_age_ds2 SELECT * FROM app.fn_summarize_age(2); COMMIT; 
INSERT INTO fact.summary_age_ds3 SELECT * FROM app.fn_summarize_age(3); COMMIT;
INSERT INTO fact.summary_age_ds4 SELECT * FROM app.fn_summarize_age(4); COMMIT;
INSERT INTO fact.summary_age_ds5 SELECT * FROM app.fn_summarize_age(5); COMMIT;
INSERT INTO fact.summary_age_ds6 SELECT * FROM app.fn_summarize_age(6); COMMIT;
INSERT INTO fact.summary_age_ds10 SELECT * FROM app.fn_summarize_age(10); COMMIT;
INSERT INTO fact.summary_age_ds12 SELECT * FROM app.fn_summarize_age(12); COMMIT;
INSERT INTO fact.summary_age_ds13 SELECT * FROM app.fn_summarize_age(13); COMMIT;
INSERT INTO fact.summary_age_ds14 SELECT * FROM app.fn_summarize_age(14); COMMIT;

CREATE INDEX ix_summary_age_ds2_geotype_geoname ON fact.summary_age_ds2 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds3_geotype_geoname ON fact.summary_age_ds3 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds4_geotype_geoname ON fact.summary_age_ds4 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds5_geotype_geoname ON fact.summary_age_ds5 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds6_geotype_geoname ON fact.summary_age_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds10_geotype_geoname ON fact.summary_age_ds10 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds12_geotype_geoname ON fact.summary_age_ds12 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds13_geotype_geoname ON fact.summary_age_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_age_ds14_geotype_geoname ON fact.summary_age_ds14 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_ethnicity_ds2 SELECT * FROM app.fn_summarize_ethnicity(2); COMMIT;
INSERT INTO fact.summary_ethnicity_ds3 SELECT * FROM app.fn_summarize_ethnicity(3); COMMIT;
INSERT INTO fact.summary_ethnicity_ds4 SELECT * FROM app.fn_summarize_ethnicity(4); COMMIT;
INSERT INTO fact.summary_ethnicity_ds5 SELECT * FROM app.fn_summarize_ethnicity(5); COMMIT;
INSERT INTO fact.summary_ethnicity_ds6 SELECT * FROM app.fn_summarize_ethnicity(6); COMMIT;
INSERT INTO fact.summary_ethnicity_ds10 SELECT * FROM app.fn_summarize_ethnicity(10); COMMIT;
INSERT INTO fact.summary_ethnicity_ds12 SELECT * FROM app.fn_summarize_ethnicity(12); COMMIT;
INSERT INTO fact.summary_ethnicity_ds13 SELECT * FROM app.fn_summarize_ethnicity(13); COMMIT;
INSERT INTO fact.summary_ethnicity_ds14 SELECT * FROM app.fn_summarize_ethnicity(14); COMMIT;

CREATE INDEX ix_summary_ethnicity_ds2_geotype_geozone ON fact.summary_ethnicity_ds2 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds3_geotype_geozone ON fact.summary_ethnicity_ds3 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds4_geotype_geozone ON fact.summary_ethnicity_ds4 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds5_geotype_geozone ON fact.summary_ethnicity_ds5 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds6_geotype_geozone ON fact.summary_ethnicity_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds10_geotype_geozone ON fact.summary_ethnicity_ds10 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds12_geotype_geozone ON fact.summary_ethnicity_ds12 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds13_geotype_geozone ON fact.summary_ethnicity_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_ds14_geotype_geozone ON fact.summary_ethnicity_ds14 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_ethnicity_change_ds6 SELECT * FROM app.fn_summarize_ethnicity_change(6); COMMIT;
INSERT INTO fact.summary_ethnicity_change_ds13 SELECT * FROM app.fn_summarize_ethnicity_change(13); COMMIT;

CREATE INDEX ix_summary_ethnicity_change_ds6 ON fact.summary_ethnicity_change_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_ethnicity_change_ds13 ON fact.summary_ethnicity_change_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_housing_ds2 SELECT * FROM app.fn_summarize_housing(2); COMMIT;
INSERT INTO fact.summary_housing_ds3 SELECT * FROM app.fn_summarize_housing(3); COMMIT;
INSERT INTO fact.summary_housing_ds4 SELECT * FROM app.fn_summarize_housing(4); COMMIT;
INSERT INTO fact.summary_housing_ds5 SELECT * FROM app.fn_summarize_housing(5); COMMIT;
INSERT INTO fact.summary_housing_ds6 SELECT * FROM app.fn_summarize_housing(6); COMMIT;
INSERT INTO fact.summary_housing_ds10 SELECT * FROM app.fn_summarize_housing(10); COMMIT;
INSERT INTO fact.summary_housing_ds12 SELECT * FROM app.fn_summarize_housing(12); COMMIT;
INSERT INTO fact.summary_housing_ds13 SELECT * FROM app.fn_summarize_housing(13); COMMIT;
INSERT INTO fact.summary_housing_ds14 SELECT * FROM app.fn_summarize_housing(14); COMMIT;

CREATE INDEX ix_summary_housing_ds2_geotype_geozone ON fact.summary_housing_ds2 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds3_geotype_geozone ON fact.summary_housing_ds3 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds4_geotype_geozone ON fact.summary_housing_ds4 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds5_geotype_geozone ON fact.summary_housing_ds5 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds6_geotype_geozone ON fact.summary_housing_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds10_geotype_geozone ON fact.summary_housing_ds10 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds12_geotype_geozone ON fact.summary_housing_ds12 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds13_geotype_geozone ON fact.summary_housing_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_housing_ds14_geotype_geozone ON fact.summary_housing_ds14 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_income_ds2 SELECT * FROM app.fn_summarize_income(2); COMMIT;
INSERT INTO fact.summary_income_ds3 SELECT * FROM app.fn_summarize_income(3); COMMIT;
INSERT INTO fact.summary_income_ds4 SELECT * FROM app.fn_summarize_income(4); COMMIT;
INSERT INTO fact.summary_income_ds5 SELECT * FROM app.fn_summarize_income(5); COMMIT;
INSERT INTO fact.summary_income_ds6 SELECT * FROM app.fn_summarize_income(6); COMMIT;
INSERT INTO fact.summary_income_ds10 SELECT * FROM app.fn_summarize_income(10); COMMIT;
INSERT INTO fact.summary_income_ds12 SELECT * FROM app.fn_summarize_income(12); COMMIT;
INSERT INTO fact.summary_income_ds13 SELECT * FROM app.fn_summarize_income(13); COMMIT;
INSERT INTO fact.summary_income_ds14 SELECT * FROM app.fn_summarize_income(14); COMMIT;

CREATE INDEX ix_summary_income_ds2_geotype_geozone ON fact.summary_income_ds2 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds3_geotype_geozone ON fact.summary_income_ds3 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds4_geotype_geozone ON fact.summary_income_ds4 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds5_geotype_geozone ON fact.summary_income_ds5 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds6_geotype_geozone ON fact.summary_income_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds10_geotype_geozone ON fact.summary_income_ds10 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds12_geotype_geozone ON fact.summary_income_ds12 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds13_geotype_geozone ON fact.summary_income_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_ds14_geotype_geozone ON fact.summary_income_ds14 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_income_median_ds2 SELECT * FROM app.fn_summarize_median_income(2); COMMIT;
INSERT INTO fact.summary_income_median_ds3 SELECT * FROM app.fn_summarize_median_income(3); COMMIT;
INSERT INTO fact.summary_income_median_ds4 SELECT * FROM app.fn_summarize_median_income(4); COMMIT;
INSERT INTO fact.summary_income_median_ds5 SELECT * FROM app.fn_summarize_median_income(5); COMMIT;
INSERT INTO fact.summary_income_median_ds6 SELECT * FROM app.fn_summarize_median_income(6); COMMIT;
INSERT INTO fact.summary_income_median_ds10 SELECT * FROM app.fn_summarize_median_income(10); COMMIT;
INSERT INTO fact.summary_income_median_ds12 SELECT * FROM app.fn_summarize_median_income(12); COMMIT;
INSERT INTO fact.summary_income_median_ds13 SELECT * FROM app.fn_summarize_median_income(13); COMMIT;
INSERT INTO fact.summary_income_median_ds14 SELECT * FROM app.fn_summarize_median_income(14); COMMIT;

CREATE INDEX ix_summary_income_median_ds2 ON fact.summary_income_median_ds2 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds3 ON fact.summary_income_median_ds3 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds4 ON fact.summary_income_median_ds4 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds5 ON fact.summary_income_median_ds5 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds6 ON fact.summary_income_median_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds10 ON fact.summary_income_median_ds10 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds12 ON fact.summary_income_median_ds12 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds13 ON fact.summary_income_median_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_income_median_ds14 ON fact.summary_income_median_ds14 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_jobs_ds6 SELECT * FROM app.fn_summarize_jobs(6); COMMIT;
INSERT INTO fact.summary_jobs_ds13 SELECT * FROM app.fn_summarize_jobs(13); COMMIT;

CREATE INDEX ix_summary_jobs_ds6 ON fact.summary_jobs_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_jobs_ds13 ON fact.summary_jobs_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

INSERT INTO fact.summary_population_ds2 SELECT * FROM app.fn_summarize_population(2); COMMIT;
INSERT INTO fact.summary_population_ds3 SELECT * FROM app.fn_summarize_population(3); COMMIT;
INSERT INTO fact.summary_population_ds4 SELECT * FROM app.fn_summarize_population(4); COMMIT;
INSERT INTO fact.summary_population_ds5 SELECT * FROM app.fn_summarize_population(5); COMMIT;
INSERT INTO fact.summary_population_ds6 SELECT * FROM app.fn_summarize_population(6); COMMIT;

INSERT INTO fact.summary_population_ds10 SELECT * FROM app.fn_summarize_population(10); COMMIT;
INSERT INTO fact.summary_population_ds12 SELECT * FROM app.fn_summarize_population(12); COMMIT;
INSERT INTO fact.summary_population_ds13 SELECT * FROM app.fn_summarize_population(13); COMMIT;
INSERT INTO fact.summary_population_ds14 SELECT * FROM app.fn_summarize_population(14); COMMIT;

CREATE INDEX ix_summary_population_ds2 ON fact.summary_population_ds2 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds3 ON fact.summary_population_ds3 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds4 ON fact.summary_population_ds4 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds5 ON fact.summary_population_ds5 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds6 ON fact.summary_population_ds6 (geotype, lower(geozone));

CREATE INDEX ix_summary_population_ds10 ON fact.summary_population_ds10 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds12 ON fact.summary_population_ds12 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds13 ON fact.summary_population_ds13 (geotype, lower(geozone));
CREATE INDEX ix_summary_population_ds14 ON fact.summary_population_ds14 (geotype, lower(geozone));




