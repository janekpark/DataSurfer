<?php
$dir    = 'download';
$files = scandir($dir);
foreach($files as $current_files){
    if(file_exists($dir."/".$current_files)){
        unlink($dir."/".$current_files);
    }
}
?>
