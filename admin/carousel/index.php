<?php
require '../common2.php';
$developermode=true;
$nframework->usecommon=true;
$datatable=new Table();
$datatable->header='<th>Titulo</th><th>Descripcion</th><th>Opciones</th>';
foreach ($m->{$config['sitedb']}->carousel->find() as $doc) {
    $datatable->data[]=[
        $doc['title'],
        $doc['html'],
        '<a href="slide.php?_id='.$doc['_id'].'"><spam class="mif-pencil"></spam></a>'
        ];
}
?>
<div class="container p-5">
	<div class="bg-cyan fg-white p-3"><h4>Carousel</h4></div>
	<div class="bg-white p-3">
	<a href="slide.php" class="button"><span class="mif-plus"></span> Nuevo</a>		
	<?=$datatable;?>
</div>
</div>
