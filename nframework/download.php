<?
require_once 'include.php';
function valid_filename($filename){
	$valido=true;
	//"(", ")",",", "&"
	$special_chars = ['.php','.asp',"?", "[", "]", "/", "\\", "=", "<", ">", ":", ";",  "'", "\"", "$", "#", "*",  "|", "~", "`", "!", "{", "}", "%", "+", chr(0)];
   	foreach($special_chars as $char){
   		if(strpos($filename,$char)!==false){
   			$valido=false;
   		}
   	}
   	return $valido;
}


$upload = $_SESSION['uploads4'][$_GET['mid']];
//print_r($upload);
if (is_array($upload) && valid_filename($_GET['file'])){
	$directorio=realpath($upload['dir']);
	$file=realpath($directorio.DIRECTORY_SEPARATOR.$_GET['file']);
	
	if ( strpos ( $_SERVER [ 'HTTP_USER_AGENT' ], "MSIE" ) > 0 ){
    	$c='Content-Disposition: attachment; filename="' . rawurlencode ( $_GET['file'] ) . '"';
	}else{
    	$c='Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode ( $_GET['file'] ) ;
	}
	
	
	
	if(empty($_GET['preview'])){
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header($c);
				 
	}else{
		if(empty($upload['onpreview'])){
			$mimetype = mime_content_type($directorio.'/'.$_GET['file']);
			if(empty($mimetype)){
				 header('Content-Type: application/octet-stream');
				 header("Content-Transfer-Encoding: Binary"); 
				 header($c);
			}else{
				 header('Content-Type: '.$mimetype);
			}
		}else{
			require $upload['extenction'];
			require $upload['preview_ext'];
			call_user_func($upload['onpreview'],$file);
			end();
		}
	}
	readfile($file);
}