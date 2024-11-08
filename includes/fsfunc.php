<?

 function delTree($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
    	 (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function formatSize($bytes,$decimals=2){
    $size=array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $factor=floor((strlen($bytes)-1)/3);
    return sprintf("%.{$decimals}f",$bytes/pow(1024,$factor)).@$size[$factor];
}