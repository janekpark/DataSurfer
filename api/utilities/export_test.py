__author__ = 'cdan'

import httplib

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


base_url = r'datasurfer.sandag.org.gerbera.arvixe.com'

tests = [
     r'/api/estimate/2013/zip/92101/92102/92103/export/pdf'
     ,r'/api/estimate/2013/jurisdiction/carlsbad/coronado/chula vista/del mar/escondido/encinitas/oceanside/san marcos/la mesa/lemon grove/san diego/export/pdf'
     ,r'/api/estimate/2013/jurisdiction/carlsbad/coronado/chula vista/del mar/escondido/encinitas/oceanside/san marcos/la mesa/lemon grove/san diego/export/xlsx'
     ,r'/api/forecast/13/msa/central/east county/east suburban/export/pdf'
     ,r'/api/forecast/13/msa/central/east county/east suburban/export/xlsx'
 ]

for test in tests:
    url = base_url + test
    url.replace(' ', '%20')
    status = get_status_code(base_url, test.replace(' ', '%20'))

    print str(status) + ': ' + url