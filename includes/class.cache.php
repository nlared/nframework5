<?
class cache{
	
	public $seconds=3600;
	public $filename;
	public function __construct($filename){
		$this->filename=realpath($filename);
	} 
	public function deletecache(){
		$encache=$m->nlared->cache->findOne([
			'id'=>md5($this->filename),
		]);
		
	}
	public function cache(){
		global  $m,$nframework;
		$encodings=['gzips','deflates'];
		$accepts=explode(',',$_SERVER['HTTP_ACCEPT_ENCODING']);
		$posibles=array_intersect($encodings, $accepts);
		$enc=(count($posibles)>0?$posibles[0]:'none');
		//$enc='none';
		
		$id=trim($_SERVER['HTTP_IF_NONE_MATCH']);
		if (substr($id,0,2)=="W/"){
			$id=substr($id,2);
		}
		$id=str_replace('"','',$id);
		//$m = new MongoDB\Client($config['mongo_connection_string']);
		$encache=$m->nlared->cache->findOne([
			'id'=>$id,
			'enc'=>$enc
		]);
		if($encache['_id']!=''){
			header('HTTP/1.1 304 Not Modified', true, 304);
			$lasttime=$encache['lasttime'];   
			$etag=$encache['id'];
			end();
		}else{
			if(file_exists($this->filename)){
				$lasttime=filectime($this->filename);
				$toetag=$this->filename;	
			}else{
				http_response_code(404);
				echo "file not found $this->filename";
				die();
			}
			
			$etag=md5($toetag);
    		$encache=$m->nlared->cache->findOne([
    			'id'=>$etag,
    			'enc'=>$enc
    		]);
    		if($encache['_id']==''){
    			$content=file_get_contents($this->filename);
    			if($enc!='none'){
					ini_set('zlib.output_compression','Off');
				}
				;	
			//	header('ncache: new');
				$encache=$m->nlared->cache->insertOne([
					'id'=>md5($toetag),
					'enc'=>$enc,
					'lasttime'=>$lasttime,
					'content'=>new MongoDB\BSON\Binary($content,MongoDB\BSON\Binary::TYPE_GENERIC)
				], ['returnDocument'=> 'after']);
			}
			
	    	$content=$encache->content;
		
		}
		header('Content-Encoding: '.$enc);
		if($encache->enc=='gzip'){
			header('Vary: "Accept-Encoding"');
		}
		$size=strlen($encache->content);
		header("Content-length: $size");
		header('Access-Control-Allow-Origin: *');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lasttime) . " GMT");
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		header("Content-type: ".$this->contentType);
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false||strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) {
		//if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
			header("Cache-Control: must-revalidate, private");
			header("Pragma: cache");
		}else{
		    header("Cache-Control:max-age=$this->seconds");
		}
		
		$nframework->expiretime= strtotime("now +$this->seconds seconds");
		$nframework->etag=$etag;
		
		// header("Dalte: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");
		//header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $this->seconds));
		//header('ETag: "' . $etag.'"');
		echo $content;
	}
}