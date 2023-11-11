<?php
$developermode=true;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'include.php';
if ($user->username!='guest'){
	header('Location: /account/logout.php?to=/account/recover.php');
	exit();
}
//echo $user->username;

require_once "recaptchalib.php";
class falseresponse{
	public $success=true;
}


if(isset($_POST['op'])){
	if(!isset($config['google-captcha-invisible-secret'])){
		$response= new falseresponse();
	}else{
		// empty response
		$response = null;
		// check secret key
		$reCaptcha = new ReCaptcha($config['google-captcha-invisible-secret']);
		$response = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $_POST["g-recaptcha-response"]
	    );
	}
	$recover=$_POST['recover'];
    if ($response != null && $response->success) {
    	if($recover['username']!=''){
	    	$user=new User(['username'=>$recover['username']]);
	    	if($user->username==$recover['username']){
	    		$user->activationcode=substr(md5(mt_rand()), 0, 7);
	    	    	$mail = new PHPMailer;
	                $mail->isSMTP();                                      // Set mailer to use SMTP
	                $mail->Host = $config['mailhost'];  // Specify main and backup server
	                $mail->Port=$config['mailport'];
	                $mail->SMTPAuth = ($config['mailauth']=='on');                               // Enable SMTP authentication
	                $mail->Username = $config['mailusername'];                            // SMTP username
	                $mail->Password = $config['mailpassword'];                           // SMTP password
	                $mail->SMTPSecure = $config['mailcrypt'];                            // Enable encryption, 'ssl' also accepted
	
	                $mail->From = $config['mailusername'];
	                $mail->FromName = 'Delivery';
	                $mail->addAddress($user->username);  // Add a recipient
	                $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	                $mail->isHTML(true);                                  // Set email format to HTML
	                $mail->Subject = 'Reactivacion '.$config['site'].' '.$user->username .' '.$user->activationcode;
	                $mail->Body    = 'Este correo es enviado automaticamente <b>no require ser confirmado!</b></br>
	                Su codigo de activacion es '.$user->activationcode.' <br>
	                <a href="http://'.$_SERVER['HTTP_HOST'].'/account/reactivate.php?username='.
	                $user->username.'&code='.$user->activationcode. '">Pulse aqui para activar</a>
	                ';
	                $mail->AltBody = 'Codigo de reactivacion:'.$user->activationcode;
	                $mail->Timeout       =   10; // set the timeout (seconds)
    				$mail->SMTPKeepAlive = false;
	                if(isset($config['gammu'])){
	                	$sms =new sms($config['gammu']);
	                	$sms->phone=$user->telefono;
	                	$sms->sms='Codigo de reactivacion:'.$user->activationcode;
	                	$sms->send();
	                }
	                
	                if(!$mail->send()) {
	                   $msgError= 'El mensaje no pudo ser enviado.<br>Error: ' . $mail->ErrorInfo;
	                   
	                }else{			
	                        header('Location: reactivate.php?username='.$user->username);
	                        exit();
	                }
	    	}else{
	    		$msgError='No existe cuenta: '.$recover['username'].' '.$user->username;
	    	}
    	}else{
    		$msgError="Campo vacio";
    	}
    }else{
        $msgError='Error en capcha';
    }
}
$nframework->usecommon=true;
$nframework->jss['100a']='/account/account.js';
?>
<link rel="stylesheet" href="account.css">
<body class="h-vh-100 bg-brandColor">
	 <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow animated fadeInUp"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm">
		<img src="/images/config/144/logo.png">
	<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Recuperar cuenta</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    <div class="form-group">
	        <input name="recover[username]" type="text" data-role="input" data-prepend="<span class='mif-envelop'>" placeholder="Correo electrónico..." data-validate="required email">
	    </div>
        <div class="form-group centrar">
        <?	if(isset($config['google-captcha-invisible-secret'])){ ?>
        <div class="g-recaptcha" data-sitekey="<?=$config['google-captcha-invisible-key']?>"></div>
        <?}?>
     </div>
     <div class="form-group">
        	<a href="/" class="button">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Recuperar">
        </div>
     <div class="form-group"><?=$msgError?>
        </div>
        <?if(isset($config['google-captcha-invisible-secret'])){?>
        <script src="https://www.google.com/recaptcha/api.js?" async defer></script>
        <?}?>
    </form>
</body>