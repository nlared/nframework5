<?
require 'include.php';
use Cocur\BackgroundProcess\BackgroundProcess;

if(isset($_GET['pid']) &&!empty($_SESSION['pids'][$_GET['pid']])){
	$info=$_SESSION['pids'][$_GET['pid']];
	if($_GET['op']=='start'){
		$proc=new BackgroundProcess($info['cmd']);
		$proc->run($info['logfile']);
		$_SESSION['pids'][$_GET['pid']]['pid']= $proc->getPid();
	}
}

$result=[
	'servertime'=>microtime(true)
];

foreach($_SESSION['pids'] as $id=>$p){
	if (!empty($p['pid'])){
		$process = BackgroundProcess::createFromPID($p['pid']);
		if ($process->isRunning()){
			$result['pids'][$id]=[
				'status'=>'ss',
				'data'=>file_get_contents($p['logfile'])
			];
		}else{
			
			unset ($_SESSION['pids'][$id]['pid']);
		}
	}
}

foreach($m->{$config['sitedb']}->notifications->find([
	'users'=>$user->_id
	]) as $d){
	$result['notifications'][]=[
		
	];
}
//$result['ss']=$_SESSION['pids'];
