import fnmatch
import os
import shutil
import pymssql

year = 2011

coded_values = {
    #'jurisdiction': ['city','city_id', 'city_name']
    #,'region': ['reg', 'region_id', 'region_name']
    #,'zip': {'', '', '']
    #,'msa': ['msa', 'msa', 'msa_name']
    #,'sra': ['sra', 'sra_id', 'sra_name']
    #,'tract': {'', '', '']
    #'elementary': ['SchoolDistrictElementary', 'elementary', 'elementary_name']
    #,'secondary': ['SchoolDistrictSecondary', 'high_school', 'high_school_name']
    #'unified': ['SchoolDistrictUnified', 'unified', 'unified_name']
    #,'college': ['college', 'community_college', 'community_college_name']
    #,'sdcouncil': {'', '', '']
    #,'supervisorial': {'', '', '']
    'cpa': ['citycpa', 'cpa', 'cpa_name']
    }
    
uncoded_values = {
   #'zip': 'ZipCode'
   #'tract': 'CensusTract'
   #,'sdcouncil': 'council'
   #,'supervisorial': 'supervisorial'
}


server = 
database = 
user = 
password = 

conn = pymssql.connect(server, user, password, database)
cursor = conn.cursor()

folder_path = 'E:/Apps/estimates/estimates_2011_2015_02_06'

for folder, metadata in coded_values.iteritems():
    sql = 'SELECT {0}, {1} FROM dim.mgra WHERE {0} is not null GROUP BY {0}, {1} ORDER BY {0}'.format(metadata[1], metadata[2])
    cursor.execute(sql)
    
    print '---' + folder + '---'
    print folder_path
    
    for row in cursor:
#        if 'cpa' == folder:
#            if row[0]/100 == 14: 
#                metadata[0] = 'citycpa'
#            elif row[0]/100 == 19: 
#                metadata[0] = 'CPACounty'
        base_file = metadata[0] + str(row[0])
        print base_file
        
        for dirpath, dirnames, files in os.walk(folder_path):
            for filename in fnmatch.filter(files, base_file + 'est.pdf'):
                shutil.copy2(os.path.join(dirpath, filename), folder_path + '/' + 'sandag_estimate_' + str(year) + '_' + folder + '_' + row[1] + '.pdf')
                print folder_path + '/' + 'sandag_estimate_' + str(year) + '_' + folder + '_' + row[1] + '.pdf'
                
for folder, prefix in uncoded_values.iteritems():
    sql = 'SELECT {0} FROM dim.mgra WHERE {0} is not null GROUP BY {0} ORDER BY {0}'.format(folder)
    cursor.execute(sql)
    
    print '---' + folder + '---'
    print folder_path + '/' + folder
    
    for row in cursor:
        base_file = prefix + '_' + str(row[0])
        
        for dirpath, dirnames, files in os.walk(folder_path + '/' + folder):
            for filename in fnmatch.filter(files, base_file + '.jpg'):
                shutil.copy2(os.path.join(dirpath, filename), folder_path + '/' + folder + '/' + 'sandag_series_13_' + folder + '_' + str(row[0]) + '.jpg')
                print os.path.join(dirpath, filename)
   
conn.close()