DROP SCHEMA dim cascade;
DROP SCHEMA fact cascade;
DROP SCHEMA app cascade;

CREATE SCHEMA IF NOT EXISTS dim AUTHORIZATION datasurfer_admin;
COMMENT ON SCHEMA dim IS 'Data warehouse dimension schema.';
  
CREATE SCHEMA IF NOT EXISTS fact AUTHORIZATION datasurfer_admin;
COMMENT ON SCHEMA fact IS 'Data warehouse fact table schema.';  

CREATE SCHEMA IF NOT EXISTS app AUTHORIZATION datasurfer_admin;
COMMENT ON SCHEMA app IS 'Schema for housing application specific procedures and data.';

CREATE SEQUENCE fact.seq_acs_profile_id;
CREATE SEQUENCE fact.seq_age_sex_ethnicity_id;
CREATE SEQUENCE fact.seq_household_income_id;
CREATE SEQUENCE fact.seq_housing_id;
CREATE SEQUENCE fact.seq_jobs_id;
CREATE SEQUENCE fact.seq_population_id;

CREATE TABLE IF NOT EXISTS dim.acs_contract_rent_group
(
  acs_rent_group_id smallint NOT NULL,
  name character varying(50) NOT NULL,
  constant_dollars_year smallint NOT NULL,
  lower_bound integer NOT NULL,
  upper_bound integer NOT NULL,
  CONSTRAINT pk_acs_rent_group PRIMARY KEY (acs_rent_group_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.acs_employment_status_type
(
  acs_employment_status_type_id smallint NOT NULL,
  name character varying(50) NOT NULL,
  CONSTRAINT pk_acs_employment_status PRIMARY KEY (acs_employment_status_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.acs_housing_value_group
(
  acs_housing_value_group_id integer NOT NULL,
  name character varying(50) NOT NULL,
  constant_dollars_year smallint NOT NULL,
  lower_bound integer NOT NULL,
  upper_bound integer NOT NULL,
  CONSTRAINT pk_acs_housing_value_group PRIMARY KEY (acs_housing_value_group_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.acs_structure_type
(
  acs_structure_type_id smallint NOT NULL,
  name character varying NOT NULL,
  CONSTRAINT pk_acs_structure_type PRIMARY KEY (acs_structure_type_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.acs_travel_time_group
(
  acs_travel_time_group_id smallint NOT NULL,
  name character varying NOT NULL,
  lower_bound smallint NOT NULL,
  upper_bound smallint NOT NULL,
  CONSTRAINT pk_acs_travel_time_group PRIMARY KEY (acs_travel_time_group_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.age_group(
	age_group_id smallint CONSTRAINT pk_age_group PRIMARY KEY,
    name character varying(15) NOT NULL,
    group_10yr character varying(10) NOT NULL,
    lower_bound smallint NOT NULL,
    upper_bound smallint NOT NULL,
    group_10yr_lower_bound smallint,
    group_10yr_upper_bound smallint
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.datasource(
	datasource_id smallint CONSTRAINT pk_datasource PRIMARY KEY,
	datasource_type_id int NOT NULL,
	name varchar(50) NOT NULL,
	yr int NOT NULL,
	description varchar(50) NOT NULL,
	is_active boolean NOT NULL,
	displayed_name varchar(50) NULL,
	series smallint NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.employment_type(
	employment_type_id smallint CONSTRAINT pk_employment_type PRIMARY KEY,
	short_name varchar(15) NOT NULL,
	full_name varchar(50) NOT NULL,
	civilian boolean NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.ethnicity(
	ethnicity_id smallint CONSTRAINT pk_ethnicity PRIMARY KEY,
	code varchar(5) NOT NULL,
	hispanic boolean NOT NULL,
	short_name varchar(35) NOT NULL,
	long_name varchar(50) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.forecast_year(
   forecast_year_id int CONSTRAINT pk_forecast_year PRIMARY KEY,
   datasource_id smallint NOT NULL,
   yr smallint NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.housing_type(
	housing_type_id smallint CONSTRAINT pk_housing_type PRIMARY KEY,
	short_name varchar(10) NOT NULL,
	long_name varchar(25) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.income_group(
	income_group_id int CONSTRAINT pk_income_group PRIMARY KEY,
	income_group int NOT NULL,
	name varchar(50) NOT NULL,
	constant_dollars_year int NOT NULL,
	lower_bound int NOT NULL,
	upper_bound int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.lu_group(
	lu_group_id int CONSTRAINT pk_lu_group PRIMARY KEY,
	short_name varchar(25) NOT NULL,
	full_name varchar(50) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.mgra(
  mgra_id integer NOT NULL,
  mgra integer NOT NULL,
  series smallint NOT NULL,
  geotype character varying(50) NOT NULL,
  geozone character varying(50) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.sex(
	sex_id smallint CONSTRAINT pk_sex PRIMARY KEY,
	sex varchar(6) NOT NULL,
	abbreviation nchar(1) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS dim.structure_type(
	structure_type_id smallint CONSTRAINT pk_structure_type PRIMARY KEY,
	short_name varchar(15) NOT NULL,
	long_name varchar(35) NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.acs_profile(
    acs_profile_id integer NOT NULL DEFAULT nextval('fact.seq_acs_profile_id'),
	datasource_id smallint NOT NULL,
	yr int NOT NULL,
	mgra_id int NOT NULL,
	pop_15plus float NULL,
	pop_15plus_no_married float NULL,
	pop_15plus_married float NULL,
	pop_15plus_separated float NULL,
	pop_15plus_widowed float NULL,
	pop_15plus_divorced float NULL,
	pop_5plus float NULL,
	pop_5plus_english float NULL,
	pop_5plus_spanish float NULL,
	pop_5plus_spanish_english float NULL,
	pop_5plus_spanish_no_english float NULL,
	pop_5plus_asian float NULL,
	pop_5plus_asian_english float NULL,
	pop_5plus_asian_no_english float NULL,
	pop_5plus_other float NULL,
	pop_5plus_other_english float NULL,
	pop_5plus_other_no_english float NULL,
	pop_25plus_edu float NULL,
	pop_25plus_edu_lt9 float NULL,
	pop_25plus_edu_9to12_no_degree float NULL,
	pop_25plus_edu_high_school float NULL,
	pop_25plus_edu_col_no_degree float NULL,
	pop_25plus_edu_associate float NULL,
	pop_25plus_edu_bachelor float NULL,
	pop_25plus_edu_master float NULL,
	pop_25plus_edu_prof float NULL,
	pop_25plus_edu_doctorate float NULL,
	pop_3plus float NULL,
	pop_3plus_sch_pre float NULL,
	pop_3plus_sch_Kto4 float NULL,
	pop_3plus_sch_5to8 float NULL,
	pop_3plus_sch_9to12 float NULL,
	pop_3plus_sch_college float NULL,
	pop_3plus_sch_grad float NULL,
	pop_3plus_no_enroll float NULL,
	pop_3plus_sch_public float NULL,
	pop_3plus_sch_pre_public float NULL,
	pop_3plus_sch_Kto4_public float NULL,
	pop_3plus_sch_5to8_public float NULL,
	pop_3plus_sch_9to12_public float NULL,
	pop_3plus_sch_college_public float NULL,
	pop_3plus_sch_grad_public float NULL,
	pop_3plus_sch_private float NULL,
	pop_3plus_sch_pre_private float NULL,
	pop_3plus_sch_Kto4_private float NULL,
	pop_3plus_sch_5to8_private float NULL,
	pop_3plus_sch_9to12_private float NULL,
	pop_3plus_sch_college_private float NULL,
	pop_3plus_sch_grad_private float NULL,
	hh_fam_mar float NOT NULL,
	hh_fam_oth float NOT NULL,
	hh_fam_oth_m float NOT NULL,
	hh_fam_oth_f float NOT NULL,
	hh_fam_u18 float NOT NULL,
	hh_fam_mar_u18 float NOT NULL,
	hh_fam_oth_u18 float NOT NULL,
	hh_fam_oth_f_u18 float NOT NULL,
	hh_nfam_u18 float NOT NULL,
	hh_fam_n18 float NOT NULL,
	hh_fam_mar_n18 float NOT NULL,
	hh_fam_oth_n18 float NOT NULL,
	hh_fam_oth_m_n18 float NOT NULL,
	hh_fam_oth_f_n18 float NOT NULL,
	hh_nfam_n18 float NOT NULL,
	hh_nfam_alone float NOT NULL,
	hh_nfam_other float NOT NULL,
	hh_fam_oth_m_u18 float NOT NULL,
	hh_owner_value float NOT NULL,
	hh_owner_value_lt150K float NOT NULL,
	hh_owner_value_150Kto200K float NOT NULL,
	hh_owner_value_200Kto250K float NOT NULL,
	hh_owner_value_250Kto300K float NOT NULL,
	hh_owner_value_300Kto400K float NOT NULL,
	hh_owner_value_400Kto500K float NOT NULL,
	hh_owner_value_500Kto750K float NOT NULL,
	hh_owner_value_750Kto1M float NOT NULL,
	hh_owner_value_1Mplus float NOT NULL,
	hs float NOT NULL,
	hs_2005later float NOT NULL,
	hs_2000to2004 float NOT NULL,
	hs_1990to1999 float NOT NULL,
	hs_1980to1989 float NOT NULL,
	hs_1970to1979 float NOT NULL,
	hs_1960to1969 float NOT NULL,
	hs_1950to1959 float NOT NULL,
	hs_1940to1949 float NOT NULL,
	hs_1939before float NOT NULL,
	hh_occupant_lt1 float NOT NULL,
	hh_occupant_1to1dot5 float NOT NULL,
	hh_occupant_1dot5to2 float NOT NULL,
	hh_occupant_2more float NOT NULL,
	hh_rent_occupant_lt1 float NOT NULL,
	hh_rent_occupant_1to1dot5 float NOT NULL,
	hh_rent_occupant_1dot5to2 float NOT NULL,
	hh_rent_occupant_2more float NOT NULL,
	hh_own_occupant_lt1 float NOT NULL,
	hh_own_occupant_1to1dot5 float NOT NULL,
	hh_own_occupant_1dot5to2 float NOT NULL,
	hh_own_occupant_2more float NOT NULL,
	hh_rent float NOT NULL,
	hh_rent_lt500 float NOT NULL,
	hh_rent_500to599 float NOT NULL,
	hh_rent_600to699 float NOT NULL,
	hh_rent_700to799 float NOT NULL,
	hh_rent_800to899 float NOT NULL,
	hh_rent_900to999 float NOT NULL,
	hh_rent_1000to1249 float NOT NULL,
	hh_rent_1250to1499 float NOT NULL,
	hh_rent_1500to1999 float NOT NULL,
	hh_rent_2000plus float NOT NULL,
	hh_rent_nocash float NOT NULL,
	hh_rent_income_lt20pct float NOT NULL,
	hh_rent_income_20to25pct float NOT NULL,
	hh_rent_income_25to30pct float NOT NULL,
	hh_rent_income_30to35pct float NOT NULL,
	hh_rent_income_35to40pct float NOT NULL,
	hh_rent_income_40to50pct float NOT NULL,
	hh_rent_income_50pct_plus float NOT NULL,
	hh_rent_income_nocomputed float NOT NULL,
	hh_no_auto float NOT NULL,
	hh_auto_1 float NOT NULL,
	hh_auto_2 float NOT NULL,
	hh_auto_3 float NOT NULL,
	hh_auto_4plus float NOT NULL,
	emp_place float NULL,
	emp_place_in_state float NULL,
	emp_place_in_state_in_county float NULL,
	emp_place_in_state_out_county float NULL,
	emp_place_out_state float NULL,
	emp_mode_auto float NULL,
	emp_mode_auto_drive_alone float NULL,
	emp_mode_auto_carpool float NULL,
	emp_mode_transit float NULL,
	emp_mode_transit_bus float NULL,
	emp_mode_transit_trolley float NULL,
	emp_mode_transit_rail float NULL,
	emp_mode_transit_others float NULL,
	emp_mode_motor float NULL,
	emp_mode_bike float NULL,
	emp_mode_walk float NULL,
	emp_mode_others float NULL,
	emp_mode_home float NULL,
	emp_travel_no_home float NULL,
	emp_travel_no_home_lt10min float NULL,
	emp_travel_no_home_10to19min float NULL,
	emp_travel_no_home_20to29min float NULL,
	emp_travel_no_home_30to44min float NULL,
	emp_travel_no_home_45to59min float NULL,
	emp_travel_no_home_60to89min float NULL,
	emp_travel_no_home_90plusmin float NULL,
	emp_occu_mgmt_prof float NULL,
	emp_occu_mgmt_prof_management float NULL,
	emp_occu_mgmt_prof_business float NULL,
	emp_occu_mgmt_prof_computer float NULL,
	emp_occu_mgmt_prof_engineer float NULL,
	emp_occu_mgmt_prof_science float NULL,
	emp_occu_mgmt_prof_service float NULL,
	emp_occu_mgmt_prof_legal float NULL,
	emp_occu_mgmt_prof_education float NULL,
	emp_occu_mgmt_prof_art float NULL,
	emp_occu_mgmt_prof_health float NULL,
	emp_occu_service float NULL,
	emp_occu_service_health float NULL,
	emp_occu_service_protective float NULL,
	emp_occu_service_food float NULL,
	emp_occu_service_maint float NULL,
	emp_occu_service_person float NULL,
	emp_occu_saleoffice float NULL,
	emp_occu_farmfishing float NULL,
	emp_occu_construction float NULL,
	emp_occu_transport float NULL,
	emp_occu float NULL,
	emp_indu_agriculture float NULL,
	emp_indu_utilities float NULL,
	emp_indu_construnction float NULL,
	emp_indu_manufacturing float NULL,
	emp_indu_wholesale float NULL,
	emp_indu_retail float NULL,
	emp_indu_transport float NULL,
	emp_indu_communication float NULL,
	emp_indu_finance float NULL,
	emp_indu_professional float NULL,
	emp_indu_education float NULL,
	emp_indu_art float NULL,
	emp_indu_other_services float NULL,
	emp_indu_public float NULL,
	hh_inc_earning float NOT NULL,
	hh_inc_wage float NOT NULL,
	hh_inc_self_emp float NOT NULL,
	hh_inc_interest float NOT NULL,
	hh_inc_social float NOT NULL,
	hh_inc_supplemental float NOT NULL,
	hh_inc_public float NOT NULL,
	hh_inc_retirement float NOT NULL,
	hh_inc_other float NOT NULL,
	pop_poverty_lt0dot5 float NULL,
	pop_poverty_0dot5to0dot99 float NULL,
	pop_poverty_1to1dot24 float NULL,
	pop_poverty_1dot25to1dot49 float NULL,
	pop_poverty_1dot5to1dot84 float NULL,
	pop_poverty_1dot85to1dot99 float NULL,
	pop_poverty_2plus float NULL,
	pop_poverty float NULL,
	pop_poverty_above float NULL,
	pop_poverty_below float NULL,
	hh_fam_above_poverty float NOT NULL,
	hh_fam_mar_above_poverty float NOT NULL,
	hh_fam_m_above_poverty float NOT NULL,
	hh_fam_f_above_poverty float NOT NULL,
	hh_fam_n18_above_poverty float NOT NULL,
	hh_fam_mar_n18_above_poverty float NOT NULL,
	hh_fam_m_n18_above_poverty float NOT NULL,
	hh_fam_f_n18_above_poverty float NOT NULL,
	hh_fam_u18_above_poverty float NOT NULL,
	hh_fam_mar_u18_above_poverty float NOT NULL,
	hh_fam_m_u18_above_poverty float NOT NULL,
	hh_fam_f_u18_above_poverty float NOT NULL,
	hh_fam_below_poverty float NOT NULL,
	hh_fam_mar_below_poverty float NOT NULL,
	hh_fam_m_below_poverty float NOT NULL,
	hh_fam_f_below_poverty float NOT NULL,
	hh_fam_n18_below_poverty float NOT NULL,
	hh_fam_mar_n18_below_poverty float NOT NULL,
	hh_fam_m_n18_below_poverty float NOT NULL,
	hh_fam_f_n18_below_poverty float NOT NULL,
	hh_fam_u18_below_poverty float NOT NULL,
	hh_fam_mar_u18_below_poverty float NOT NULL,
	hh_fam_m_u18_below_poverty float NOT NULL,
	hh_fam_f_u18_below_poverty float NOT NULL,
	hh float NOT NULL,
	hh_fam float NOT NULL,
	hh_nfam float NOT NULL,
	hh_u18 float NOT NULL,
	hh_n18 float NOT NULL,
	hh_own float NOT NULL,
	profile_id int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.acs_employment_status
(
    acs_employment_status_id serial NOT NULL,
    datasource_id smallint NOT NULL,
    yr integer NOT NULL,
    mgra_id integer NOT NULL,
    sex_id smallint NOT NULL,
    acs_employment_status_type_id smallint NOT NULL,
    population integer NOT NULL,
    CONSTRAINT pk_acs_employment_status PRIMARY KEY (acs_employment_status_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.acs_housing_units
(
  acs_housing_units_id serial NOT NULL,
  datasource_id smallint NOT NULL,
  yr integer NOT NULL,
  mgra_id integer NOT NULL,
  acs_structure_type_id smallint NOT NULL,
  hs integer NOT NULL,
  CONSTRAINT pk_acs_housing_units PRIMARY KEY (acs_housing_units_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.acs_housing_value
(
  housing_value_id serial NOT NULL,
  datasource_id smallint NOT NULL,
  yr integer NOT NULL,
  mgra_id integer NOT NULL,
  housing_value_group integer NOT NULL,
  households integer NOT NULL,
  CONSTRAINT pk_acs_housing_value PRIMARY KEY (housing_value_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.acs_travel_time_to_work
(
  acs_travel_time_to_work_id serial NOT NULL,
  datasource_id smallint NOT NULL,
  yr integer NOT NULL,
  mgra_id integer NOT NULL,
  acs_travel_time_group_id integer NOT NULL,
  workers integer NOT NULL,
  CONSTRAINT pk_acs_travel_time_to_work PRIMARY KEY (acs_travel_time_to_work_id)
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_load(
    age_sex_ethnicity_id integer NOT NULL DEFAULT nextval('fact.seq_age_sex_ethnicity_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	age_group_id smallint NOT NULL,
	sex_id smallint NOT NULL,
	ethnicity_id smallint NOT NULL,
	population int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity(
    age_sex_ethnicity_id integer NOT NULL DEFAULT nextval('fact.seq_age_sex_ethnicity_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	age_group_id smallint NOT NULL,
	sex_id smallint NOT NULL,
	ethnicity_id smallint NOT NULL,
	population int NOT NULL
) TABLESPACE datasurfer_tablespace;

--Forecast Partitions
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds6 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds13 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;

--Census Partitions
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds12 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds5 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;

--Estimate Partitions
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds2 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds3 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds4 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds10 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.age_sex_ethnicity_ds14 () INHERITS (fact.age_sex_ethnicity) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.age_sex_ethnicity_ds6 ADD CONSTRAINT chk_ase_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.age_sex_ethnicity_ds13 ADD CONSTRAINT chk_ase_ds13 CHECK (datasource_id = 13);

ALTER TABLE fact.age_sex_ethnicity_ds12 ADD CONSTRAINT chk_ase_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.age_sex_ethnicity_ds5 ADD CONSTRAINT chk_ase_ds5 CHECK (datasource_id = 5);

ALTER TABLE fact.age_sex_ethnicity_ds2 ADD CONSTRAINT chk_ase_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.age_sex_ethnicity_ds3 ADD CONSTRAINT chk_ase_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.age_sex_ethnicity_ds4 ADD CONSTRAINT chk_ase_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.age_sex_ethnicity_ds10 ADD CONSTRAINT chk_ase_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.age_sex_ethnicity_ds14 ADD CONSTRAINT chk_ase_ds14 CHECK (datasource_id = 14);

CREATE TABLE IF NOT EXISTS fact.household_income(
	household_income_id int NOT NULL DEFAULT nextval('fact.seq_household_income_id'),
	datasource_id smallint NOT NULL,
	yr int NOT NULL,
	mgra_id int NOT NULL,
	income_group_id int NOT NULL,
	households int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.housing(
	housing_id int NOT NULL DEFAULT nextval('fact.seq_housing_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	structure_type_id smallint NOT NULL,
	units int NOT NULL,
	occupied int NOT NULL,
	vacancy float NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.jobs_load(
	jobs_id int NOT NULL DEFAULT nextval('fact.seq_jobs_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	employment_type_id smallint NOT NULL,
	jobs int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.jobs(
	jobs_id int NOT NULL DEFAULT nextval('fact.seq_jobs_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	employment_type_id smallint NOT NULL,
	jobs int NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.jobs_ds6 () INHERITS (fact.jobs) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.jobs_ds13 () INHERITS (fact.jobs) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.jobs_ds6 ADD CONSTRAINT chk_jobs_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.jobs_ds13 ADD CONSTRAINT chk_jobs_ds6 CHECK (datasource_id = 13);

CREATE TABLE IF NOT EXISTS fact.population(
	population_id int NOT NULL DEFAULT nextval('fact.seq_population_id'),
	datasource_id smallint NOT NULL,
	yr smallint NOT NULL,
	mgra_id int NOT NULL,
	housing_type_id smallint NOT NULL,
	population int NOT NULL
) TABLESPACE datasurfer_tablespace;

ALTER SEQUENCE fact.seq_acs_profile_id OWNED BY fact.acs_profile.acs_profile_id;
ALTER SEQUENCE fact.seq_age_sex_ethnicity_id OWNED BY fact.age_sex_ethnicity.age_sex_ethnicity_id;
ALTER SEQUENCE fact.seq_household_income_id OWNED BY fact.household_income.household_income_id;
ALTER SEQUENCE fact.seq_housing_id OWNED BY fact.housing.housing_id;
ALTER SEQUENCE fact.seq_jobs_id OWNED BY fact.jobs.jobs_id;
ALTER SEQUENCE fact.seq_population_id OWNED BY fact.population.population_id;

INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (1, 'Less than $500', 2009, 0, 499);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (2, '$500 to $599', 2009, 500, 599);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (3, '$600 to $699', 2009, 600, 699);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (4, '$700 to $799', 2009, 700, 799);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (5, '$800 to $899', 2009, 800, 899);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (6, '$900 to $999', 2009, 900, 999);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (7, '$1000 to $1249', 2009, 1000, 1249);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (8, '$1250 to $1499', 2009, 1250, 1499);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (9, '$1500 to $1999', 2009, 1500, 1999);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (10, '$2000 or more', 2009, 2000, 3499);
INSERT INTO dim.acs_contract_rent_group (acs_rent_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (11, 'No cash rent', 2009, 0, 0);

INSERT INTO dim.acs_employment_status (acs_employment_status_id, name) VALUES (1, 'Armed forces');
INSERT INTO dim.acs_employment_status (acs_employment_status_id, name) VALUES (2, 'Civilian (employed)');
INSERT INTO dim.acs_employment_status (acs_employment_status_id, name) VALUES (3, 'Civilian (unemployed)');
INSERT INTO dim.acs_employment_status (acs_employment_status_id, name) VALUES (4, 'Not in labor force');

INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (1, "Less than $150,000", 2009, 0, 149999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (2, '$150,000 to $199,999', 2009, 150000, 199999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (3, '$200,000 to $249,999', 2009, 200000, 249999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (4, '$250,000 to $299,999', 2009, 250000, 299999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (5, '$300,000 to $399,999', 2009, 300000, 399999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (6, '$400,000 to $499,999', 2009, 400000, 499999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (7, '$500,000 to $749,999', 2009, 500000, 749999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (8, '$750,000 to $999,999', 2009, 750000, 999999);
INSERT INTO dim.acs_housing_value_group (acs_housing_value_group_id, name, constant_dollars_year, lower_bound, upper_bound) VALUES (9, '$1,000,000 or more', 2009, 1000000, 1999999);

INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (1, 'Single family (detached)');
INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (2, 'Single family (attached)');
INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (3, '2 to 4 units');
INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (4, '5 to 9 units');
INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (5, '10 or more units');
INSERT INTO dim.acs_structure_type (acs_structure_type_id, name) VALUES (6, 'Mobile home and Other');

INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (1, 'Less than 10 minutes', 0, 9);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (2, '10 to 19 minutes', 10, 19);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (3, '20 to 29 minutes', 20, 29);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (4, '30 to 44 minutes', 30, 44);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (5, '45 to 59 minutes', 45, 59);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (6, '60 to 89 minutes', 60, 89);
INSERT INTO dim.acs_travel_time_group (acs_travel_time_group_id, name, lower_bound, upper_bound) VALUES (7, '90 minutes or more', 90, 180);

INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (1, 'Under 5', 'Under 10', 0, 4, 0, 9);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (2, '5 to 9', 'Under 10', 5, 9, 0, 9);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (3, '10 to 14', '10 to 19', 10, 14, 10, 19);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (4, '15 to 17', '10 to 19', 15, 17, 10, 19);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (5, '18 and 19', '10 to 19', 18, 19, 10, 19);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (6, '20 to 24', '20 to 29', 20, 24, 20, 29);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (7, '25 to 29', '20 to 29', 25, 29, 20, 29);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (8, '30 to 34', '30 to 39', 30, 34, 30, 39);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (9, '35 to 39', '30 to 39', 35, 39, 30, 39);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (10, '40 to 44', '40 to 49', 40, 44, 40, 49);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (11, '45 to 49', '40 to 49', 45, 49, 40, 49);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (12, '50 to 54', '50 to 59', 50, 54, 50, 59);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (13, '55 to 59', '50 to 59', 55, 59, 50, 59);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (14, '60 and 61', '60 to 69', 60, 61, 60, 69);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (15, '62 to 64', '60 to 69', 62, 64, 60, 69);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (16, '65 to 69', '60 to 69', 65, 69, 60, 69);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (17, '70 to 74', '70 to 79', 70, 74, 70, 79);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (18, '75 to 79', '70 to 79', 75, 79, 70, 79);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (19, '80 to 84', '80+', 80, 84, 80, 100);
INSERT INTO dim.age_group (age_group_id, name, group_10yr, lower_bound, upper_bound, group_10yr_lower_bound, group_10yr_upper_bound) VALUES (20, '85 and Older', '80+', 85, 100, 80, 100);

INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (2, 2, '2010 Estimates', 2010, 'Jan. 1, 2010 population and housing estimates', TRUE, '2010', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (3, 2, '2011 Estimates', 2011, 'Jan. 1, 2011 population and housing estimates', TRUE, '2011', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (4, 2, '2012 Estimates', 2012, 'Jan. 1, 2012 population and housing estimates', TRUE, '2012', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (5, 1, '2010 Census', 2010, 'SANDAG 2010 Census Base', TRUE, '2010', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (6, 3, 'Series 12 - 2050 Forecast', 2011, 'Series 12 Growth Forecast', TRUE, 'Series 12 (2011)', 12);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (7, 3, 'Series 13 Draft - San Diego Foreward Forecast', 2015, 'Draft San Diego Forward RTP Subregional Forecast', TRUE, 'Draft Series 13 (2015)', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (9, 1, '2010 5-Year ACS', 2010, '2006-2010 American Community Survey', FALSE, '2010', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (10, 2, '2013 Estimates', 2013, 'Jan. 1, 2013 population and housing estimates', TRUE, '2013', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (11, 3, 'Series 11 Forecast', 2004, 'Series 11 Growth Forecast', TRUE, 'Series 11 (2004)', 11);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (12, 1, '2000 Census', 2000, 'SANDAG 2000 Census Base', TRUE, '2000', 10);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (13, 3, 'Series 13 Final - San Diego Forward Forecast', 2015, 'Final San Diego Forward RTP Subregional Forecast', TRUE, 'Series 13 (2015)', 13);
INSERT INTO dim.datasource (datasource_id, datasource_type_id, name, yr, description, is_active, displayed_name, series) VALUES (14, 2, '2014 Estimates', 2014, 'Jan. 1, 2014 population and housing estimates', TRUE, '2014', 13);

INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (1, 'mil', 'Military', FALSE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (2, 'agmin', 'Agriculture and Mining', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (3, 'cons', 'Construction', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (4, 'mfg', 'Manufacturing', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (5, 'whtrade', 'Wholesale Trade', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (6, 'retrade', 'Retail Trade', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (7, 'twu', 'Transporation, Warehousing, and Utilities', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (8, 'info', 'Information Systems', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (9, 'fre', 'Finance and Real Estate', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (10, 'pbs', 'Professional and Business Services', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (11, 'edhs', 'Education and Healthcare', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (12, 'lh', 'Liesure and Hospitality', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (13, 'os', 'Office Services', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (14, 'gov', 'Government', TRUE);
INSERT INTO dim.employment_type (employment_type_id, short_name, full_name, civilian) VALUES (15, 'sedw', 'Self-Employed', TRUE);

INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (1, 'hisp', TRUE, 'Hispanic', 'Hispanic');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (2, 'nhw', FALSE, 'White', 'Non-Hispanic, White');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (3, 'nhb', FALSE, 'Black', 'Non-Hispanic, Black');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (4, 'nhai', FALSE, 'American Indian', 'Non-Hispanic, American Indian or Alaska Native');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (5, 'nha', FALSE, 'Asian', 'Non-Hispanic, Asian');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (6, 'nhh', FALSE, 'Pacific Islander', 'Non-Hispanic, Hawaiian or Pacific Islander');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (7, 'nho', FALSE, 'Other', 'Non-Hispanic, Other');
INSERT INTO dim.ethnicity (ethnicity_id, code, hispanic, short_name, long_name) VALUES (8, 'nh2m', FALSE, 'Two or More', 'Non-Hispanic, Two or More Races');

INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62008, 6, 2008);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62020, 6, 2020);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62025, 6, 2025);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62030, 6, 2030);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62035, 6, 2035);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62040, 6, 2040);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62045, 6, 2045);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (62050, 6, 2050);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132012, 13, 2012);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132020, 13, 2020);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132025, 13, 2025);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132030, 13, 2030);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132035, 13, 2035);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132040, 13, 2040);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132045, 13, 2045);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (132050, 13, 2050);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (22010, 2, 2010);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (32011, 3, 2011);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (42012, 4, 2012);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (102013, 10, 2013);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (142014, 14, 2014);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (122000, 12, 2000);
INSERT INTO dim.forecast_year(forecast_year_id, datasource_id, yr) VALUES (52010, 5, 2010);

INSERT INTO dim.housing_type (housing_type_id, short_name, long_name) VALUES (1, 'hh', 'Household');
INSERT INTO dim.housing_type (housing_type_id, short_name, long_name) VALUES (2, 'gq_mil', 'Group Quarters - Military');
INSERT INTO dim.housing_type (housing_type_id, short_name, long_name) VALUES (3, 'gq_college', 'Group Quarters - College');
INSERT INTO dim.housing_type (housing_type_id, short_name, long_name) VALUES (4, 'gq_other', 'Group Quarters - Other');

INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (1, 1, 'Less than $10,000', 1999, 0, 9999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (2, 2, '$10,000 to $19,999', 1999, 10000, 19999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (3, 3, '$20,000 to $29,999', 1999, 20000, 29999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (4, 4, '$30,000 to $39,999', 1999, 30000, 39999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (5, 5, '$40,000 to $49,999', 1999, 40000, 49999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (6, 6, '$50,000 to $59,999', 1999, 50000, 59999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (7, 7, '$60,000 to $74,999', 1999, 60000, 74999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (8, 8, '$75,000 to $99,999', 1999, 75000, 99999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (9, 9, '$100,000 to $149,999', 1999, 100000, 149999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (10, 10, '$150,000 or more', 1999, 150000, 349999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (11, 1, 'Less than $15,000', 2010, 0, 14999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (12, 2, '$15,000 to $29,999', 2010, 15000, 29999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (13, 3, '$30,000 to $44,999', 2010, 30000, 44999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (14, 4, '$45,000 to $59,999', 2010, 45000, 59999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (15, 5, '$60,000 to $74,999', 2010, 60000, 74999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (16, 6, '$75,000 to $99,999', 2010, 75000, 99999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (17, 7, '$100,000 to $124,999', 2010, 100000, 124999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (18, 8, '$125,000 to $149,999', 2010, 125000, 149999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (19, 9, '$150,000 to $199,999', 2010, 150000, 199999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (20, 10, '$200,000 or more', 2010, 200000, 349999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (21, 1, 'Less than $15,000', 1999, 0, 14999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (22, 2, '$15,000 to $29,999', 1999, 15000, 29999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (23, 3, '$30,000 to $44,999', 1999, 30000, 44999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (24, 4, '$45,000 to $59,999', 1999, 45000, 59999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (25, 5, '$60,000 to $74,999', 1999, 60000, 74999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (26, 6, '$75,000 to $99,999', 1999, 75000, 99999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (27, 7, '$100,000 to $124,999', 1999, 100000, 124999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (28, 8, '$125,000 to $149,999', 1999, 125000, 149999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (29, 9, '$150,000 to $199,999', 1999, 150000, 199999);
INSERT INTO dim.income_group (income_group_id, income_group, name, constant_dollars_year, lower_bound, upper_bound) VALUES (30, 10, '$200,000 or more', 1999, 200000, 349999);


INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (1, 'ldsf', 'Low Density Single Family');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (2, 'sf', 'Single Family');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (3, 'mf', 'Multiple Family');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (4, 'mh', 'Mobile Homes');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (5, 'oth', 'Other Residential');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (6, 'ag', 'Agricultural and Extractive');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (7, 'indus', 'Industrial');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (8, 'comm', 'Commercial / Services');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (9, 'office', 'Office');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (10, 'schools', 'Schools');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (11, 'roads', 'Roads and Freeways');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (12, 'parks', 'Parks');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (13, 'mil', 'Military');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (14, 'water', 'Water');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (15, 'mixed_use', 'Mixed Use');
INSERT INTO dim.lu_group (lu_group_id, short_name, full_name) VALUES (16, 'constrained', 'Constrained');

--MGRA INSERTS
COPY dim.mgra (mgra_id, mgra, series, geotype, geozone) FROM '' WITH CSV DELIMITER ',' HEADER;

INSERT INTO dim.sex (sex_id, sex, abbreviation) VALUES (1, 'Female', 'F');
INSERT INTO dim.sex (sex_id, sex, abbreviation) VALUES (2, 'Male', 'M');

INSERT INTO dim.structure_type (structure_type_id, short_name, long_name) VALUES (1, 'sf', 'Single Family - Detached');
INSERT INTO dim.structure_type (structure_type_id, short_name, long_name) VALUES (2, 'sfmu', 'Single Family - Multiple Unit');
INSERT INTO dim.structure_type (structure_type_id, short_name, long_name) VALUES (3, 'mf', 'Multifamily');
INSERT INTO dim.structure_type (structure_type_id, short_name, long_name) VALUES (4, 'mh', 'Mobile Home');

COPY fact.acs_contract_rent (datasource_id, yr, mgra_id, acs_rent_group_id, households)
FROM ''
WITH  CSV DELIMITER ',' HEADER;

COPY fact.acs_employment_status (datasource_id, yr, mgra_id, sex_id, acs_employment_status_type_id, population)
FROM ''
WITH  CSV DELIMITER ',' HEADER;

COPY fact.acs_housing_units (datasource_id, yr, mgra_id, acs_structure_type_id, hs)
FROM ''
WITH CSV DELIMITER ',' HEADER;

COPY fact.acs_housing_value (datasource_id, yr, mgra_id, housing_value_group, households)
FROM ''
WITH CSV DELIMITER ',' HEADER;

COPY fact.acs_travel_time_to_work (datasource_id, yr, mgra_id, acs_travel_time_group_id, workers)
FROM ''
WITH CSV DELIMITER ',' HEADER;

COPY fact.acs_profile (
	datasource_id, yr, mgra_id, pop_15plus,	pop_15plus_no_married, pop_15plus_married, pop_15plus_separated, pop_15plus_widowed, pop_15plus_divorced,
	pop_5plus, pop_5plus_english, pop_5plus_spanish, pop_5plus_spanish_english,	pop_5plus_spanish_no_english, pop_5plus_asian, pop_5plus_asian_english,
	pop_5plus_asian_no_english,	pop_5plus_other, pop_5plus_other_english, pop_5plus_other_no_english, pop_25plus_edu, pop_25plus_edu_lt9, 
	pop_25plus_edu_9to12_no_degree,	pop_25plus_edu_high_school,	pop_25plus_edu_col_no_degree, pop_25plus_edu_associate,	pop_25plus_edu_bachelor,
	pop_25plus_edu_master, pop_25plus_edu_prof,	pop_25plus_edu_doctorate, pop_3plus, pop_3plus_sch_pre, pop_3plus_sch_Kto4,	pop_3plus_sch_5to8,
	pop_3plus_sch_9to12, pop_3plus_sch_college, pop_3plus_sch_grad,	pop_3plus_no_enroll, pop_3plus_sch_public, pop_3plus_sch_pre_public, pop_3plus_sch_Kto4_public,
	pop_3plus_sch_5to8_public, pop_3plus_sch_9to12_public, pop_3plus_sch_college_public, pop_3plus_sch_grad_public, pop_3plus_sch_private, pop_3plus_sch_pre_private,
	pop_3plus_sch_Kto4_private, pop_3plus_sch_5to8_private,	pop_3plus_sch_9to12_private, pop_3plus_sch_college_private,	pop_3plus_sch_grad_private,
	hh_fam_mar,	hh_fam_oth,	hh_fam_oth_m, hh_fam_oth_f,	hh_fam_u18,	hh_fam_mar_u18,	hh_fam_oth_u18,	hh_fam_oth_f_u18, hh_nfam_u18, hh_fam_n18, hh_fam_mar_n18,
	hh_fam_oth_n18,	hh_fam_oth_m_n18, hh_fam_oth_f_n18,	hh_nfam_n18, hh_nfam_alone,	hh_nfam_other, hh_fam_oth_m_u18, hh_owner_value, hh_owner_value_lt150K,
	hh_owner_value_150Kto200K, hh_owner_value_200Kto250K, hh_owner_value_250Kto300K, hh_owner_value_300Kto400K, hh_owner_value_400Kto500K, hh_owner_value_500Kto750K,
	hh_owner_value_750Kto1M, hh_owner_value_1Mplus, hs, hs_2005later, hs_2000to2004, hs_1990to1999, hs_1980to1989, hs_1970to1979, hs_1960to1969, hs_1950to1959,
	hs_1940to1949, hs_1939before, hh_occupant_lt1, hh_occupant_1to1dot5, hh_occupant_1dot5to2, hh_occupant_2more, hh_rent_occupant_lt1,	hh_rent_occupant_1to1dot5,
	hh_rent_occupant_1dot5to2, hh_rent_occupant_2more, hh_own_occupant_lt1, hh_own_occupant_1to1dot5, hh_own_occupant_1dot5to2, hh_own_occupant_2more,
	hh_rent, hh_rent_lt500,	hh_rent_500to599, hh_rent_600to699, hh_rent_700to799, hh_rent_800to899, hh_rent_900to999, hh_rent_1000to1249, hh_rent_1250to1499,
	hh_rent_1500to1999,	hh_rent_2000plus, hh_rent_nocash, hh_rent_income_lt20pct, hh_rent_income_20to25pct, hh_rent_income_25to30pct, hh_rent_income_30to35pct,
	hh_rent_income_35to40pct, hh_rent_income_40to50pct,	hh_rent_income_50pct_plus, hh_rent_income_nocomputed, hh_no_auto, hh_auto_1, hh_auto_2,	hh_auto_3,
	hh_auto_4plus, emp_place, emp_place_in_state, emp_place_in_state_in_county, emp_place_in_state_out_county, emp_place_out_state, emp_mode_auto, 
	emp_mode_auto_drive_alone, emp_mode_auto_carpool, emp_mode_transit,	emp_mode_transit_bus, emp_mode_transit_trolley,	emp_mode_transit_rail, emp_mode_transit_others,
	emp_mode_motor, emp_mode_bike, emp_mode_walk, emp_mode_others, emp_mode_home, emp_travel_no_home, emp_travel_no_home_lt10min, emp_travel_no_home_10to19min,
	emp_travel_no_home_20to29min, emp_travel_no_home_30to44min, emp_travel_no_home_45to59min, emp_travel_no_home_60to89min,	emp_travel_no_home_90plusmin,
	emp_occu_mgmt_prof,	emp_occu_mgmt_prof_management, emp_occu_mgmt_prof_business,	emp_occu_mgmt_prof_computer, emp_occu_mgmt_prof_engineer,
	emp_occu_mgmt_prof_science, emp_occu_mgmt_prof_service, emp_occu_mgmt_prof_legal, emp_occu_mgmt_prof_education, emp_occu_mgmt_prof_art,	emp_occu_mgmt_prof_health,
	emp_occu_service, emp_occu_service_health, emp_occu_service_protective, emp_occu_service_food, emp_occu_service_maint, emp_occu_service_person,	emp_occu_saleoffice,
	emp_occu_farmfishing, emp_occu_construction, emp_occu_transport, emp_occu, emp_indu_agriculture, emp_indu_utilities, emp_indu_construnction, emp_indu_manufacturing,
	emp_indu_wholesale, emp_indu_retail, emp_indu_transport, emp_indu_communication, emp_indu_finance, emp_indu_professional, emp_indu_education, emp_indu_art,
	emp_indu_other_services, emp_indu_public, hh_inc_earning, hh_inc_wage, hh_inc_self_emp, hh_inc_interest, hh_inc_social, hh_inc_supplemental, hh_inc_public,
	hh_inc_retirement, hh_inc_other, pop_poverty_lt0dot5, pop_poverty_0dot5to0dot99, pop_poverty_1to1dot24, pop_poverty_1dot25to1dot49, pop_poverty_1dot5to1dot84,
	pop_poverty_1dot85to1dot99, pop_poverty_2plus, pop_poverty, pop_poverty_above, pop_poverty_below, hh_fam_above_poverty, hh_fam_mar_above_poverty, 
	hh_fam_m_above_poverty,	hh_fam_f_above_poverty,	hh_fam_n18_above_poverty, hh_fam_mar_n18_above_poverty, hh_fam_m_n18_above_poverty, hh_fam_f_n18_above_poverty,
	hh_fam_u18_above_poverty, hh_fam_mar_u18_above_poverty, hh_fam_m_u18_above_poverty,	hh_fam_f_u18_above_poverty,	hh_fam_below_poverty, hh_fam_mar_below_poverty,
	hh_fam_m_below_poverty,	hh_fam_f_below_poverty,	hh_fam_n18_below_poverty, hh_fam_mar_n18_below_poverty,	hh_fam_m_n18_below_poverty,	hh_fam_f_n18_below_poverty,
	hh_fam_u18_below_poverty, hh_fam_mar_u18_below_poverty,	hh_fam_m_u18_below_poverty,	hh_fam_f_u18_below_poverty, hh, hh_fam,	hh_nfam, hh_u18, hh_n18,
	hh_own, profile_id)
FROM 'E:/Apps/DataSurfer/api/utilities/fact_acs_profile_data.csv'
WITH CSV DELIMITER ',' HEADER;

	
COPY fact.household_income (datasource_id, yr, mgra_id, income_group_id, households)
  FROM 'E:/Apps/DataSurfer/api/utilities/fact_household_income_data.csv' WITH CSV DELIMITER ',' HEADER;  
  
COPY fact.housing (datasource_id, yr, mgra_id, structure_type_id, units, occupied, vacancy)
  FROM 'E:/Apps/DataSurfer/api/utilities/fact_housing_data.csv' WITH CSV DELIMITER ',' HEADER;
  
COPY fact.jobs_load (datasource_id, yr, mgra_id, employment_type_id, jobs)
  FROM 'E:/Apps/DataSurfer/api/utilities/fact_jobs_data.csv' WITH CSV DELIMITER ',' HEADER;
  
INSERT INTO fact.jobs_ds6 SELECT * FROM fact.jobs_load WHERE datasource_id = 6 and jobs > 0;
INSERT INTO fact.jobs_ds13 SELECT * FROM fact.jobs_load WHERE datasource_id = 13 and jobs > 0;

DROP TABLE fact.jobs_load;
  
COPY fact.population (datasource_id, yr, mgra_id, housing_type_id, population)
  FROM 'E:/Apps/DataSurfer/api/utilities/fact_population_data.csv' WITH CSV DELIMITER ',' HEADER;
  
COPY fact.age_sex_ethnicity_load (datasource_id, yr, mgra_id, age_group_id, sex_id, ethnicity_id, population)
  FROM 'E:/Apps/DataSurfer/api/utilities/fact_age_sex_ethnicity_data.csv' WITH CSV DELIMITER ',' HEADER;

INSERT INTO fact.age_sex_ethnicity_ds10 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 10 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds12 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 12 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds13 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 13 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds14 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 14 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds2 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 2 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds3 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 3 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds4 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 4 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds5 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 5 and population > 0;
INSERT INTO fact.age_sex_ethnicity_ds6 SELECT * FROM fact.age_sex_ethnicity_load WHERE datasource_id = 6 and population > 0;

DROP TABLE fact.age_sex_ethnicity_load; 

ALTER TABLE fact.acs_profile ADD CONSTRAINT pk_acs_profile PRIMARY KEY (acs_profile_id);
ALTER TABLE fact.household_income ADD CONSTRAINT pk_household_income PRIMARY KEY (household_income_id);
ALTER TABLE fact.housing ADD CONSTRAINT pk_housing PRIMARY KEY (housing_id);
ALTER TABLE fact.jobs ADD CONSTRAINT pk_jobs PRIMARY KEY (jobs_id);
ALTER TABLE fact.population ADD CONSTRAINT pk_population PRIMARY KEY (population_id);
ALTER TABLE fact.age_sex_ethnicity ADD CONSTRAINT pk_age_sex_ethnicity PRIMARY KEY (age_sex_ethnicity_id);

CREATE INDEX ix_mgra_geotype_geozone ON dim.mgra (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

CREATE INDEX ix_acs_contract_rent ON fact.acs_contract_rent (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_acs_employment_status ON fact.acs_employment_status (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_acs_housing_units ON fact.acs_housing_units (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_acs_housing_value ON fact.acs_housing_value (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;

CREATE INDEX ix_houshold_income_datasource_mgra ON fact.household_income (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_housing_datasource_mgra ON fact.housing (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_jobs_datasource_mgra_civilian ON fact.jobs (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_population_datasource_mgra ON fact.population (datasource_id, mgra_id) TABLESPACE datasurfer_tablespace;
  
CREATE INDEX ix_age_ds10_mgra ON fact.age_sex_ethnicity_ds10 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds12_mgra ON fact.age_sex_ethnicity_ds12 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds13_mgra ON fact.age_sex_ethnicity_ds13 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds14_mgra ON fact.age_sex_ethnicity_ds14 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds2_mgra ON fact.age_sex_ethnicity_ds2 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds3_mgra ON fact.age_sex_ethnicity_ds3 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds4_mgra ON fact.age_sex_ethnicity_ds4 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds5_mgra ON fact.age_sex_ethnicity_ds5 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_age_ds6_mgra ON fact.age_sex_ethnicity_ds6 (mgra_id) TABLESPACE datasurfer_tablespace;

CREATE INDEX ix_jobs_ds6_mgra ON fact.jobs_ds6 (mgra_id) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_jobs_ds13_mgra ON fact.jobs_ds13 (mgra_id) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_age
(
  datasource_id smallint not null
  ,geotype varchar(50) not null
  ,geozone varchar(50) not null
  ,yr smallint not null
  ,sex varchar(6) not null
  ,age_group varchar(17) not null
  ,population int not null
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_age_ds2 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds3 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds4 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds5 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds6 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds10 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds12 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds13 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_age_ds14 () INHERITS (fact.summary_age) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_age_ds2 ADD CONSTRAINT chk_summary_age_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_age_ds3 ADD CONSTRAINT chk_summary_age_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_age_ds4 ADD CONSTRAINT chk_summary_age_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_age_ds5 ADD CONSTRAINT chk_summary_age_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_age_ds6 ADD CONSTRAINT chk_summary_age_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.summary_age_ds10 ADD CONSTRAINT chk_summary_age_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_age_ds12 ADD CONSTRAINT chk_summary_age_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_age_ds13 ADD CONSTRAINT chk_summary_age_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_age_ds14 ADD CONSTRAINT chk_summary_age_ds14 CHECK (datasource_id = 14);

CREATE TABLE IF NOT EXISTS fact.summary_ethnicity
(
  datasource_id smallint not null
  ,geotype varchar(50) not null
  ,geozone varchar(50) not null
  ,yr smallint not null
  ,ethnic varchar (35) not null
  ,population int not null
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds2 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds3 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds4 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds5 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds6 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds10 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds12 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds13 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_ds14 () INHERITS (fact.summary_ethnicity) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_ethnicity_ds2 ADD CONSTRAINT chk_summary_ethnicity_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_ethnicity_ds3 ADD CONSTRAINT chk_summary_ethnicity_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_ethnicity_ds4 ADD CONSTRAINT chk_summary_ethnicity_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_ethnicity_ds5 ADD CONSTRAINT chk_summary_ethnicity_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_ethnicity_ds6 ADD CONSTRAINT chk_summary_ethnicity_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.summary_ethnicity_ds10 ADD CONSTRAINT chk_summary_ethnicity_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_ethnicity_ds12 ADD CONSTRAINT chk_summary_ethnicity_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_ethnicity_ds13 ADD CONSTRAINT chk_summary_ethnicity_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_ethnicity_ds14 ADD CONSTRAINT chk_summary_ethnicity_ds14 CHECK (datasource_id = 14);

CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_change
(
  datasource_id smallint NOT NULL,
  geotype character varying(50) NOT NULL,
  geozone character varying(50) NOT NULL,
  ethnicity character varying(35),
  pct_chg_byear_to_2020 double precision,
  pct_chg_2020_to_2025 double precision,
  pct_chg_2025_to_2030 double precision,
  pct_chg_2030_to_2035 double precision,
  pct_chg_2035_to_2040 double precision,
  pct_chg_2040_to_2045 double precision,
  pct_chg_2045_to_2050 double precision,
  pct_chg_base_to_horizon double precision
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_change_ds6 () INHERITS (fact.summary_ethnicity_change) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_ethnicity_change_ds13 () INHERITS (fact.summary_ethnicity_change) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_ethnicity_change_ds6 ADD CONSTRAINT chk_summary_ethnicity_chg_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.summary_ethnicity_change_ds13 ADD CONSTRAINT chk_summary_ethnicity_chg_ds13 CHECK (datasource_id = 13);

CREATE TABLE IF NOT EXISTS fact.summary_jobs
(
  datasource_id smallint NOT NULL,
  geotype character varying(50) NOT NULL,
  geozone character varying(50) NOT NULL,
  yr smallint NOT NULL,
  employment_type character varying(45) NOT NULL,
  jobs integer NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_jobs_ds6 () INHERITS (fact.summary_jobs) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_jobs_ds13 () INHERITS (fact.summary_jobs) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_jobs_ds6 ADD CONSTRAINT chk_summary_jobs_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.summary_jobs_ds13 ADD CONSTRAINT chk_summary_jobs_ds6 CHECK (datasource_id = 13);

CREATE INDEX ix_summary_jobs_ds6_geotype_geozone ON fact.summary_jobs_ds6 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;
CREATE INDEX ix_summary_jobs_ds13_geotype_geozone ON fact.summary_jobs_ds13 (geotype, lower(geozone)) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_housing
(
  datasource_id smallint NOT NULL
  ,geotype varchar(50)  NOT NULL
  ,geozone varchar(50) NOT NULL
  ,yr smallint NOT NULL
  ,unit_type varchar(35) NOT NULL
  ,units bigint NOT NULL
  ,occupied bigint NOT NULL
  ,unoccupied bigint NOT NULL
  ,vacancy_rate double precision NOT NULL
) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_housing_ds2 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds3 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds4 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds5 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds6 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;

CREATE TABLE IF NOT EXISTS fact.summary_housing_ds10 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds12 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds13 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;
CREATE TABLE IF NOT EXISTS fact.summary_housing_ds14 () INHERITS (fact.summary_housing) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_housing_ds2 ADD CONSTRAINT chk_summary_housing_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_housing_ds3 ADD CONSTRAINT chk_summary_housing_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_housing_ds4 ADD CONSTRAINT chk_summary_housing_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_housing_ds5 ADD CONSTRAINT chk_summary_housing_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_housing_ds6 ADD CONSTRAINT chk_summary_housing_ds6 CHECK (datasource_id = 6);

ALTER TABLE fact.summary_housing_ds10 ADD CONSTRAINT chk_summary_housing_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_housing_ds12 ADD CONSTRAINT chk_summary_housing_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_housing_ds13 ADD CONSTRAINT chk_summary_housing_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_housing_ds14 ADD CONSTRAINT chk_summary_housing_ds14 CHECK (datasource_id = 14);

CREATE TABLE fact.summary_population
(
  datasource_id smallint
  ,geotype varchar(50)
  ,geozone varchar(50)
  ,yr smallint
  ,housing_type varchar(35)
  ,population integer
) TABLESPACE datasurfer_tablespace;

CREATE TABLE fact.summary_population_ds2 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds3 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds4 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds5 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds6 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;

CREATE TABLE fact.summary_population_ds10 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds12 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds13 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_population_ds14 () INHERITS (fact.summary_population) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_population_ds2 ADD CONSTRAINT chk_summary_population_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_population_ds3 ADD CONSTRAINT chk_summary_population_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_population_ds4 ADD CONSTRAINT chk_summary_population_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_population_ds5 ADD CONSTRAINT chk_summary_population_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_population_ds6 ADD CONSTRAINT chk_summary_population_ds6 CHECK (datasource_id = 6);

ALTER TABLE fact.summary_population_ds10 ADD CONSTRAINT chk_summary_population_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_population_ds12 ADD CONSTRAINT chk_summary_population_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_population_ds13 ADD CONSTRAINT chk_summary_population_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_population_ds14 ADD CONSTRAINT chk_summary_population_ds14 CHECK (datasource_id = 14);

CREATE TABLE fact.summary_income (
  datasource_id integer
  ,geotype varchar
  ,geozone varchar
  ,yr smallint
  ,ordinal int
  ,income_group varchar
  ,households bigint
) TABLESPACE datasurfer_tablespace;

CREATE TABLE fact.summary_income_ds2 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds3 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds4 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds5 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds6 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;

CREATE TABLE fact.summary_income_ds10 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds12 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds13 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_ds14 () INHERITS (fact.summary_income) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_income_ds2 ADD CONSTRAINT chk_summary_income_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_income_ds3 ADD CONSTRAINT chk_summary_income_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_income_ds4 ADD CONSTRAINT chk_summary_income_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_income_ds5 ADD CONSTRAINT chk_summary_income_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_income_ds6 ADD CONSTRAINT chk_summary_income_ds6 CHECK (datasource_id = 6);

ALTER TABLE fact.summary_income_ds10 ADD CONSTRAINT chk_summary_income_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_income_ds12 ADD CONSTRAINT chk_summary_income_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_income_ds13 ADD CONSTRAINT chk_summary_income_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_income_ds14 ADD CONSTRAINT chk_summary_income_ds14 CHECK (datasource_id = 14);

CREATE TABLE fact.summary_income_median (
  datasource_id smallint
  ,geotype varchar(50)
  ,geozone varchar(50)
  ,yr smallint
  ,median_inc int
) TABLESPACE datasurfer_tablespace;

CREATE TABLE fact.summary_income_median_ds2 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds3 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds4 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds5 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds6 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds10 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds12 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds13 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;
CREATE TABLE fact.summary_income_median_ds14 () INHERITS (fact.summary_income_median) TABLESPACE datasurfer_tablespace;

ALTER TABLE fact.summary_income_median_ds2 ADD CONSTRAINT chk_summary_income_median_ds2 CHECK (datasource_id = 2);
ALTER TABLE fact.summary_income_median_ds3 ADD CONSTRAINT chk_summary_income_median_ds3 CHECK (datasource_id = 3);
ALTER TABLE fact.summary_income_median_ds4 ADD CONSTRAINT chk_summary_income_median_ds4 CHECK (datasource_id = 4);
ALTER TABLE fact.summary_income_median_ds5 ADD CONSTRAINT chk_summary_income_median_ds5 CHECK (datasource_id = 5);
ALTER TABLE fact.summary_income_median_ds6 ADD CONSTRAINT chk_summary_income_median_ds6 CHECK (datasource_id = 6);
ALTER TABLE fact.summary_income_median_ds10 ADD CONSTRAINT chk_summary_income_median_ds10 CHECK (datasource_id = 10);
ALTER TABLE fact.summary_income_median_ds12 ADD CONSTRAINT chk_summary_income_median_ds12 CHECK (datasource_id = 12);
ALTER TABLE fact.summary_income_median_ds13 ADD CONSTRAINT chk_summary_income_median_ds13 CHECK (datasource_id = 13);
ALTER TABLE fact.summary_income_median_ds14 ADD CONSTRAINT chk_summary_income_median_ds14 CHECK (datasource_id = 14);