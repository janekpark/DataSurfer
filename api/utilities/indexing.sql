ALTER TABLE [dim].[age_group] ADD CONSTRAINT [PK_age_group] PRIMARY KEY CLUSTERED
(
	[age_group_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
CREATE INDEX [ix_ase_datasource] ON [fact].[age_sex_ethnicity]
(
	[datasource_id]  ASC 
)  INCLUDE ( [mgra_id],[ethnicity_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_ase_datasource_3] ON [fact].[age_sex_ethnicity]
(
	[datasource_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[age_group_id],[sex_id],[ethnicity_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_ase_datasource_age] ON [fact].[age_sex_ethnicity]
(
		[datasource_id]  ASC , [age_group_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[sex_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_ase_datasource_mgra] ON [fact].[age_sex_ethnicity]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [ethnicity_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_ase_datasource_mgra_2] ON [fact].[age_sex_ethnicity]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [year],[age_group_id],[sex_id],[ethnicity_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
ALTER TABLE [dim].[datasource] ADD CONSTRAINT [PK_datasource] PRIMARY KEY CLUSTERED
(
	[datasource_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
ALTER TABLE [dim].[employment_type] ADD CONSTRAINT [PK_employment_type] PRIMARY KEY CLUSTERED
(
	[employment_type_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
ALTER TABLE [dim].[ethnicity] ADD CONSTRAINT [PK_ethnicity] PRIMARY KEY CLUSTERED
(
	[ethnicity_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
CREATE INDEX [ix_hh_inc_datasource] ON [fact].[household_income]
(
	[datasource_id]  ASC 
)  INCLUDE ( [mgra_id],[income_group_id],[households] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_hh_inc_datasouce_mgra] ON [fact].[household_income]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [income_group_id],[households] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_hh_inc_datasource_forecast] ON [fact].[household_income]
(
	[datasource_id]  ASC 
)  INCLUDE ( [yr],[mgra_id],[income_group_id],[households] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_housing_datasource] ON [fact].[housing]
(
	[datasource_id]  ASC 
)  INCLUDE ( [mgra_id],[structure_type_id],[units],[occupied] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_housing_datasource_mgra] ON [fact].[housing]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [structure_type_id],[units],[occupied] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_housing_datasource_forecast] ON [fact].[housing]
(
	[datasource_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[structure_type_id],[units],[occupied] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
ALTER TABLE [dim].[housing_type] ADD CONSTRAINT [PK_housing_type] PRIMARY KEY CLUSTERED
(
	[housing_type_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
ALTER TABLE [dim].[income_group] ADD CONSTRAINT [PK_income_group] PRIMARY KEY CLUSTERED
(
	[income_group_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
CREATE INDEX [ix_jobs_datasource] ON [fact].[jobs]
(
	[datasource_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[employment_type_id],[jobs] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_jobs_datasource_mgra] ON [fact].[jobs]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [year],[employment_type_id],[jobs] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_jobs_datasource_employment_type] ON [fact].[jobs]
(
		[datasource_id]  ASC , [employment_type_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[jobs] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
ALTER TABLE [dim].[mgra] ADD CONSTRAINT [PK_mgra] PRIMARY KEY CLUSTERED
(
	[mgra_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
CREATE INDEX [ix_mgra_city_name] ON [dim].[mgra]
(
	[city_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_region_name] ON [dim].[mgra]
(
	[region_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_zip] ON [dim].[mgra]
(
	[zip]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_msa_name] ON [dim].[mgra]
(
	[msa_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_mgra] ON [dim].[mgra]
(
	[mgra_id]  ASC 
)  INCLUDE ( [city_name],[region_name],[zip],[msa_name],[sra_name],[supervisorial],[cpa_name],[tract],[elementary_name],[high_school_name],[unified_name],[community_college_name],[council] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_sra_name] ON [dim].[mgra]
(
	[sra_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_tract] ON [dim].[mgra]
(
	[tract]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_elementary_name] ON [dim].[mgra]
(
	[elementary_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_high_school_name] ON [dim].[mgra]
(
	[high_school_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_unified_name] ON [dim].[mgra]
(
	[unified_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_community_college_name] ON [dim].[mgra]
(
	[community_college_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_council] ON [dim].[mgra]
(
	[council]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_supervisorial] ON [dim].[mgra]
(
	[supervisorial]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_cpa_name] ON [dim].[mgra]
(
	[cpa_name]  ASC 
)  INCLUDE ( [mgra_id] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_series_region] ON [dim].[mgra]
(
		[series_id]  ASC , [region_name]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_unified] ON [dim].[mgra]
(
	[unified]  ASC 
)  INCLUDE ( [unified_name] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_series_cpa] ON [dim].[mgra]
(
		[series_id]  ASC , [cpa_name]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_series_elem] ON [dim].[mgra]
(
		[series_id]  ASC , [elementary_name]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_mgra_series_city] ON [dim].[mgra]
(
	[series_id]  ASC 
)  INCLUDE ( [city_name] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_population_datasource] ON [fact].[population]
(
	[datasource_id]  ASC 
)  INCLUDE ( [year],[mgra_id],[housing_type_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
CREATE INDEX [ix_population_datasource_mgra] ON [fact].[population]
(
		[datasource_id]  ASC , [mgra_id]  ASC 
)  INCLUDE ( [year],[housing_type_id],[population] ) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF ,DROP_EXISTING = ON )  ON [IX]
ALTER TABLE [dim].[sex] ADD CONSTRAINT [PK_sex] PRIMARY KEY CLUSTERED
(
	[sex_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]
ALTER TABLE [dim].[structure_type] ADD CONSTRAINT [PK_structure_type] PRIMARY KEY CLUSTERED
(
	[structure_type_id]  ASC 
) 

WITH ( PAD_INDEX = OFF, ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, STATISTICS_NORECOMPUTE = OFF )  ON [IX]