<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'config.php';
$m = new MongoDB\Client($config['mongo_connection_string']);
use Altmetric\MongoSessionHandler;
$sessions = $m->{$config['sitedb']}->sessions;
$handler = new MongoSessionHandler($sessions);
session_set_save_handler($handler);
session_name(str_replace('.','_',$config['cookie_domain']));
session_set_cookie_params(0, '/', $config['cookie_domain'],true,false);
session_start();

if (isset($_SESSION['nf5photo'])) {
	$conf=$_SESSION['nf5photo'];
	if(!empty($_POST['photo'])){
		$data=$_POST['photo'];
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);
		file_put_contents($conf['path'], $data);
		//print_r($_SESSION);
	}
}
