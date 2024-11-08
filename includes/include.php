<?
if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
        $_SERVER['REQUEST_SCHEME'] = str_replace('http', 'https', $_SERVER['REQUEST_SCHEME']);
        $_SERVER['SERVER_PROTOCOL'] = str_replace('HTTP', 'HTTPS', $_SERVER['SERVER_PROTOCOL']);
        $_SERVER['HTTPS'] = 'on';
}

if( php_sapi_name() != "cli"){
	/*if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
	    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    header('HTTP/1.1 301 Moved Permanently');
	    header('Location: ' . $location);
	    exit;
	}*/
}
require 'vendor/autoload.php';


function ifset($array,$key):mixed{
	return isset($array[$key]) ? $array[$key] : null;
}
class class_config implements ArrayAccess {
	private array $contenedor;
	public function __construct(){
		require 'config.php';
		$this->contenedor=$config;
		
		$this->contenedor['images']['config']['logo']=(empty($this->contenedor['image'])?'https://www.nlared.com/img/nlaredlogo5.png':$this->contenedor['image']);
	}
	public function loadfromdb(){
		global $m;
		$dbconf=$m->{$this->contenedor['sitedb']}->configs->findOne(['_id'=>'site']);
		$themeconf=$m->{$this->contenedor['sitedb']}->configs->findOne(['_id'=>'theme']);
		
		$conf=array_merge(
			$this->contenedor,
			mongoToArray($dbconf)
		);
		$conf['theme']=mongoToArray($themeconf);
		if(empty($conf['manifest']['theme_color'])){
			$conf['manifest']['theme_color']='#1ba1e2';
		}
		if(empty($conf['manifest']['background_color'])){
			$conf['manifest']['background_color']='#ffffff';
		}
		
		$this->contenedor=$conf;
	}
	public function offsetSet(mixed $offset, mixed $valor) :void{
        
        /*if (is_null($offset)) {
            $this->contenedor[] = $valor;
        } else {
            $this->contenedor[$offset] = $valor;
        }*/
    }
	
    public function offsetExists($offset):bool {
        return isset($this->contenedor[$offset]);
    }
    public function offsetUnset($offset):void {
        unset($this->contenedor[$offset]);
    }
    public function offsetGet($offset):mixed {
        return isset($this->contenedor[$offset]) ? $this->contenedor[$offset] : null;
    }
}

$config=new class_config();


class class_nframework{
	public array $language; 
	public bool $isAjax=false;
	public bool $https=false;
	public String $lang;
	public String $lang_;
	public String $langshort;
	public array $languages;
	//public String $language;
	private array $config;
	private array $counters=[];
	public array $errores=[];
	public array $csss=[];
	public array $jss=[];
	public array $javas=[];
	public array $javasonce=[];
	public array $docend=[];
	public bool $usecommon=false;
	public String $include_path;
	public String $api_path;
	public String $body_addtag='';
	public String $html_addtag='';
	public function __construct(){
		$this->include_path=__DIR__;
		$this->api_path=$_SERVER['DOCUMENT_ROOT'].'/nframework';
		if	(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])){
			if ( $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
				$this->https=true;
			}
		
		}else{
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
    			$this->https=true;
			}
    	}


		if(
			isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$this->isAjax=true;
		//	$this->usecommon=false;
		}else{
			
			$this->csss=[
				'000'=>'https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.0/themes/smoothness/jquery-ui.min.css',
				'004'=>'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css',
				'005rte'=>'https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css',
				'049'=>'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.32.0/css/jquery.fileupload.min.css',
				'050'=>'https://cdn.nlared.com/metro4/metro.min.css',
				'051'=>'https://cdn.nlared.com/metro4/icons.min.css',
				'100'=>'https://cdn.nlared.com/nframework/4.5.0/nframework.min.css',
			];
			
			$this->jss=[
				'000'=>'/main.js',
				'001'=>'https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.7.1.min.js',
				'002'=>'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.0/jquery-ui.min.js',
		//		'003'=>'https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js',
				'004'=>'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js',
				'005'=>'https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js',
				'006'=>'https://cdn.datatables.net/v/dt/dt-1.13.6/r-2.5.0/sc-2.2.0/sl-1.7.0/datatables.min.js',
				'007'=>'https://cdn.nlared.com/jquery-parallax/parallax.min.js',
				'008'=>'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
				'049'=>'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.32.0/js/jquery.fileupload.min.js',
				'050'=>'https://cdn.nlared.com/metro4/metro.min.js',
				
				
				'100'=>'https://cdn.nlared.com/nframework/4.5.0/nframework.min.js',
			];
			/*$this->csss['050']='https://cdn.metroui.org.ua/current/metro.css';
			$this->csss['051']='https://cdn.metroui.org.ua/current/icons.css';
			$this->jss['050']='https://cdn.metroui.org.ua/current/metro.js';
			$this->jss['100']='https://cdn.nlared.com/nframework/4.5.1/nframework.js?dev='.date('ymdhis');
		//*/
		}
	}
	public function getAuthorizationHeader():string{
	    $headers = null;
	    if (isset($_SERVER['Authorization'])) {
	        $headers = trim($_SERVER["Authorization"]);
	    }
	    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	    } elseif (function_exists('apache_request_headers')) {
	        $requestHeaders = apache_request_headers();
	        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	        //print_r($requestHeaders);
	        if (isset($requestHeaders['Authorization'])) {
	            $headers = trim($requestHeaders['Authorization']);
	        }
	    }
	    return $headers;
	}
	
	public function counters(string $v):int{
		if(!array_key_exists($v,$this->counters)){
        	$this->counters[$v]=0;
        }else{
        	$this->counters[$v]++;
        }
        return $this->counters[$v];
	}
	public function isAjax():bool{
		return $this->isAjax;
	}
	function loadBrowserInfo():void{
		require 'Browser.php'; //TODO: composer
		$b = new Browser();
		$_SESSION['nf']['browser']=[
	        'browser'=>  $b->getBrowser(),
	        'version'=>  $b->getVersion(),
	        'platform'=> $b->getPlatform(),
	        'mobile'=>  $b->isMobile()
		];
		$languages=[
			'es'=>'es-MX',
			'es-ES'=>'es-MX',
			'es-MX'=>'es-MX',
			'en-US'=>'en-US',
			'en'=>'en-US'
		];
		if(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			$_SESSION['nf']['browser']['language']='en-US';
		}else{
			$_SESSION['nf']['browser']['language']=	$languages[Locale::lookup(array_keys($languages), $_SERVER['HTTP_ACCEPT_LANGUAGE'], true, 'en-US')];
		}
		$_SESSION['nf']['Anti-CSRF']=uniqid();
		
	}
	
	
	
	function excelOut($spreadsheet,$filename){
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		$writer->save('php://output');
		
	}
	
	function excelOutPdf($spreadsheet,$filename){
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Dompdf');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="'.$filename.'.pdf"');
		$writer->save('php://output');
		
	}
	function wordOut($spreadsheet,$filename){
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		$writer->save('php://output');
		
	}
	
	function wordOutPdf($spreadsheet,$filename){
		$writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Dompdf');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="'.$filename.'.pdf"');
		$writer->save('php://output');
		
	}
	function language(){
		return $this->languages[$this->lang];
	}
	
}
$nframework=new class_nframework();
require 'class.Base.php';
try{
	$m = new MongoDB\Client($config['mongo_connection_string']);

	$config->loadfromdb();
	
	
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    phpinfo();
}
if(!empty($config['timezone']))date_default_timezone_set($config['timezone']);

use MongoDB\BSON\ObjectID;
function toMongoId($item){
	return new MongoDB\BSON\ObjectID($item);
}
function toMongoIds(array $items){
	return array_map('toMongoId',(array)$items);
}
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |  E_COMPILE_ERROR );

function nferrorhandler(int $errno , string $errstr , string $errfile , int $errline , array $errcontext =[]):bool{
	global $developermode,$m,$nframework,$config;
	if (!$developermode){
	    if($errno ^ E_NOTICE && $errno ^ E_WARNING){
		    
			$result=$m->{$config['sitedb']}->errorlog->updateOne([
				'desc'=>$errstr
				],[
		        '$inc'=>['tries'=>1],
		        '$set'=>['lasttime'=>date('Y-m-d H:i:s')],
		        '$setOnInsert'=>['type'=>$errno,'file'=>$errfile,'number'=>$errline]
		    ],['upsert'=> true]);
	        if($errordoc['type'] & E_FATAL){
	        	http_response_code(200);
		    	echo 'ocurrio una incidencia en el programa, reportando el problema para su solucion, disculpe las molestias ';
		    	
		        if(isset($result->upserted)){
		            /*$mail = new PHPMailer();
		            $mail->isSMTP();                                      // Set mailer to use SMTP
		           	$mail->Host=$config['mailhost'];
					$mail->Port=$config['mailport'];
					$mail->SMTPAuth=$config['mailsmtpauth'];
					$mail->Username=$config['mailusername'];
					$mail->Password=$config['mailpassword'];
					$mail->Subject = 'Incidencia critica '.$result['upserted']  ;
					$mail->From = 'contacto@hmail.nlared.com';
		            $mail->FromName = 'Incidencia critica';
		            $mail->addAddress('quique@nlared.com', 'Enrique Flores'); // Add a recipient
		            $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		            $mail->IsHTML(true);
		            $mail->Body    = 'A ocurrido una incidencia critica #'.$result['upserted'];
		            $mail->AltBody = 'A ocurrido una incidencia critica #'.$result['upserted'];
		            if(!$mail->send()) {
		               echo 'Error enviando correo';
		            }*/
		        }
			}
		}
	    return false;
	}else{
    	$nframework->errores[]=[
	        	'type'=>$errno,
	        	'file'=>$errfile,
	        	'number'=>$errline,
	        	'desc'=>$errstr,
	    ];
		return false;
	}
}
$original=set_error_handler('nferrorhandler');
function nframework_autoload($class_name):bool {
    $ipaths = get_include_path();
    $iarray = array_merge([(string)__DIR__],explode(PATH_SEPARATOR, $ipaths));
    foreach ($iarray as $ipath) {
        if (file_exists($ipath . '/class.' . $class_name . '.php')) {
            require_once($ipath.'/class.' . $class_name . '.php');
            return true;
        }
    }
    return false;
}
spl_autoload_register('nframework_autoload');


if($config['cookie_domain']==''){
	$config['cookie_domain']=$_SERVER['HTTP_HOST'];
}
if ($config['usebootstrap']){
	
	$nframework->csss['050']='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous';
	$nframework->csss['051']='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css';
	$nframework->jss['050']='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous';
}	
use Altmetric\MongoSessionHandler;
$sessions = $m->{$config['sitedb']}->sessions;
$handler = new MongoSessionHandler($sessions);
session_set_save_handler($handler);
session_name(str_replace('.','_',$config['cookie_domain']));
session_set_cookie_params(0, '/', $config['cookie_domain'],$nframework->https,false);
session_start();
if(empty($_SESSION['nf']['browser']['language'])){
	$nframework->loadBrowserInfo();
}

$nframework->lang=(empty($_SESSION['nf']['browser']['language'])?'en-US':$_SESSION['nf']['browser']['language']);
$nframework->lang_=str_replace('-','_',$nframework->lang);
$nframework->langshort=substr($nframework->lang,0,2);
require $nframework->include_path.'/i18n/'.$nframework->lang.'.php';
$nframework->language=$nframework->languages[$nframework->lang];
if (isset($_SESSION['user'])) {
    $user = new User(array('username' => $_SESSION['user']));
    if ($user->username == ''|| $user->disabled==true) {
        unset($_SESSION['user']);
        if($user->disabled==true){
        	header('location: /account/disabled.php');
        }else{
        	header('location: /'); // expulsar
        }
        if($user->in('developers')){
        	$developermode=true;	
        }
    }
} else {
    if (isset($requiresession)){
        header('Location: /');
    }else{
        $user = new User(array('username' => 'guest'));        
    }
}




$javas=new Javas();
function speak($text){
	global $javas;
	$javas->addjs("
	speak('$text');
",'ready');
}
//TODO> Other options
function notify($title='nlared.com',$text='',$options=[]){
	global $javas;
	$javas->addjs("
	toast('$text');
",'ready');
}
function nfshutdown(){
	global $nframework,$noobfuscate,$buffer,$developermode,$javas,$result,$config;
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR || $last_error['type'] ===E_USER_ERROR) {
		nferrorhandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		if($developermode){
			if(php_sapi_name() == 'cli'){
	        	foreach ($nframework->errores as $row){
	                $result.='|'.implode('|', $row).'|';
	            }
	            return $result;
	        }else{
				$datatable=new Table();
				$datatable->header='<th>Tipo</th><th>Archivo</th><th>Linea</th><th>Descripcion</th>';
		    	$datatable->data= $nframework->errores;
		    	echo '<link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.5/css/metro-all.min.css"/>
<link rel="stylesheet" href="//cdn.nlared.com/datatables.net-responsive-dt/css/responsive.dataTables.min.css"/>
<div class="container"><h4>Developer mode active</h4>'.$datatable.'</div>';
	        }
		}
	}

	ob_end_flush();
	
	if(count($nframework->javas)>0){
			if(empty($noobfuscate)){
				$packer = new Tholu\Packer\Packer(implode(";\n",$nframework->javas), 'Normal', true, false, true);
				$packed_js = $packer->pack();
				$javasstr.='
	<script>'.$packed_js.'</script>';
			}else{
				$javasstr.='
	<script>'.implode(";\n",$nframework->javas).'</script>';
			}
		}
	if(isset($nframework->etag)){
		header('ETag: "' .$nframework->etag.'"');
	}
	
	if(isset($nframework->lastmodified)){
    	header("Last-Modified: " .gmdate("D, d M Y H:i:s", $nframework->lastmodified) . " GMT");
	}		
	if(isset($nframework->expiretime)){
		header('Cache-Control:public, max-age='.( $nframework->expiretime-time()));
		header('Expires: '.date('D, d M Y H:i:s',$nframework->expiretime).' GMT');
	//	header('test: test');
		header('Pragma: cache');
	}else{
	//	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		//header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
	}
	//header('Content-Language: '.$nframework->lang); 
	//header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
//	if($xframe!='remove')	header('X-Frame-Options: '.$xframe);
	//header('Referrer-Policy ""'); nunca usar
	//header( 'X-XSS-Protection: 1;mode=block' );
	//header( 'X-Content-Type-Options: nosniff' );
	
	if($nframework->isAjax()){
		http_response_code(200);
		header('Content-Type: application/json');
		echo json_encode($result);
		//end();
	}else{
		
	
	if($nframework->usecommon){
		$metas=$nframework->metas;
			$csss='';
			$jss='';
			foreach($nframework->csss as $css){
				$csss.='
	<link rel="stylesheet" href="'.$css."\"/>";
			}
			ksort($nframework->jss);
			foreach($nframework->jss as $js){
				$jss.='
	<script src="'.$js.'"></script>';
			}
			$tmpkeyworsd2=[];
			/*$tmpkeywords[]=array_merge(
				explode(',',$metas['keywords']),
				explode(',',$config['keywords'])
				);
		
			foreach($tmpkeywords as $tmpkeyword){
				$tmpkeyworsd2[]=trim($tmpkeyword);
			}//*/
			
			
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'X-XSS-Protection: 1;mode=block' );
	header('Content-Type:text/html; charset=utf-8');
	echo '<!DOCTYPE html>
<html lang="'.$nframework->lang.'"'.$nframework->html_addtag.'>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8" />
    <meta name="metro4:jquery" content="true">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="google-site-verification" content="' . $config['google-site-verification'] . '" />
<meta name="Title" content="' .$config['title'].' '.$metas['title'] . '" />
<meta name="Author" content="' . $config['author'] . '" />
<meta name="Subject" content="' . $metas['title'] . '" />
<meta name="Description" content="' . $metas['description'] . '" />
<meta name="Keywords" lang="en" content="' . implode(',',$tmpkeyworsd2) . '" />
<link rel="manifest" href="/nf.webmanifest" />
<meta name="theme-color" content="#005696" />
<meta name="metro4:init" content="true" />
<meta name="metro4:locale" content="'.$nframework->lang.'" />
<meta name="metro4:week_start" content="1" />
<meta property="og:url" content="'. $metas['url'] .'" />
<meta property="og:type" content="article" />
<meta property="og:title" content="'. $config['title'].' '.$metas['title'] .'" />
<meta property="og:description" content="'. $metas['description'] .'" />
<meta property="og:image" content="/images/config///logo.png" />
<meta property="twitter:card" content="/images/config/1200/628/logo.png" />
<meta property="twitter:url" content="'. $config['url'] .'" />
<meta property="twitter:title" content="'. $config['title'].' '.$metas['title'] .'" />
<meta property="twitter:description" content="'.$metas['description'].'" />
<meta property="twitter:image" content="/images/config///logo.png" />
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="'.$config['title'].'">
<meta name="apple-mobile-web-app-title" content="'.$config['title'].'">
<meta name="msapplication-starturl" content="/">
<link rel="apple-touch-icon" sizes="57x57" href="/images/config/57/logo.png" />
<link rel="apple-touch-icon" sizes="144x144" href="/images/config/144/logo.png" />
<title>' . $config['title'] .' '.$metas['title']. '</title>
    '.$csss.'
  </head>
  <body'.$nframework->body_addtag.'>
  <dialog id="dialogLoading">
		<center>
			<span class="mif-spinner2 ani-spin"></span>
			<div autofocus id="#dialogCancel" class="button">Cancelar</div>
		</center>
	</dialog>'
  .$buffer.implode('',$nframework->docend).$jss.$javas.$javasstr.'
	
</body>
</html>';
			
		}else{
			echo $buffer.$javasstr;
		}
	}
}
//$buffer='';
if( php_sapi_name() != "cli"){
	register_shutdown_function('nfshutdown');
}
function nfjavaobfuscate($mbuffer):string{
	global $nframework,$buffer;
	if ($_SESSION['nf']['browser']['platform'] == 'Android'){
		$mbuffer=str_replace('href="javascript:','href="#" onclick="javascript:',$mbuffer);
	}
	preg_match_all('/<script((?:(?!src=).)*?)>(.*?)<\/script>/smix',$mbuffer,$matches,PREG_SET_ORDER);
	$oo[]=$matches;
	foreach($matches as $match){
		$nframework->javas[]=$match[2];
		$mbuffer=str_replace($match[0],'',$mbuffer);
	}
	$buffer.=$mbuffer;
	return '';
}
if( php_sapi_name() != "cli"){
	ob_start("nfjavaobfuscate");
}


function mongoToArray($obj){
	$m=(array)$obj;
	foreach($m as $k=>$val){
		 if(is_array($val) || is_object($val)){
			$m[$k]=mongoToArray($val);
		 }
	}
	return $m;
}

function csrfValidate(){
	return ($_POST['CSRFToken']==
	hash("sha256", $_SESSION['nf']['Anti-CSRF'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REQUEST_URI']));
}
function csrfToken($action):string{
	return hash("sha256", $_SESSION['nf']['Anti-CSRF'].$_SERVER['HTTP_USER_AGENT'].$action);
}
function secureform(
		string $action='',
		bool $files=false,
		string $id='',
		string $onvalidateform='',
		string $onbeforesubmit=''
	):string{
	global $nframework;
//	$csrftoken = csrfToken($arg['action']);
	if($id==''){
		$id='secureform'.($nframework->counters('secureform'));
	}
	return '<form method="POST" id="'.$id.'" data-role="validator"'.
	($action==''?' action="javascript:" data-on-submit="nAjaxOnSubmit"': 'action="'.$action.'"').
	($files?' enctype="multipart/form-data"':'').
' data-interactive-check="true" 
data-on-error-form="
var log = arguments[0];
var msg=\'Error de captura<br>\';
$.each(log, function(){
	var label=$(\'label[for=\\\'\'+this.input.id+\'\\\']\').text();
	msg+=(label  +\' \'+ this.errors.join(\',\') + \'<br>\');
});
toast(msg,null,5000);
"'.($onbeforesubmit==''?'': ' data-on-before-submit="'.$onbeforesubmit. '"').
($onvalidateform==''?'': ' data-on-validate-form="'.$onvalidateform. '"').'>
<input type="hidden" name="op" id="'.$opid.'" value="">
<input type="hidden" name="CSRFToken" value="'.csrfToken($action).'">';
	//$nframework['secureformcounter']++;
}


function _setNotification(array $users,$content){
	global $m,$config;
	foreach($users as $_user){
		$_user=trim((string)$_user);
		if($_user!=null){
			$nuevo=new MongoDB\BSON\ObjectID();
			$m->{$config['sitedb']}->registros->updateOne(['_id'=>new MongoDB\BSON\ObjectID($nuevo)],[
					'$set'=>[
						'user'=>$_user,
						'content'=>$content,
						'date'=>date("Y-m-d H:i:s"),
					]
			],['upsert'=>true]);
		}
	}
}
