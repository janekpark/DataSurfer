import fnmatch
import os
import shutil
import pymssql

year = 2014

coded_values = {
    #'jurisdiction': ['city','city_id', 'city_name']
    #,'region': ['reg', 'region_id', 'region_name']
    #,'zip': {'', '', '']
    #,'msa': ['msa', 'msa', 'msa_name']
    #,'sra': ['sra', 'sra_id', 'sra_name']
    #,'tract': {'', '', '']
    #,'elementary': ['elem', 'elementary', 'elementary_name']
    #,'secondary': ['high', 'high_school', 'high_school_name']
    #'unified': ['unif', 'unified', 'unified_name']
    #,'college': ['coll', 'community_college', 'community_college_name']
    #,'sdcouncil': {'', '', '']
    #,'supervisorial': {'', '', '']
    #,'cpa': ['citycpa', 'cpa', 'cpa_name']
    }
    
uncoded_values = {
   'zip': 'zip'
   ,'tract': 'ct2010'
   ,'council': 'council'
   #,'supervisorial': 'super'
}


server = 
database =
user = 
password = 

conn = pymssql.connect(server, user, password, database)
cursor = conn.cursor()

folder_path = r'E:\Apps\estimates\estimates_2014_2015_03_18'

for folder, metadata in coded_values.iteritems():
    sql = 'SELECT {0}, {1} FROM dim.mgra WHERE {0} is not null GROUP BY {0}, {1} ORDER BY {0}'.format(metadata[1], metadata[2])
    cursor.execute(sql)
    
    print '---' + folder + '---'
    print folder_path
    
    for row in cursor:
        if 'cpa' == folder:
            if row[0]/100 == 14: 
                metadata[0] = 'citycpa'
            elif row[0]/100 == 19: 
                metadata[0] = 'cocpa'
        base_file = metadata[0] + str(row[0])
        
        for dirpath, dirnames, files in os.walk(folder_path):
            for filename in fnmatch.filter(files, base_file+'est*'):
                shutil.copy2(os.path.join(dirpath, filename), 'e:/apps/datasurfer/api/pdf/estimate/' + str(year) + '/' + folder + '/' + 'sandag_estimate_' + str(year) + '_' + folder + '_' + row[1] + '.pdf')
                print os.path.join(dirpath, filename)
                
for folder, prefix in uncoded_values.iteritems():
    sql = 'SELECT {0} FROM dim.mgra WHERE {0} is not null GROUP BY {0} ORDER BY {0}'.format(folder)
    cursor.execute(sql)
    
    print '---' + folder + '---'
    print folder_path
    
    for row in cursor:
        base_file = prefix + str(row[0])
        
        for dirpath, dirnames, files in os.walk(folder_path):
            for filename in fnmatch.filter(files, base_file+'est*'):
                shutil.copy2(os.path.join(dirpath, filename), 'e:/apps/datasurfer/api/pdf/estimate/' + str(year) + '/' + folder + '/' + 'sandag_estimate_' + str(year) + '_' + folder + '_' + str(row[0]) + '.pdf')
                print os.path.join(dirpath, filename)
   
conn.close()