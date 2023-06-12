<?php
if (empty($_GET['_id'])) {
    $newid=new MongoDB\BSON\ObjectID();
    header('Location: ?_id='.$newid);
    exit();
}
require '../common2.php';
$dataset=new dataset(
    [
    'collection'=>$m->{$config['sitedb']}->pages,
    '_id'=>$_GET['_id'],
    'simpleid'=>false,
    'nameprefix'=>'data']
);


$title=new inputtext(['dataset'=>&$dataset,'field'=>'title','caption'=>'Titulo:',]);
$background=new inputtext(['dataset'=>&$dataset,'field'=>'background','caption'=>'Background:',]);
$path=new inputtext(['dataset'=>&$dataset,'field'=>'path','caption'=>'Path:',]);
/*$lang=new select(['dataset'=>&$dataset,'field'=>'lang','caption'=>'Language:','options'=>[
	'es-MX'=>'es-MX',
	'en-US'=>'en-US'
	]]);//*/
$keywords=new inputtext(['dataset'=>&$dataset,'field'=>'keywords','caption'=>'Keywords:',]);
//$html=new inputmce(['dataset'=>&$dataset,'field'=>'html','caption'=>'Html:',]);

if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/media')){
	mkdir($_SERVER["DOCUMENT_ROOT"].'/media',0777,true);
}
$mediadir=realpath($_SERVER["DOCUMENT_ROOT"].'/media').'/';
$html=new inputmce(['dataset'=>&$dataset,'field'=>'html','caption'=>'Html:',
	'mediadir'=>$mediadir,//realpath( '../../sliders').'/'.$id.'/',
	'baseurl'=>'/media/',//'/sliders/'.$id.'/',
	'id'=>'mceid',//md5('/sliders/'.$id.'/'),
	'upload'=>true,
	'extended_valid_elements' => "script[src|async|defer|type|charset]",
	'content_css'=>'https://cdn.korzh.com/metroui/v4.5.1/css/metro-all.min.css'
]);


$description=new inputtext(['dataset'=>&$dataset,'field'=>'description','caption'=>'Description:',]);

$robots=new inputcheckboxs(['dataset'=>&$dataset,'field'=>'robots','caption'=>'Robots:<br>','options'=>[
	'noindex'=>'No index',
	'nofollow'=>'No follow'
	]]);




if ($nframework->isAjax()) {
	$nframework->usecommon=false;
	if ($_POST['op']=='save') {
        $session = $m->startSession();
        $session->startTransaction();
        $dataset->lastmodification=new MongoDB\BSON\UTCDateTime();
        try {
            $result=[
                'error'=>$dataset->save(),
            ];
            $session->commitTransaction();
        } catch (Exception $e) {
            $session->abortTransaction();
            $result=[
            	'error'=>$e->getMessage()
        	];
        }
    }
} else {
	$nframework->usecommon=true;
	
?>
<div class="container p-5">
	<div class="bg-cyan fg-white p-3"><h4>Page</h4></div>
	<div class="bg-white p-3">
	<?=secureform()?>
		<div class="grid">
			<div class="row">
				<div class="cell"><?=$title?></div>
			</div>
			<div class="row">
				<div class="cell"><?=$description?></div>
			</div>
			<div class="row">
				<div class="cell"><?=$keywords?></div>
			</div>
			<div class="row">
				<div class="cell"><?=$path?></div>
				<div class="cell"><?=$lang?></div>
				<div class="cell"><?=$robots?></div>
			</div>
			
			<div class="row">
				<div class="cell"><?=$background?></div>
			</div>
			<div class="row">
				<div class="cell"><?=$html?></div>
			</div>
			<div class="row">
				<div class="cell-md-2 offset-md-8"><a href="./" class="button primary w-100"><span class="mif-exit"></span>&nbsp;Cerrar</a></div>
				<div class="cell-md-2"><button class="button secureop success w-100" value="save"><span class="mif-floppy-disk"></span>&nbsp;Guardar</button></div>
			</div>
		</div>
<?}?>
		