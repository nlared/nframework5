<?
require '../common2.php';
require 'timezonelist.php';
	$dataset=new dataset(
    [
    'collection'=>$m->{$config['sitedb']}->configs,
    '_id'=>'site',
    'simpleid'=>true,
    'nameprefix'=>'data']
);

$themesdir=scandir('../../themes/');
$developermode=true;

foreach($themesdir as $themedir){
	if($themedir!='.'&& $themedir!='..'){
		$themes[$themedir]=$themedir;
	}
}





$title=new inputText(['dataset'=>&$dataset,'field'=>'title','caption'=>$nframework->language['title'].':','required'=>true]);
$shortname=new inputText(['dataset'=>&$dataset,'field'=>'shortname','caption'=>$nframework->language['shortname'].':','required'=>true]);
$tagline=new inputText(['dataset'=>&$dataset,'field'=>'tagline','caption'=>'Tagline:']);
$image=new inputText(['dataset'=>&$dataset,'field'=>'image','caption'=>'Image:']);

$description=new textarea(['dataset'=>&$dataset,'field'=>'description','caption'=>$nframework->language['description'].':','required'=>true]);


$timezone=new select(['dataset'=>&$dataset,'field'=>'timezone','caption'=>$nframework->language['timezone'].':','options'=>$timezones]);



$bgcolor=new inputcolor(['dataset'=>&$dataset,'field'=>'bgcolor','caption'=>$nframework->language['bgcolor'].':','default'=>'#FFFFFF']);
$themecolor=new inputcolor(['dataset'=>&$dataset,'field'=>'themecolor','caption'=>$nframework->language['themecolor'].':','default'=>'#1BA1E2']);



$theme=new select(['dataset'=>&$dataset,'field'=>'theme','caption'=>$nframework->language['theme'].':','options'=>$themes]);
$homepagetype=new inputradios(['dataset'=>&$dataset,'field'=>'homepagetype','caption'=>$nframework->language['homepagetype'].':<br>','options'=>[
	'page'=>'Page',
	'blog'=>'Blog'
	]]);


$email=new inputText(['dataset'=>&$dataset,'field'=>'email','caption'=>$nframework->language['webmasteremail'].':']);

//$logo=new inputText(['dataset'=>&$dataset,'field'=>'logo','caption'=>$nframework->language['logo'].':','default'=>'https://www.nlared.com/img/nlaredlogo5.png']);

$logo=new inputfile([
	'id'=>'logo',
	'name'=>'logo',
	'dir'=>$_SERVER['DOCUMENT_ROOT'].'/img/nf/',
	'path'=>$_SERVER['DOCUMENT_ROOT'].'/img/nf/logo.png',
	'accept'=>'.png',
	'drop'=>false,
]);



$google_site_verification=new inputText(['dataset'=>&$dataset,'field'=>'google-site-verification','caption'=>'google-site-verification:']);
$google_captcha_key=new inputText(['dataset'=>&$dataset,'field'=>'google-captcha-key','caption'=>'google-captcha-key:']);
$google_captcha_secret=new inputText(['dataset'=>&$dataset,'field'=>'google-captcha-secret','caption'=>'google-captcha-secret:']);
$google_maps_api=new inputText(['dataset'=>&$dataset,'field'=>'google-maps-api','caption'=>'google-maps-api:']);
//https://console.cloud.google.com/google/maps-apis/credentials?

$canregister=new inputcheckbox(['dataset'=>&$dataset,'field'=>'canregister','caption'=>$nframework->language['canregister'].':']);
$usebootstrap=new inputcheckbox(['dataset'=>&$dataset,'field'=>'usebootstrap','caption'=>$nframework->language['usebootstrap'].':']);
$passwordmask=new inputText(['dataset'=>&$dataset,'field'=>'passwordmask','caption'=>$nframework->language['passwordmask'].':','default'=>'/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/','required'=>true]);


$mailhost=new inputText(['dataset'=>&$dataset,'field'=>'mailhost','caption'=>$nframework->language['host'].':']);
$mailusername=new inputText(['dataset'=>&$dataset,'field'=>'mailusername','caption'=>$nframework->language['username'].':']);
$mailpassword=new inputText(['dataset'=>&$dataset,'field'=>'mailpassword','caption'=>$nframework->language['password'].':']);
$mailport=new inputNumber(['dataset'=>&$dataset,'field'=>'mailport','caption'=>$nframework->language['port'].':']);
$mailsmtpauth=new inputcheckbox(['dataset'=>&$dataset,'field'=>'mailsmtpauth','caption'=>$nframework->language['smtpauth'].':']);
$mailcrypt=new inputradios(['dataset'=>&$dataset,'field'=>'mailcrypt','caption'=>$nframework->language['encrypt'].':','options'=>[
	'ssl'=>'ssl',
	'tls'=>'tls'
	]]);
//$mailauth=new inputcheckbox(['dataset'=>&$dataset,'field'=>'mailauth','caption'=>'Auth:']);


$letsencryptemail=new inputText(['dataset'=>&$dataset,'field'=>'letsencrypt_email','caption'=>$nframework->language['email'].':']);
$letsencryptuse=new inputcheckbox(['dataset'=>&$dataset,'field'=>'letsencrypt_use','caption'=>$nframework->language['useletsencrypt'].':']);


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
	<div class="bg-cyan fg-white p-3"><h4><?=$nframework->language['siteconfig']?></h4></div>
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
				<div class="cell col"><?=$description?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$logo?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$bgcolor?></div>
				<div class="cell col"><?=$themecolor?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$email?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$timezone?></div>
				<div class="cell col"><?=$theme?></div>
				<div class="cell col"><?=$themeupload?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$passwordmask?></div>
				<div class="cell col"><?=$homepagetype?></div>
				<div class="cell col"><br><?=$canregister?></div>
				<div class="cell col"><br><?=$usebootstrap?></div>
			</div>
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
			<div class="row bg-cyan fg-white p-3">
				<div class="cell">Google keys</div>
			</div>
			<div class="row">
				<div class="cell col"><?=$google_site_verification?></div>
				<div class="cell col"><?=$google_maps_api?></div>
			</div>
			<div class="row">
				<div class="cell col"><?=$google_captcha_key?></div>
				<div class="cell col"><?=$google_captcha_secret?></div>
			</div>
			
			<div class="row justify-content-md-right">
				<div class="cell-md-2 col offset-md-8"><a href="./" class="btn btn-primary button primary w-100"><span class="mif-exit"></span>&nbsp;<?=$nframework->language['close']?></a></div>
				<div class="cell-md-2 col"><button class="button btn btn-success secureop success w-100" value="save"><span class="mif-floppy-disk"></span>&nbsp;<?=$nframework->language['save']?></button></div>
			</div>
		</div>
		
	</form>
	</div>
</div>
<?}?>