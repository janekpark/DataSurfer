import json
import time
import urllib2

base_url = 'http://datasurfer.sandag.org/api'
comp_url = 'http://localhost:81/api'

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
            
                for type in ['housing','age','ethnicity','income', 'population']:
                    burl = '%s/%s/%s/%s/%s/%s' % (base_url, datasource, series_id, geo_id, zone_id, type)
                    burl = burl.replace(' ', '%20')
                    bresponse = urllib2.urlopen(burl)
                    bjson = json.load(bresponse)
                    curl = '%s/%s/%s/%s/%s/%s' % (comp_url, datasource, series_id, geo_id, zone_id, type)
                    curl = curl.replace(' ', '%20')
                    cresponse = urllib2.urlopen(curl)
                    cjson = json.load(cresponse)
                    if not (sorted(bjson) == sorted(cjson)):
                        print "Equal: FALSE: " + burl
				
				#'income/median',
                
                # if datasource == 'forecast':
                    # url = '%s/%s/%s/%s/%s/jobs' % (base_url, datasource, series_id, geo_id, zone_id)
                    # url = url.replace(' ', '%20')
                    # print url
                    # response = urllib2.urlopen(url)
                    # url = '%s/%s/%s/%s/%s/ethnicity/change' % (base_url, datasource, series_id, geo_id, zone_id)
                    # url = url.replace(' ', '%20')
                    # print url
                    # response = urllib2.urlopen(url)