import json
import time
import urllib2

base_url = 'http://datasurfer.sandag.org.gerbera.arvixe.com/api'

for datasource in ['forecast','estimate','census']:
    response = urllib2.urlopen(base_url + '/' + datasource)
    series_api = json.load(response)

    for record in series_api:
        series_id = record[1]
        response = urllib2.urlopen(base_url + '/' + datasource + '/' +str(series_id))
        geo_api = json.load(response)
    
        for geo in geo_api:
            geo_id = geo[1]
            response = urllib2.urlopen(base_url+ '/' + datasource + '/' +str(series_id)+'/'+ str(geo_id))
            zone_api = json.load(response)
    
            for zone in zone_api:
                print zone
                zone_id = zone[geo_id]
            
                #for type in ['housing','age','ethnicity','income', 'jobs']:
                for type in ['housing','age','ethnicity','income']:
                    url = base_url+ '/' + datasource + '/' +str(series_id)+'/'+ str(geo_id) + '/' + str(zone_id) + '/' + str(type)
                    url = url.replace(' ', '%20')
                    print url
                    response = urllib2.urlopen(url)
                
                if datasource == 'forecast':
                    url = base_url+ '/' + datasource + '/' +str(series_id)+'/'+ str(geo_id) + '/' + str(zone_id) + '/jobs'
                    url = url.replace(' ', '%20')
                    print url
                    response = urllib2.urlopen(url)