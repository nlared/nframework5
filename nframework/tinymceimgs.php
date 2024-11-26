<?
require 'include.php';
$developermode=true;
if (isset($_SESSION['tinymceup'][$_GET['_id']])){
	/*********************************************
	   * Change this line to set the upload folder *
	   *********************************************/
	$imageFolder = $_SESSION['tinymceup'][$_GET['_id']]['mediadir'];
	$baseurl= $_SESSION['tinymceup'][$_GET['_id']]['baseurl'];
	function getDirContents($dir){
	 		global $baseurl;
	        $results = array();
	        $files = scandir($dir);
	
	        foreach($files as $key => $value){
	            if(!is_dir($dir. DIRECTORY_SEPARATOR .$value)){
	                $results[] = [
	                	'title'=>$value,
	                	'value'=>$baseurl.$value,
	                	];
	            } else if($value!='.'&& $value!='..' && is_dir($dir. DIRECTORY_SEPARATOR .$value)) {
	            	$data=getDirContents($dir. DIRECTORY_SEPARATOR .$value);
	                if(count($data)>0){
		                $results[] =[
		                	'title'=>$value,
		                	'menu'=>$data
		                ];
	                }
	            }
	        }
	        return $results;
	}
	
	
	$d=getDirContents($imageFolder);
	echo json_encode($d);
}