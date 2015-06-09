import json
import time
import urllib2

base_url = 'http://datasurfer.sandag.org/api'

for datasource in ['estimate', 'census', 'forecast']:
    response = urllib2.urlopen('%s/%s' % (base_url, datasource))
    series_api = json.load(response)

    for record in series_api:
        series_id = record[1]
        response = urllib2.urlopen('%s/%s/%s' % (base_url, datasource, series_id))
        geo_api = json.load(response)

        for geo in geo_api:
            geo_id = geo[1]
            response = urllib2.urlopen('%s/%s/%s/%s' % (base_url, datasource, series_id, geo_id))
            zone_api = json.load(response)
    
            for zone in zone_api:
                print zone
                zone_id = zone[geo_id]
            
                #for type in ['housing','age','ethnicity','income', 'jobs']:
                for type in ['housing','age','income/median','ethnicity','income', 'population']:
                    url = '%s/%s/%s/%s/%s/%s' % (base_url, datasource, series_id, geo_id, zone_id, type)
                    url = url.replace(' ', '%20')
                    print url
                    response = urllib2.urlopen(url)
                
                if datasource == 'forecast':
                    url = '%s/%s/%s/%s/%s/jobs' % (base_url, datasource, series_id, geo_id, zone_id)
                    url = url.replace(' ', '%20')
                    print url
                    response = urllib2.urlopen(url)
                    url = '%s/%s/%s/%s/%s/ethnicity/change' % (base_url, datasource, series_id, geo_id, zone_id)
                    url = url.replace(' ', '%20')
                    print url
                    response = urllib2.urlopen(url)