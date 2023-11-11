<?
$developermode=true;
require_once('include.php');
//require_once('recapchalib.php');
if(isset($_POST['op'])){
	$recovery=$_POST['recovery'];
}
if(isset($_GET['username'])){
	$recovery['username']=$_GET['username'];
}
if(isset($_GET['code'])){
	$recovery['code']=$_GET['code'];
}
if ($recovery['code']!=''){
	$user= new User([
		'username'=>  strtolower(trim($recovery['username'])),
		'activationcode'=>(int)$recovery['code']
		]);
	if(!empty($recovery['password'])){
		if ($user->activationcode!=''&&
		$recovery['password']!=''&&
		$recovery['password2']==$recovery['password'] &&
		$user->activationcode == $recovery['code']){
			$_SESSION['user']=(string)$user->username;
			unset($user->activationcode);
			$user->password=hash('sha512', $recovery['password']);
			//$user->datetime=date('Y-m-d H:m:s');
			header('location: /');
			echo '<h1>Bienvenido...</h1>'.$_SESSION['user'].'<a href="/" class="button">Iniciar</a>
			<script>
				window.location.replace("/");
			</script>
			';
			exit();
		}else{
			$msgError="Datos incorrectos";
		}
	}
}
//if(i$recovery['code']==''||$msgError!=''){
$nframework->usecommon=true;
$nframework->jss['100a']='/account/account.js';
$nframework->csss['100a']="/account/account.css";
?>
   <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow animated fadeInUp"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm" >
    		<img src="/images/config/144/logo.png">
		<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Reestablecer cuenta</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    Este codigo le fue enviado por correo electrónico
		<div class="form-group">
        	<input name="recovery[username]" type="text" 
        	data-role="input"
        	data-icon="<span class='mif-envelop'>"
			data-label="User email"
			data-informer="Teclee un correo valido"
        	data-prepend="<span class='mif-envelop'>" 
        	placeholder="Correo electrónico..." 
        	data-validate="required email"
        	value="<?=$recovery['username']?>">
		</div>
		<div class="form-group">
	        <input name="recovery[password]" type="password" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Contraseña..." data-validate="required minlength=6">
        </div>
        <div class="form-group">
	        <input name="recovery[password2]" type="password" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Confirmar Contraseña..." data-validate="required minlength=6">
        </div>
        <?if(empty($_GET['code'])){?>
		<div class="form-group">
        	<input name="recovery[code]" type="password"  
        	data-role="input" 
        	data-icon="<span class='mif-key>"
    		data-label="Codigo de activación que fue enviado a tu correo electrónico"
    		data-informer="Teclee el codigo de reactivación"
        	data-prepend="<span class='mif-key'>" 
        	placeholder="Codigo de activación" 
        	data-validate="required minlength=6">
    	</div>
    	<?}?>
    	<div class="form-group">
        	<a href="/" class="button">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Restablecer">
        </div>
        <?=$msgError?>
	</form>
