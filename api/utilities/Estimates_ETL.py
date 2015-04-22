__author__ = 'cdan'

import pandas as pd
import sqlalchemy
import pymssql

server = 
database = 
user = 
password = 

year = 2014
ase_sql = 'SELECT 14 as datasource_id, [estimates_year],1300000 + [mgra] as mgra_id,[ethnicity],[popm_0to4],[popm_5to9],[popm_10to14],[popm_15to17],[popm_18to19],[popm_20to24],[popm_25to29],[popm_30to34],[popm_35to39],[popm_40to44],[popm_45to49],[popm_50to54],[popm_55to59],[popm_60to61],[popm_62to64],[popm_65to69],[popm_70to74],[popm_75to79],[popm_80to84],[popm_85plus],[popf_0to4],[popf_5to9],[popf_10to14],[popf_15to17],[popf_18to19],[popf_20to24],[popf_25to29],[popf_30to34],[popf_35to39],[popf_40to44],[popf_45to49],[popf_50to54],[popf_55to59],[popf_60to61],[popf_62to64],[popf_65to69],[popf_70to74],[popf_75to79],[popf_80to84],[popf_85plus] FROM [detailed_pop_tab_mgra] WHERE estimates_year = %d and ethnicity > 0' % year
inc_sql = 'SELECT 14 as datasource_id, [estimates_year],1300000 + [mgra] as mgra_id,[i1],[i2],[i3],[i4],[i5],[i6],[i7],[i8],[i9],[i10] FROM [income_estimates_mgra] where estimates_year = 2014'
housing_sql = '''
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,1 as structure_type_id,[hs_sf] as units,[hh_sf] as occupied,CASE  WHEN hs_sf > 0 THEN 1 - (cast(hh_sf as float) / cast(hs_sf as float)) ELSE null END as vacancy FROM[popest_mgra] WHERE estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,2 as structure_type_id,[hs_sfmu] as units,[hh_sfmu] as occupied,CASE  WHEN hs_sfmu > 0 THEN 1 - (cast(hh_sfmu as float) / cast(hs_sfmu as float)) ELSE null END as vacancy FROM [popest_mgra] WHERE estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,3 as structure_type_id,[hs_mf] as units,[hh_mf] as occupied,CASE  WHEN hs_mf > 0 THEN 1 - (cast(hh_mf as float) / cast(hs_mf as float)) ELSE null END as vacancy FROM [popest_mgra] WHERE estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,4 as structure_type_id,[hs_mh] as units,[hh_mh] as occupied ,CASE  WHEN hs_mh > 0 THEN 1 - (cast(hh_mh as float) / cast(hs_mh as float)) ELSE null END as vacancy FROM [popest_mgra] WHERE estimates_year = %d
    ''' % (year, year, year, year)
population_sql = '''
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,1 as housing_type_id,[hhp] FROM [popest_mgra] where estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,2 as housing_type_id,[gq_mil] FROM [popest_mgra] where estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,3 as housing_type_id,[gq_civ_college] FROM [popest_mgra] where estimates_year = %d
    UNION ALL
    SELECT 14 as datasource_id,[estimates_year],1300000 + [mgra] as mgra_id,4 as housing_type_id,[gq_civ_other] FROM [popest_mgra] where estimates_year = %d
    ''' % (year, year, year, year)

age_range = {'0to4':1,'5to9':2,'10to14':3,'15to17':4,'18to19':5,'20to24':6,'25to29':7,'30to34':8,'35to39':9,'40to44':10,
  '45to49':11,'50to54':12,'55to59':13,'60to61':14,'62to64':15,'65to69':16,'70to74':17,'75to79':18,'80to84':19,'85plus':20}
age_range_df = pd.DataFrame(age_range.items(), columns=['variable','age_group_id'])

with pymssql.connect(server, user, password, database) as conn:
    '''
    ase_df = pd.read_sql_query(ase_sql, conn)

    #Remove the total population records (ethnicity = 0) and unpivot
    ase_df = pd.melt(ase_df, id_vars=['datasource_id','estimates_year','mgra_id', 'ethnicity'])

    #Set the sex, Female = 1, Male = 2
    ase_df['sex_id'] = ase_df['variable'].str.contains('popm') + 1
    ase_df['variable'] = ase_df['variable'].str[5:]

    ase_df = pd.merge(ase_df, age_range_df, on='variable').drop('variable', 1)
    ase_df.columns = ['datasource_id','year', 'mgra_id', 'ethnicity_id', 'population', 'sex_id', 'age_group_id']

    ase_df = ase_df[['datasource_id','year','mgra_id','age_group_id','sex_id','ethnicity_id', 'population']]

    print "Writing ASE Table..."

    ase_df.to_csv('age_sex_ethnicity.csv', index=False)

    print 'Writing ASE Table Complete'

    ################################################################################################################

    income_df = pd.read_sql(inc_sql, conn)

    income_df = pd.melt(income_df, id_vars=['datasource_id', 'estimates_year', 'mgra_id'], var_name='income_group_id', value_name='households')
    income_df['income_group_id'] = income_df['income_group_id'].str[1:].astype(int) + 10

    print "Writing Income Table..."

    income_df.to_csv('income.csv', index=False)

    print 'Writing Income Table Complete'
    '''
    housing_df = pd.read_sql(housing_sql, conn)

    print "Writing Housing Table..."

    housing_df.to_csv('housing.csv', index=False)

    print 'Writing Housing Table Complete'

    population_df = pd.read_sql(population_sql, conn)

    print "Writing Population Table..."

    population_df.to_csv('population.csv', index=False)

    print 'Writing Population Table Complete'


