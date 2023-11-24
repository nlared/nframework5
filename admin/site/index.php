<?
require '../common2.php';
	$dataset=new dataset(
    [
    'collection'=>$m->{$config['sitedb']}->configs,
    '_id'=>'site',
    'simpleid'=>true,
    'nameprefix'=>'data']
);

$themesdir=scandir('../../themes/');


foreach($themesdir as $themedir){
	if($themedir!='.'&& $themedir!='..'){
		$themes[$themedir]=$themedir;
	}
}
$title=new inputText(['dataset'=>&$dataset,'field'=>'title','caption'=>'Title:','required'=>true]);
$shortname=new inputText(['dataset'=>&$dataset,'field'=>'shortname','caption'=>'Shortname:','required'=>true]);
$tagline=new inputText(['dataset'=>&$dataset,'field'=>'tagline','caption'=>'Tagline:']);
$image=new inputText(['dataset'=>&$dataset,'field'=>'image','caption'=>'Image:']);

$theme=new select(['dataset'=>&$dataset,'field'=>'theme','caption'=>'Theme:','options'=>$themes]);
$homepagetype=new inputradios(['dataset'=>&$dataset,'field'=>'homepagetype','caption'=>'Home page type:<br>','options'=>[
	'page'=>'Page',
	'blog'=>'Blog'
	]]);


$email=new inputText(['dataset'=>&$dataset,'field'=>'email','caption'=>'Webmaster E-mail:']);

$logo=new inputText(['dataset'=>&$dataset,'field'=>'logo','caption'=>'Logo:','default'=>'https://www.nlared.com/img/nlaredlogo5.png']);


$google_site_verification=new inputText(['dataset'=>&$dataset,'field'=>'google-site-verification','caption'=>'google-site-verification:']);
$google_captcha_key=new inputText(['dataset'=>&$dataset,'field'=>'google-captcha-key','caption'=>'google-captcha-key:']);
$google_captcha_secret=new inputText(['dataset'=>&$dataset,'field'=>'google-captcha-secret','caption'=>'google-captcha-secret:']);

$canregister=new inputcheckbox(['dataset'=>&$dataset,'field'=>'canregister','caption'=>'Anyone can register:']);
$usebootstrap=new inputcheckbox(['dataset'=>&$dataset,'field'=>'usebootstrap','caption'=>'Use Bootstrap:']);
$passwordmask=new inputText(['dataset'=>&$dataset,'field'=>'passwordmask','caption'=>'Password mask:','default'=>'/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/','required'=>true]);


$mailhost=new inputText(['dataset'=>&$dataset,'field'=>'mailhost','caption'=>'Host:']);
$mailusername=new inputText(['dataset'=>&$dataset,'field'=>'mailusername','caption'=>'Username:']);
$mailpassword=new inputText(['dataset'=>&$dataset,'field'=>'mailpassword','caption'=>'Password:']);
$mailport=new inputNumber(['dataset'=>&$dataset,'field'=>'mailport','caption'=>'Port:']);
$mailsmtpauth=new inputcheckbox(['dataset'=>&$dataset,'field'=>'mailsmtpauth','caption'=>'Smtp auth:']);
$mailcrypt=new inputradios(['dataset'=>&$dataset,'field'=>'mailcrypt','caption'=>'Crypt:','options'=>[
	'ssl'=>'ssl',
	'tls'=>'tls'
	]]);
//$mailauth=new inputcheckbox(['dataset'=>&$dataset,'field'=>'mailauth','caption'=>'Auth:']);


$letsencryptemail=new inputText(['dataset'=>&$dataset,'field'=>'letsencrypt_email','caption'=>'E-mail:']);
$letsencryptuse=new inputcheckbox(['dataset'=>&$dataset,'field'=>'letsencrypt_use','caption'=>'Use:']);


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
        
        
        if($dataset->letsencrypt_use=='on'){
	        $client = new Api($dataset->letsencrypt_email, __DIR__ . '/__account');
			if (!$client->account()->exists()) {
			    $account = $client->account()->create();
			}else{
				$account = $client->account()->get();
			}
			if($renovar){
				$order = $client->order()->new($account, ['example.com']);
				$order = $client->order()->get($order->id);
				$validationStatus = $client->domainValidation()->status($order);
				$validationData = $client->domainValidation()->getValidationData($validationStatus, AuthorizationChallengeEnum::HTTP);
			}
        }
    }
} else {
	$nframework->usecommon=true;
	
?>
<div class="container p-5">
	<div class="bg-cyan fg-white p-3"><h4>Site config</h4></div>
	<div class="bg-white p-3">
	<?=secureform()?>
		<div class="grid">
			<div class="row">
				<div class="cell col"><?=$title?></div>
				<div class="cell col"><?=$shortname?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$tagline?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$logo?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$email?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$theme?></div>
				<div class="cell col"><?=$homepagetype?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$passwordmask?></div>
				<div class="cell col"><br><?=$canregister?></div>
				<div class="cell col"><br><?=$usebootstrap?></div>
			</div>
			</div>
			<div class="row">
				<div class="cell col"><?=$google_site_verification?></div>
			</div>
			
			
			<div class="row bg-cyan fg-white p-3">
				<div class="cell">Mail Config</div>
			</div>
			<div class="row">
				<div class="cell col"><?=$mailhost?></div>
				<div class="cell col"><?=$mailport?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$mailusername?></div>
				<div class="cell col"><?=$mailpassword?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$mailcrypt?></div>
				<div class="cell col"><?=$mailsmtpauth?></div>
			</div>
			<div class="row bg-cyan fg-white p-3">
				<div class="cell">Lets Encrypt</div>
			</div>
			<div class="row">
				<div class="cell col"><?=$letsencryptuse?></div>
				<div class="cell col"><?=$letsencryptemail?></div>
			</div>
			
			<div class="row justify-content-md-right">
				<div class="cell-md-2 col offset-md-8"><a href="./" class="btn btn-primary button primary w-100"><span class="mif-exit"></span>&nbsp;Cerrar</a></div>
				<div class="cell-md-2 col"><button class="button btn btn-success secureop success w-100" value="save"><span class="mif-floppy-disk"></span>&nbsp;Guardar</button></div>
			</div>
		</div>
		
	</form>
	</div>
</div>
<?}?>