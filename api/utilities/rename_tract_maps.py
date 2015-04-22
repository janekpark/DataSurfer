import os
import shutil

__author__ = 'cdan'

folder_path = r'E:\Apps\DataSurfer\api\pdf\estimate\2014\tract'

for dirpath, dirnames, files in os.walk(folder_path):
    for file in files:
        os.rename(dirpath + os.sep + file, dirpath + os.sep + file[:-6] + '.' + file[-6:])