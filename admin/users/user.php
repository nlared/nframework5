<?php
if (empty($_GET['_id'])) {
    $newid=new MongoDB\BSON\ObjectID();
    header('Location: ?_id='.$newid);
    exit();
}
require '../common2.php';

if(!$user->in('admins')){
	exit();
}

$dataset=new dataset(
    [
    'collection'=>$m->{$config['sitedb']}->users,
    '_id'=>$_GET['_id'],
    'simpleid'=>false,
    'nameprefix'=>'data']
);

$password=new inputText(['id'=>'password','caption'=>'Contraseña:','required'=>true]);
$username=new inputText(['dataset'=>&$dataset,'field'=>'username','caption'=>'Username:','required'=>true]);
$permisos=new inputCheckboxs(['dataset'=>&$dataset,'field'=>'permissions','caption'=>'Permisos:','options'=>[
	'admin'=>'Administrador',
	]]);

if ($nframework->isAjax()) {
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
    if ($_POST['op']=='password') {
    	$dataset->password=hash('sha512', $_POST['password']);
            $result=[
                'error'=>'',
                'js'=>'alert("Contraseña cambiada")'
            ];
    }
} else {
	$nframework->usecommon=true;
	
	$datatable=new Table();
	$datatable->Ajax([
	    'id'=>'sessions',
	    'db'=>$config['sitedb'],
	    'collection'=>'sessions',
	    'header'=>'<th>Last accesed</th><th>id</th>',
	    'pipeline'=>[[
	    	'$match'=>[
	    		'_id'=>['$in'=>(array)$user->sessions]
	    		]
	    	],
	    	[
	    	'$addFields'=>[
	    		'last_accessed'=>['$toString'=>'$last_accessed']
	    		]	
	    	]
	    	],
	    'columns'=>[
	        'last_accessed','_id'
	    ],
	    'columnDefs'=>[
			'1'=>['render'=>"'<a href=\"databindingajax.php?_id='+data+'\" class=\"button\"><span class=\"mif-pencil\"></span></a><a href=\"javascript:removeid(\\''+data+'\\');\" class=\"button\"><span class=\"mif-cross\"></span></a>'"],// data $row[0]
		]
	]);
	
	
?>
<div class="container p-5">
	<div class="bg-cyan fg-white p-3"><h4>User</h4></div>
	<div class="bg-white p-3">
	<?=secureform()?>
		<div class="grid">
			<div class="row">
				<div class="cell"><?=$username?></div>
			</div>
			<div class="row">
				<div class="cell"><?=$permisos?></div>
			</div>
			<div class="row">
				<div class="cell-md-2 offset-md-6"><a href="#" onclick="Metro.dialog.open('#demoDialog1')" class="button primary w-100"><span class="mif-lock"></span>&nbsp;Cambiar contraseña</a></div>
				<div class="cell-md-2"><a href="./" class="button primary w-100"><span class="mif-exit"></span>&nbsp;Cerrar</a></div>
				<div class="cell-md-2"><button class="button secureop success w-100" value="save"><span class="mif-floppy-disk"></span>&nbsp;Guardar</button></div>
			</div>
		</div>
	</form>
	<?=$datatable?>
	</div>
	<div class="dialog" data-role="dialog" id="demoDialog1">
	    <div class="dialog-title">Cambio de contraseña</div>
	    <div class="dialog-content">
	        <?=$password?>
	    </div>
	    <div class="dialog-actions">
	    	<button class="button js-dialog-close">Cancelar</button>
	        <button class="button js-dialog-close primary" onclick="cambiar();">Cambiar</button>
	    </div>
	</div>
</div>
<script>
	function cambiar(){
		$.ajax({
			url: 'user.php?_id=<?=$dataset->_id?>',
			method: 'post',
			data: {
				op: 'password',
				password:$('#password').val()
			},
			success: function(respuesta){
				console.log(respuesta);
			},
			error: function() {
		        console.log("No se ha podido obtener la información");
		    }
		});
	}
</script>
<?}?>