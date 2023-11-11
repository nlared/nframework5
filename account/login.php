<?
/*if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}*/

require 'include.php';
$login=$_POST['login'];
if(isset($_POST['op'])){
	$user= new User([
		'username'=>  strtolower(trim($login['username'])),
		'password'=>trim($login['password'])
		]);
	if($user->username!=''){
		$tmp=(array)$user->sessions;
		$tmp[]=session_id();
		$user->sessions=array_values(array_unique($tmp));
		
		
		$_SESSION['user']=$user->username;
		//echo $_SESSION['user'];
		session_write_close();
        if( $_SESSION['nframework']['logiopage']!='' && $_SESSION['nframework']['logiopage']!='/account/login.php'){
            header('location: '.$_SESSION['nframework']['logiopage']);
        }else{
        	if($user->in('admins')){
        		header('location: /admin/');
        	}else{
        		header('location: /');
        	}
        }
        exit();
	}
	
	$msgError='Datos incorrectos';
	if($_SESSION['nframework']['loginpage']){
		$_SESSION['nframework']['loginerror']='Datos incorrectos';
		header('location: '.$_SESSION['nframework']['loginpage']);
	}else{
		//notify($msgError,$msgError);
		
	}
}

//require_once 'common.php';
$nframework->jss['100a']='/account/account.js';
$nframework->usecommon=true;
?>
<style>
	input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
    transition: background-color 5000s ease-in-out 0s;
}
</style>
<link rel="stylesheet" href="account.css">
<body class="h-vh-100 bg-brandColor">
    <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow animated fadeInUp"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm" >
    	<img src="/images/config/144/logo.png">
		<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Inicio de Sesión</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    <div class="form-group">
	        <input name="login[username]" type="text" data-role="input" 
	        data-prepend="<span class='mif-envelop'>" 
	        data-icon="<span class='mif-envelop'>"
			data-label="Correo Electrónico"
			data-informer="Tecleé un correo electrónico valido"
			placeholder="Correo electrónico..."
			data-validate="required"
			required="required">
	    </div>
	    <div class="form-group">
	        <input name="login[password]" type="password" data-role="input" 
	        data-prepend="<span class='mif-key'>" 
	        data-icon="<span class='mif-key'>"
			data-label="Contraseña"
			data-informer="Tecleé la contraseña"
	        placeholder="Contraseña..." 
	        data-validate="required"
	        required="required">
        </div>
        <div class="form-group">
        	<a href="/" class="button">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Iniciar"><br>
        </div>
    	<? if(defined('Facebook_App_ID')){ ?>
        <div class="text-center m-4">- OR -</div>
        <div class="form-group">
        	<? if(defined('Facebook_App_ID')){
        	
        	$fb = new Facebook\Facebook([
			  'app_id' => Facebook_App_ID,
			  'app_secret' => Facebook_App_Secret,
			  'default_graph_version' => 'v2.10',
			  ]);
			
			$helper = $fb->getRedirectLoginHelper();
			
			$permissions = ['email']; // Optional permissions
			$loginUrl = $helper->getLoginUrl('https://lomitos.nlared.com/account/fb-callback.php', $permissions);
        	
        	?>
            <a href="<?=$loginUrl?>" class="image-button w-100 mt-1 bg-facebook fg-white" type="button">
                <span class="mif-facebook icon"></span>
                <span class="caption">Sign In using Facebook</span>
            </a>
            <? }?>
        </div>
        <? }?>
        <div class="form-group">
	        <a href="recover.php" class="button">Recuperar cuenta</a>&nbsp;
	        <?if($config['canregister']){ ?>
	        <a href="new.php" class="button">Registarse</a>
	        <? }?>
        </div>
    </form>
</body>