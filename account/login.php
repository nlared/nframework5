<?
/*if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}*/

require 'include.php';
$lng=$nframework->language();

$login=$_POST['login'];
if(isset($_POST['op'])){
	$user= new User([
		'username'=>  strtolower(trim($login['username'])),
		'password'=>trim($login['password'])
		]);
		
		//print_r($user);
		//exit();
	if(!empty($user->_id)){
		/*$tmp=(array)$user->sessions;
		$tmp[]=session_id();
		$user->sessions=array_values(array_unique($tmp));
		*/
		$_SESSION['user']=$user->_id;
		
	//	print_r($_SESSION);
		session_write_close();
		
		//exit();
		
		
		//echo $_SESSION['user'];
		
        
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
//$nframework->jss['100a']='/account/account.js';
$nframework->usecommon=true;
$javas->addjs('

$("#content").height("100vh");

','resize');
?>
<style>
	input:-webkit-autofill,
	input:-webkit-autofill:hover,
	input:-webkit-autofill:focus,
	input:-webkit-autofill:active {
    	transition: background-color 5000s ease-in-out 0s;
	}
	body{
    
    	background: linear-gradient(to right,<?=$config['manifest']['theme_color']?> 50%,white 50%,white 100%); /* W3C */
	    background-repeat: no-repeat;
	    background-attachment: fixed;
	    background-position: center; 
	}
</style>
<link rel="stylesheet" href="account.css">
<body id="body">
    <div class="container-fluid flex-justify-center bg-brand" style="height:100vh;" id="content">
        <div class="grid h-100">
            <div class="row h-100">
                <div class="cell-md-6 mh-25-sm mh-100-md  p-4 p-20-md flex-justify-center flex-align-center" style="color: <?=$config['bgcolor']?>;background-color: <?=$config['themecolor']?>;">
                    <center class="pos-relative pos-center">
                    	<img  src="/images/config/256/logo.png">
						<h1 class="responsiveheader1" id=responsiveheader1><?=$config['title']?><br>
							<div class= "centrar m-5">
							<span class="responsiveheader2" id="responsiveheader2"><?=$config['title2']?></span>
							</div>
						</h1>
						<p class="time" align="center"></p>
					</center>
				</div>
                
                <div class="cell-md-6 p-4 p-20-md">
		        	<form method="POST" id="newform"
		    			data-role="validator"
		    			data-clear-invalid="2000"
		    			data-on-error-form="invalidForm"
		    			data-on-validate-form="validateForm" class="pos-relative pos-center">
		        		<div id="activar" align="center">
				    		<h2 class="text-light"><?=$lng['login']?></h2>
							<p class="time" align="center"></p>
				    			<hr class="bg-darkCrimson">
				    	</div>
		        		
						<div class="form-group">
					        <input name="login[username]" type="text" data-role="input" 
					        data-prepend="<span class='mif-user'>" 
					        data-icon="<span class='mif-user'>"
							data-label="<?=$lng['user'].':'?>"
							data-informer="Tecleé un correo electrónico valido"
							placeholder="<?=$lng['user']?>..."
							data-validate="required"
							required="required">
					    </div>
					    <div class="form-group">
					        <input name="login[password]" type="password" data-role="input" 
					        data-prepend="<span class='mif-key'>" 
					        data-icon="<span class='mif-key'>"
							data-label="<?=$lng['password'].':'?>"
							data-informer="Tecleé la contraseña"
					        placeholder="<?=$lng['password']?>..." 
					        data-validate="required"
					        required="required">
				        </div>
				        <div class="form-group" align="right" >
				        	
				        	<a href="/" class="button"><?=$lng['buttons']['cancel']?></a>&nbsp
				            <input name="op" type="submit" class="button primary" value="<?=$lng['signin']?>"><br>
		        		</div>
		        		<hr class="thin mt-4 mb-4 bg-white">
			        	<div id="ajax"></div>
		        
    				<div id="actividadesCuenta" align="center/*">
		        		<hr class="bg-darkCrimson">
		        		<?if($config['canregister']){ ?>
		        			Don't have an account?&nbsp;<a href="new.php"><?=$lng['signup']?></a><br>
			        	<? }?>
			        	Forgot your password?&nbsp;<a href="recover.php"><?=$lng['recovery']?></a><br>
		        	</div>
		        </form>
	        	</div>
        	</div>
    	</div>
    </div>
</body>



<?/*



	<div class="container-fluid"> 
		<div class="grid">
			<div class="row">
    			 <div id="cabecera" class="cell-6 align-center pos-fixed pos-left-center account" >
					<div class="cell">
						<img class= " porcentaje img-container mb-5 pos-bottom-center" src="/images/config/256/logo.png">
					</div>
					<div id=txtheader class= "container responsivenone pos-bottom-center">
						<h1 class="responsiveheader1" id=responsiveheader1><?=$config['title']?><br>
							<div class= "centrar m-5">
							<span class="responsiveheader2" id="responsiveheader2"><?=$config['title2']?></span>
							</div>
						</h1>
						<p class="time" align="center"></p>
					</div>
				</div>
            </div>
    		<div id="login" class="cell-6 align-center pos-fixed pos-right-center account bg-white" >

		<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light"><?=$lng['login']?></h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    <div class="form-group">
	        <input name="login[username]" type="text" data-role="input" 
	        data-prepend="<span class='mif-user'>" 
	        data-icon="<span class='mif-user'>"
			data-label="<?=$lng['user'].':'?>"
			data-informer="Tecleé un correo electrónico valido"
			placeholder="<?=$lng['user']?>..."
			data-validate="required"
			required="required">
	    </div>
	    <div class="form-group">
	        <input name="login[password]" type="password" data-role="input" 
	        data-prepend="<span class='mif-key'>" 
	        data-icon="<span class='mif-key'>"
			data-label="<?=$lng['password'].':'?>"
			data-informer="Tecleé la contraseña"
	        placeholder="<?=$lng['password']?>..." 
	        data-validate="required"
	        required="required">
        </div>
        <div class="form-group">
        	<a href="/" class="button"><?=$lng['buttons']['cancel']?></a>
            <input name="op" type="submit" class="button primary" value="<?=$lng['signin']?>"><br>
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
	        <a href="recover.php" class="button"><?=$lng['recovery']?></a>&nbsp;
	        <?if($config['canregister']){ ?>
	        <a href="new.php" class="button"><?=$lng['signup']?></a>
	        <? }?>
        </div>
    </form>
</body>*/