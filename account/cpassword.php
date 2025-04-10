<?
require 'include.php';
$lng=$nframework->language();

$login=$_POST['login'];
if ($nframework->isAjax()) {
	if ($_POST['op']=='cpassword') {
		$user2= new User([
			'_id'=>tomongoid($user->_id),
			'password'=>trim($login['passwordold'])
			]);
		
		if(!empty($user2->_id)){
			if($login['password1']==$login['password2']){
				$user->password=hash('sha512', $login['password1']);
				
				$result=[
		            'error'=>'',
		            'js'=>'alert("Contraseña cambiada");',
		            
		        ];
				
			}else{
					$result=['error'=>'contraseñas no corresponden','darta'=>$user2];
			}
	        
		}else{
			$result=['error'=>'Datos incorrectos','darta'=>$user2];
		}
	}
}else{
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
		        	<?=secureform(class:"pos-relative pos-center")?>
		        		<div id="activar" align="center">
				    		<h2 class="text-light"><?=$lng['changepassword']?></h2>
							<p class="time" align="center"></p>
				    			<hr class="bg-darkCrimson">
				    	</div>
		        		
						<div class="form-group">
					        <input name="login[passwordold]" type="password"  id="password" data-role="input" 
					        data-prepend="<span class='mif-key'>" 
					        data-icon="<span class='mif-key'>"
							data-label="<?=$lng['passwordold'].':'?>"
							data-informer="Tecleé un correo electrónico valido"
							placeholder="<?=$lng['password']?>..."
							data-validate="required"
							required="required" value="">
					    </div>
					    <div class="form-group">
					        <input name="login[password1]" type="password"  id="password1" data-role="input" 
					        data-prepend="<span class='mif-key'>" 
					        data-icon="<span class='mif-key'>"
							data-label="<?=$lng['passwordnew'].':'?>"
							data-informer="Tecleé la contraseña"
					        placeholder="<?=$lng['passwordnew']?>..." 
					        data-validate="required"
					        required="required" value="">
				        </div>
				        <div class="form-group">
					        <input name="login[password2]" type="password" id="password2" data-role="input" 
					        data-prepend="<span class='mif-key'>" 
					        data-icon="<span class='mif-key'>"
							data-label="<?=$lng['passwordconfir'].':'?>"
							data-informer="Tecleé la contraseña"
					        placeholder="<?=$lng['passwordnew']?>..." 
					        data-validate="compare=password1 required"
					        required="required" value="">
				        </div>
				        <div class="form-group" align="right" >
				        	
				        	<a href="/" class="button"><?=$lng['close']?></a>&nbsp
				            <button class="button primary secureop" value="cpassword"><?=$lng['changepassword']?></button><br>
		        		</div>
		        		<hr class="thin mt-4 mb-4 bg-white">
			        	<div id="ajax"></div>
		        </form>
	        	</div>
        	</div>
    	</div>
    </div>
</body>
<?} ?>