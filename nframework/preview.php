<?

require 'include.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (isset($_GET['id']) && isset($_SESSION['uploads4'][$_GET['id']])){
	$upload = $_SESSION['uploads4'][$_GET['id']];
	$filename=$upload['extensioninfo']['path'];
	$extension = pathinfo($filename, PATHINFO_EXTENSION);
	if($extension=='pdf'){
		$pdf = new \Spatie\PdfToImage\Pdf($filename);
		$numberOfPages = $pdf->pageCount();
		for($i=1;$i<=$numberOfPages;$i++){
			$links[]='/images/preview/pdf/'.$_GET['id'].'/100/200/'.$i.'.png';
		}
		
	}
	if($extension=='png'){
		$links[]='/images/'.$_GET['id'].'/100/200/preview.png';
	}
	$result=[
		'links'=>$links
	];
}else{
	$result=[
		'error'=>'id not found'
	];
}