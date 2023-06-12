<?php
if (empty($_GET['_id'])) {
    $newid=new MongoDB\BSON\ObjectID();
    header('Location: ?_id='.$newid);
    exit();
}
require '../common2.php';
$dataset=new dataset(
    [
    'collection'=>$m->{$config['sitedb']}->carousel,
    '_id'=>$_GET['_id'],
    'simpleid'=>false,
    'nameprefix'=>'data']
);


$title=new inputtext(['dataset'=>&$dataset,'field'=>'title','caption'=>'Titulo:',]);
$background=new inputtext(['dataset'=>&$dataset,'field'=>'background','caption'=>'Background:',]);
$html=new inputmce(['dataset'=>&$dataset,'field'=>'html','caption'=>'Html:',]);

if ($nframework->isAjax()) {
	$nframework->usecommon=false;
	if ($_POST['op']=='save') {
        $session = $m->startSession();
        $session->startTransaction();
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
	<div class="bg-cyan fg-white p-3"><h4>Slide</h4></div>
	<div class="bg-white p-3">
	<?=secureform()?>
		<div class="grid">
			<div class="row">
				<div class="cell"><?=$title?></div>
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
		