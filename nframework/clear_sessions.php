<?
$developermode=true;
require 'include.php';
$usersessions=[];
foreach($m->{$config['sitedb']}->users->find([
	
	]) as $doc){
	$usersessions=array_merge($usersessions,(array)$doc->sessions);
}
//print_r($usersessions);

$m->{$config['sitedb']}->sessions->deletemany([
	'_id'=>['$nin'=>$usersessions]
	]);

foreach($m->{$config['sitedb']}->sessions->find() as $doc){
	$count++;
}

print_r($count);