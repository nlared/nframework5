<?
require 'include.php';
$nframework->usecommon=true;
$webcam =new Webcam();
if(empty($_GET['_id'])){
	$id=$user->_id;
	$webcam->exitpath='/account/myprofile.php';
}else{
	$id=$_GET['_id'];
	$webcam->exitpath='/account/profile.php?_id='.$id;
}
$webcam->path=$_SERVER['DOCUMENT_ROOT'].'/profiles/'.$id.'.png';
echo $webcam;