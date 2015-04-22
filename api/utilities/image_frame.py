import fnmatch
import os
from PIL import Image

folder_path =  'E:/Apps/DataSurfer/maps'

for dirpath, dirnames, files in os.walk(folder_path):
	for filename in fnmatch.filter(files, '*.jpg'):
		im = Image.open(os.path.join(dirpath,  filename))
		w,h = im.size
		im.crop((90, 50, w-90, h-50)).save(os.path.join('E:/Apps/DataSurfer/maps/crop', filename))
		print os.path.join('E:/Apps/DataSurfer/maps/crop', filename)
		