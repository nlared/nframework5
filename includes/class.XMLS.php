<?
class XMLS{
	public $tagName;
	public $className;
	public $attributes=[];
	public $addattributes;
	public function __construct($ops=[]){
	    $this->className=get_class($this);
	    foreach($ops as $op=>$val){
	    	if(property_exists($this,$op)){
	    		$this->{$op}=$val;
	    	}
	    }
	    
	}
	function encodeespecial($strvalor) {
	/*    $strvalor = str_replace("&", "&amp;", $strvalor);
	    $strvalor = str_replace("\"", "&quot;", $strvalor);
	    $strvalor = str_replace("<", "&lt;", $strvalor);
	    $strvalor = str_replace(">", "&gt;", $strvalor);
	    $strvalor = str_replace("`", "&apos;", $strvalor);
	    $strvalor = str_replace("\r\n", " ", $strvalor);
	    $strvalor = str_replace("\n", " ", $strvalor);
	    $strvalor = str_replace("\t", " ", $strvalor);
	    $strvalor = trim($strvalor);//*/
	    return $strvalor;
	}
	
	public function __toString(){
		$attributes=[];
		$elements=[];
		$data=get_object_vars($this);
		foreach($data as $n=>$v){
			if($n!='attributes' && $n!="className"&&$n!='tagName'&&$n!='addattributes'){
			 	if(in_array($n,$this->attributes)){
			 		if($this->{$n}!=''){
			 			$attributes[]=$n.'="'.$this->encodeespecial($this->{$n}).'"';
			 		}
			 	}else{
			 		if($this->{$n}!=''){
			 			$elements[]=(is_array($this->{$n})?implode("\n",$this->{$n}) :$this->{$n});
			 		}
			 	}
			}
		}
		
		return '<'.$this->tagName.($this->addattributes!=''?' '.$this->addattributes :'').
		(count($attributes)>0?' '. implode(' ',$attributes):'').
		(count($elements)>0? '>
'.implode("\n",$elements) .'
</'.$this->tagName.'>' :'/>');
		
	}
	
	public function Deserialize($xml,$nstrans=[],$classtrans=[]){
		$this->tagName=$xml->tagName;
	    foreach($xml->attributes as $so){
			$this->{$so->name}=$so->value;
		}
	    foreach($xml->childNodes as $so){
			if($so->localName!=''){
			    $clase='\\'.str_replace(':','\\',$so->nodeName);
			    
			    foreach($nstrans as $nso=>$nsn){
			    	if(strpos($clase,$nso)==0){
			    		$clase=str_replace($nso,$nsn,$clase);
			    	}
			    }
			    
			    if(array_key_exists($clase,$classtrans)){
			    	$clase=$classtrans[$clase];
			    }
			    if (class_exists($clase,true)){
				    //print_r($classtranslacions);
				    //echo "<br>$clase<br>";
					$objeto=new $clase;
					$objeto->Deserialize($so,$nstrans,$classtrans);
					if (is_array($this->{$so->localName})){
						$this->{$so->localName}[]=$objeto;
					}else{
						$this->{$so->localName}=$objeto;
					}
			    }else{
			    	echo "$clase no existe<br>";
			    }
			}
		}
	}
}