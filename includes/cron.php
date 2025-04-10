<?
require 'include.php';

$dateTime = new DateTime('1 month ago'); // Current date and time
$m->{$config['sitedb']}->sessions->deleteMany([
	'last_accessed'=>[
		'$lt'=>new MongoDB\BSON\UTCDateTime($dateTime->getTimestamp() * 1000)
	]
]);
