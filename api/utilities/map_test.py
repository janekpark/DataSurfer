import httplib
import json
import sys
import time
import urllib2

def get_status_code(host, path):
    """ This function retreives the status code of a website by requesting
        HEAD data from the host. This means that it only requests the headers.
        If the host cannot be reached or something else goes wrong, it returns
        None instead.
    """
    try:
        conn = httplib.HTTPConnection(host)
        conn.request("HEAD", path)
        return conn.getresponse().status
    except StandardError:
        return None


base_url = 'http://datasurfer.sandag.org/api'
host = 'http://datasurfer.sandag.org'

with open('e:/apps/datasurfer/api/map_test.log', 'w') as f:

	for datasource in ['forecast']: #'census','estimate','forecast']:
		response = urllib2.urlopen(base_url + '/' + datasource)
		series_api = json.load(response)

		for record in series_api:
			series_id = record['series']
			response = urllib2.urlopen(base_url + '/' + datasource + '/' +str(series_id))
			geo_api = json.load(response)
    
			for geo in geo_api:
				geo_id = geo['zone']
				response = urllib2.urlopen(base_url+ '/' + datasource + '/' +str(series_id)+'/'+ str(geo_id))
				zone_api = json.load(response)
    
				for zone in zone_api:
					zone_id = zone[geo_id]
					zone_path = '/api/' +datasource+ '/' +str(series_id)+'/'+ str(geo_id)+ '/' + str(zone_id) + '/map'	
					zone_path = zone_path.replace(' ','%20')
					status = get_status_code(host[7:], zone_path)
					if 200 == status:
						print "200: " + host + zone_path
					else:
						print "ERROR - " + str(status) + ": " + host + zone_path
						f.write("ERROR - " + str(status) + ": " + host + zone_path + "\n")