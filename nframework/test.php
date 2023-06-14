<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$errores=[];
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}


$inipath=php_ini_loaded_file();
$archivo=file_get_contents($inipath);
date_default_timezone_set('America/Monterrey');
$exts=get_loaded_extensions();
if(ini_get('display_errors')){
	$errores[]= "display_errors=Off";
}


$memory_limit = ini_get('memory_limit');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//*/
$phpver=number_format((float)phpversion(),1);
$apts=[];

if(!in_array('mongodb',$exts)){
	$apts[]= "php$phpver-mongodb";
}
if(!in_array('intl',$exts)){
		$apts[]= "php$phpver-intl";
}
if(!in_array('gd',$exts)){
		$apts[]= "php$phpver-gd";
}
if(!in_array('curl',$exts)){
		$apts[]= "php$phpver-curl";
}
if(!in_array('pdo_sqlite',$exts)){
		$apts[]= "php$phpver-sqlite";
}
if(!in_array('zip',$exts)){
		$apts[]= "php$phpver-zip";
}
if(!in_array('mongodb',$exts)){
		$errores[]= "";
}
if(!in_array('mbstring',$exts)){
		$apts[]= "php$phpver-mbstring";
}
if(!in_array('libxml',$exts)){
		$apts[]= "php$phpver-xml";
}

if(count($apts)>0){
	$errores[]='sudo apt-get install '.implode(' ',$apts) ;
}

$includespath=get_include_path();


$data = explode("\n", file_get_contents("/proc/meminfo"));
$meminfo = array();
foreach ($data as $line) {
    list($key, $val) = explode(":", $line);
    $meminfo[$key] = trim($val);
}
    


include('config.php');
if(!isset($config)){
	$includepaths[]=explode(':',$includepath);
	//TODO:buscar path
	
	$errores[]='config.php not found OR include_path = "'.$includespath.'"';
}

if ((include 'vendor/autoload.php') != TRUE) {
	$errores[]='composer update --ignore-platform-reqs';
}

if(ini_get('auto_append_file')==''){
	$errores[]='auto_append_file =/var/www/html/includes/append_file.php';
}

if($config['sitedb']==''){
		$errores[]='$config[sitedb] no configurada';
}else{
	try{
		$m = new MongoDB\Client($config['mongo_connection_string']);
		
		
		
		$guest=$m->{$config['sitedb']}->users->findOne(['username'=>'guest']);
		if($guest['_id']==''){
			$errores[]="guest no existe";
			$m->{$config['sitedb']}->users->insertOne(['username'=>'guest']);
			$errores[]="guest creado error solucionado actualiza la pagina";
			$m->{$config['sitedb']}->users->createIndex( [ "username"=> 1 ], [ 'unique'=> true ] );
		}
		
		$admin=$m->{$config['sitedb']}->users->findOne(['username'=>'admin']);
		if($admin['_id']==''){
			$errores[]="admin no existe";
			$adminid=new  MongoDB\BSON\ObjectID();
			$m->{$config['sitedb']}->users->insertOne([
				'username'=>'admin',
				'_id'=>$adminid,
				'password'=>'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec'
			]);
			$errores[]="admin creado error solucionado actualiza la pagina";
		}else{
			$adminid=$admin->_id;
		}
		$gadmin=$m->{$config['sitedb']}->usersgroups->findOne(['name'=>'admins']);
		if($gadmin['_id']==''){
			$m->{$config['sitedb']}->usersgroups->insertOne([
				'name'=>'admins',
				'description'=>'administrators',
				'users'=>[(String)$adminid]
			]);
			$m->{$config['sitedb']}->usersgroups->createIndex( [ "name"=> 1 ], [ 'unique'=> true ] );
		}else{
			if(count($gadmin->users)==0){
				$m->{$config['sitedb']}->usersgroups->updateOne(['name'=>'admins'],[
					'$set'=>[
						'users'=>[(String)$adminid]
					]
				]);
			}
		}
		
		$gadmin=$m->{$config['sitedb']}->usersgroups->findOne(['name'=>'developers']);
		if($gadmin['_id']==''){
			$m->{$config['sitedb']}->usersgroups->insertOne([
				'name'=>'developers',
				'description'=>'developers',
				'users'=>[(String)$adminid]
			]);
		}
		
		
	} catch (Exception $e) {
	   $errores[]= 'Excepción capturada: '.  $e->getMessage();
	}
}

$a=ini_get('post_max_size');
$b=ini_get('upload_max_filesize');
echo date("Y-m-d H:i:s").'<br>
Capacidad de post_max_size:'.$a.'<br>
Capacidad de upload_max_filesize:'.$b.'<br>
Tu capacidad de subida es de :'.
(return_bytes($a)<return_bytes($b)?
$a.' determinada por post_max_size':
$b.' determinada por upload_max_filesize').
'<br>';
if(count($errores)>0){
	foreach ($errores  as $errs){
		echo $errs.'<br>';
	}
}else{
	echo "No se encontraron errores de configuración";
}
require 'include.php';
echo "sid:".session_id();