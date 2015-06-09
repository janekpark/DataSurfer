import urllib
import urllib2

url = 'http://datasurfer.sandag.org/api/estimate/2013/jurisdiction/export/xlsx'
values = {'zones' : 'escondido,oceanside,vista,solana beach,san diego,san marcos,chula vista,encinitas,el cajon,carlsbad,national city,la mesa,unincorporated,imperial beach,santee,lemon grove,del mar,coronado,poway'}

data = urllib.urlencode(values)
req = urllib2.Request(url, data)
response = urllib2.urlopen(req)
the_page = response.read()

file = open('e:/tmp/data_test.xlsx', 'wb')
file.write(the_page)
file.close