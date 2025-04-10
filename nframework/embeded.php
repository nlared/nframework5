<?
require 'include.php';

use Twig\Environment;
use Twig\Extension\StringLoaderExtension;
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,//__DIR__.'/../compilation_cache',
    'debug' => true,
]);
$twig->addExtension(new StringLoaderExtension());
$id=$_GET['_id'];
$info=$_SESSION['nfembeded'][$id];
$dataset=new dataset([
    'collection'=>$m->{$info['database']}->{$info['collection']},
    '_id'=>$info['_id'],
    'simpleid'=>false,
    'historic'=>$info['historic'],
    'nameprefix'=>$info['nameprefix']
    ]
);

$items=mongotoArray($dataset->{$info['field']});
if($_POST['op']=='load'){
	$result['item']=$items[$_POST['pos']];
	
}else{
	if($_POST['op']=='add'){
		$items[]=$_POST[$info['nameprefix']];
	}elseif($_POST['op']=='update'){
		$items[$_POST['pos']]=$_POST[$info['nameprefix']];
	}elseif($_POST['op']=='delete'){
	    unset($items[$_POST['pos']]);
	    $items=array_values($items);
	}
	$dataset->{$info['field']}=$items;
	
	$template = $twig->createTemplate($info['template']);
	$result['items']=$items;
	$result['container']=$template->render([
		'function_get'=>$id.'_get',
		'function_delete'=>$id.'_delete',
		'items'=>$items
	]);
	
	
	
}
$result['debug']=$info;