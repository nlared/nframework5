<?
$lat=($dataset->lat==''?'25.43228030':$dataset->lat);
$lng=($dataset->lng==''?'-101.00447970':$dataset->lng);
$mapa=new mapmarker(['dataset'=>&$dataset,'field'=>'mapa','caption'=>'Mapa:','required'=>true,'onchange'=>'buscard();']);
$cp=new inputtext(['dataset'=>&$dataset,'field'=>'cp','caption'=>'Codigo Postal:',]);
$estado=new inputtext(['dataset'=>&$dataset,'field'=>'estado','caption'=>'Estado:','data-autocomplete'=>'uno,dos','autocomplete'=>'none','required'=>true]);
$municipio=new inputtext(['dataset'=>&$dataset,'field'=>'municipio','caption'=>'Municipio:','data-autocomplete'=>'uno,dos','autocomplete'=>'none','required'=>true]);
$localidad=new inputtext(['dataset'=>&$dataset,'field'=>'localidad','caption'=>'Localidad (Ciudad):','data-autocomplete'=>'uno,dos','autocomplete'=>'none','required'=>true]);
$asentamiento=new inputtext(['dataset'=>&$dataset,'field'=>'asentamiento','caption'=>'Asentamiento (Colonia, Fraccionamiento, etc..):','data-autocomplete'=>'uno,dos','autocomplete'=>'none','required'=>true]);
$vialidad=new inputtext(['dataset'=>&$dataset,'field'=>'vialidad','caption'=>'Vialidad (Calle, Boulevard, etc..):','data-autocomplete'=>'uno,dos','autocomplete'=>'none','required'=>true]);
$noext=new inputtext(['dataset'=>&$dataset,'field'=>'noext','caption'=>'No. ext:','required'=>true]);
$noint=new inputtext(['dataset'=>&$dataset,'field'=>'noint','caption'=>'No. int:',]);
if ($nframework->isAjax()) {
    if ($_POST['op']=='save') {
    	$dataset->lat=$_POST['data']['mapa']['lat'];
    	$dataset->lng=$_POST['data']['mapa']['lng'];
    }
}

?>