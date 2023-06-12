<?php
$developermode=true;
require 'include.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// here I'll get the subscription endpoint in the POST parameters
// but in reality, you'll get this information in your database
// because you already stored it (cf. push_subscription.php)
//$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));
//$bson=$m->{$config['sitedb']}->endpoints->findOne();
foreach($m->{$config['sitedb']}->endpoints->find() as $bson){
	$bsonn=mongotoarray($bson);
	
	if(str_contains($bsonn['endpoint']['endpoint'],'notify.windows.com')){
		echo $bsonn['endpoint']['endpoint']."\n";
		//$bsonn['endpoint']['endpoint']=urlencode($bsonn['endpoint']['endpoint']);
		$bsonn['endpoint']['endpoint']=str_replace('+','%2b',$bsonn['endpoint']['endpoint']);
		
		echo $bsonn['endpoint']['endpoint']."\n";
	}
	
	//$bsonn['contentEncoding'] = 'aesgcm';
	$notifications[]=[
		'subscription'=>Subscription::create($bsonn['endpoint']),
		'payload'=>'{"msg":"hola"}'];
	print_r($bsonn);
}

$auth = [
    'VAPID' =>$config['notifications'] ,
];

print_r($auth);
$webPush = new WebPush($auth);
// send multiple notifications with payload
$webPush->setReuseVAPIDHeaders(true);
print_r($webPush);
foreach ($notifications as $notification) {
    $webPush->queueNotification(
        $notification['subscription'],
        $notification['payload'] // optional (defaults null)
    );
}
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
        print_r($report->getRequest());
        print_r($report->getResponse());
        
    }
}