<?php 
require_once 'include.php';
$developermode=true;
$noobfuscate=true;

if($user->username=='guest'){
	exit();
}

$dataset=new dataset([
    'collection'=>$m->{$config['sitedb']}->users,
    'simpleid'=>false,
    '_id'=>$data['_id'],
    'nameprefix'=>'data']
);

$primerap   = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'primerap' ,'caption'=>'Primer Ap.','placeholder'=>'Primer Apellido']);
$segundoap   = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false, 'field'=>'segundoap' ,'caption'=>'Segundo Ap.','placeholder'=>'Segundo Apellido']);
$nombres    = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'nombres'  ,'caption'=>'Nombre(s)','placeholder'=>'Nombre(s)']);
$telefono	= new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'numtel' ,'caption'=>'Telefono','placeholder'=>'Telefono']);
$cel    	= new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'numcel' ,'caption'=>'Celular','placeholder'=>'Celular']);
$username   =($user->can('admin')?
new inputText(['disable'=>true,'dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'username' ,'caption'=>'Correo Electronico','placeholder'=>'email'])
:
$dataset->username);
$nacimiento = new inputDate(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'fhcumpleaños','caption'=>'Fecha Nacimiento','format'=>'%Y-%m-%d','typse'=>'calendarpicker' ]);    
$sexo       = new Select(['dataset'=>$dataset,'caption'=>'Genero','field'=>'sexo','addclass'=>'full-size','required'=>true,'options'=>[''=>'Seleccione..','Masculino'=>'Masculino','Femenino'=>'Femenino']]);    
    

$_SESSION['imagesresize']['usuarios']=[
	'dst'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
	'src'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
	'default'=>$_SERVER['DOCUMENT_ROOT'].'/account/Profile.png'
];


foreach($userincludes as $includetitle=>$includefile){
	include str_replace('.php','.inc.php',$includefile);
}
if ($nframework->isAjax()) {
    if ($_POST['op']=='save') {
		$result=['error'=>$dataset->save()];
		$dataset->nombrecompleto=strtoupper(trim($dataset->nombres.' '.$dataset->primerap.' '.$dataset->segundoap));
	}

} else {
    $nframework->usecommon=true;
    echo $metro;
	
?>
<div class="container p-2">
	<?=secureform()?>
	<br><br><br>
    <ul class="" data-role="materialtabs" data-on-tab-change="tab_change" data-expand="fs">
        <li><a href="#frame_General">General</a></li>
        <?
        foreach($userincludes as $includetitle=>$includefile){
				echo "<li><a href=\"#frame_$includetitle\">$includetitle</a></li>";
			}
		?>
    </ul>
	<div class="border bd-default p-2" style="margin-top: 2px">
        <div class="frame" id="frame_General">
        	<div class="bg-cyan fg-white p-3"><h4>Generales</h4></div>
			<div class="bg-white p-3">
				<div class="grid">
					<div class="row">
						<div class="cell-md-6">
							<div class="grid">
							    <div class="row">
							        <div class="cell"><?=$primerap?></div>        
							        <div class="cell"><?=$segundoap?></div>
						        </div>
						        <div class="row">
							        <div class="cell "><?=$nombres?></div>
							    </div>
							      <div class="row">
							        <div class="cell"><?=$username?></div> 
						        </div>
						        <div class="row">
							        <div class="cell"><?=$telefono?></div>
							        <div class="cell"><?=$cel?></div> 
						        </div>
						        <div class="row">
							        <div class="cell"><?=$sexo?></div>
							        <div class="cell"><?=$nacimiento?></div>
							    </div>
							</div>
						</div>
						<div class="cell-md-6 align-center">
							<img src="/profiles/<?=(file_exists($_SERVER['DOCUMENT_ROOT'].'/profiles/'.$data['_id'].'.png')?$data['_id']:'guest')?>.png?t=<?=time()?>" width="250px">
						<?/*
							<img src="/nframework/imagen.php?id=profile&file=<?=(file_exists($_SERVER['DOCUMENT_ROOT'].'/profiles/'.$data['_id'].'.png')?$data['_id']:'guest')?>.png?t=<?=time()?>">
							<a href="picture.php" class="button full-size">Cargar imagen</a>*/?>
								<a href="webcam.php" class="button full-size">Tomar foto</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
		foreach($userincludes as $includetitle=>$includefile){
			echo '<div class="frame" id="frame_'.$includetitle.'">';
					include $includefile;
			echo '</div>';
		}
		?>
	</div>
	<div class="grid">
	    <div class="row">
				<div class="cell-md-2 offset-md-8"><a href="/" class="button primary w-100"><span class="mif-exit"></span>&nbsp;Cerrar</a></div>
				<div class="cell-md-2"><button class="button secureop success w-100" value="save"><span class="mif-floppy-disk"></span>&nbsp;Guardar</button></div>
			</div>
     
	</div>
</form>
</div>
<script>
    $('.ms-choice').width(300);
    $('.ms-drop').width('');

function tab_change(tab){
	google.maps.event.trigger(map, 'resize');
	map.setCenter(myLatlng);
}
</script>
<?}?>