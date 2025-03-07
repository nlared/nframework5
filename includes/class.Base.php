<?php

//namespace nframework;


function assignArrayByPath(&$arr, $path, $value, $separator='.') {
    $keys = explode($separator, $path);
    foreach ($keys as $key) {
        $arr = &$arr[$key];
    }
    $arr = $value;
}

function booltotag($tag,$val){
    return ' '.$tag.'="'.($val?'true':'false').'"';
}
function strtotag($tag,$val){
    return (!empty($val)?' '.$tag.'="'.$val.'"':'');
}
function icontotag($tag,$val){
    return ($tag!=''?' '.$tag.'="'.str_replace('"','\'',$val).'"':'');
}



function mongo_auto_increment($campo){
	global $m,$config;
	$result =$m->{$config['sitedb']}->counters->findOneAndUpdate(
	[ '_id' => $campo ],
	[ '$inc' => [ 'seq' => 1] ],
	[ 'upsert' => true,
	'projection' => [ 'seq' => 1 ],
	'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
	]
	);
	return $result->seq;
}

class Base{
    public $tags;
    public function __construct($options = []) {
        $this->tags = [];
        foreach ($options as $option => $value) {
            $this->{$option} = $value;
        }
    }

}
class baseInput  {
    public $required;
    public $class;
    public $infobox;
    public $id;
    public $name;
    public $nameprefix;
    public $dataset;
    public $field;
    public $addclass;
    public $disabled;
    public $placeholder;
	public $caption;
	public $prependicon;
	public $readonly;
	public $default;
	public $validate;
	public $title;
	public $onChange;
	public $tags;
	public $value;
	public function __toMongo($val){
		return $val;
	}
    public function __lset($option,$value){
        $ovars=array_keys( get_object_vars($this));
        if($option=='value'){
           $this->value=$value;
        }elseif(in_array($option, $ovars)){
            $this->{$option} = $value;
        }else{
            $this->tags[$option]=$value;
        }
        echo "$option,$value<br>";
    }
    public function __get($name) {
        switch ($name){
            case 'value':
                if(isset($this->dataset)){
                    return $this->dataset->{$this->field};
                } else { 
                    return $this->value;           
                }                
        }        
    }
    public function __isset($option) {
        $ovars=array_keys( get_object_vars($this));
        if(in_array($option, $ovars)){
           return (isset( $this->{$option}));
        }else{
            return (isset($this->tags[$option]));
        }
    }

    public function __construct($options = []) {
        $this->tags = [];
        $ovars=array_keys( get_object_vars($this));
        /*$tmp= get_class($this);
        $parentclass=get_parent_class($tmp);
        /print_r($ovars);
        while($parentclass){
        	echo $parentclass.'</br>';
        	$tmp=$parentclass;
        	$parentarray=(array)array_keys(get_class_vars($tmp));
        	$ovars= array_merge($ovars,$parentarray);
        	print_r ($ovars);
        	$parentclass=get_parent_class($tmp);
        }
        */
        if(!isset($options['class'])){
            $options['class']='inputText';
        }
        foreach ($options as $option => $value) {
            if($option=='value'){
               $this->value=$value;
            }elseif($option=='dataset'){
                $value->addElement($this);               
                $this->dataset=$value;
            }elseif(in_array($option, $ovars)){
                $this->{$option} = $value;
            }else{
                $this->tags[$option]=$value;
            }
        }
        
        if (empty($this->value)&&!empty($this->default)){
        	$this->value=$this->default;
        }
        
        
        if($this->dataset!=''){
            if($this->name=='' &$this->field!=''){
                $this->name=$this->field;
            }
            $this->name=  $this->dataset->nameprefix.'['.$this->name.']';
			if (strpos($this->field, '.') !== false)
			{
			    $data=(array)$this->dataset->info;
			    $keys = explode('.', str_replace('$',$this->dataset->position,$this->field));
			   // print_r($keys);
			    foreach ($keys as $innerKey){
			           	/*if (!array_key_exists($innerKey, $data))
			        {
			            return $options['default'];
			        }
			        */
			        try{
			        	$data =$data[$innerKey];
			        }catch(Exception $e){
			        	$data=$options['default'];
			        }
			        
			    }
			    
			    
			    $this->value=$data;
			}else{
	            if(!isset($this->dataset->{$this->field}) && isset($options['default'])){
	        		$this->value=$options['default'];
	            }else{
	            	$this->value=$this->dataset->{$this->field};
	            }
			}
            //echo "dd:".$this->field .$this->name.' '.$this->dataset->{$this->field}."\n";
        }else{
        	if(!isset($options['value']) && isset($options['default'])){
        		$this->value=$options['default'];
        	}
        }
        if($this->id=='')$this->id=str_replace(['[',']','.'],['_','','_'],$this->name);
        if($this->id=='')$this->id=str_replace(['[',']','.'],['_','','_'],$this->field);
        
    }
    
    
    public function is_valid($newval) {
        return ($this->pattern != '' ?  filter_var($newval, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . $this->pattern . '/']]):true);
    }
    protected function writetags():String {
        $result='';
        foreach ($this->tags as $name => $value) {
            $result.=' ' . $name . '="' . $value . '"';
        }
        return $result;
        
    }
	public function data_validate(){
		$rules=explode(' ',$this->validate);
		if($this->required && !in_array('required',$rules)){
			$rules[]='required';
		}
	
		if($this->pattern){
			foreach($rules as $rule){
				if(substr($rule,0,7)=='pattern'){
					$encontrado=true;
				}
			}
			if(!$encontrado){
				$rules[]='pattern=('.$this->pattern.')';
			}
		}
		return implode(' ',$rules);
	}
	
}
class baseOptions extends baseInput{
    public $options=[];
}
class label extends baseInput {
    public function __toString() {
        return '<label' . $this->writetags() . ' id="' . $this->id . '"' . '>' .htmlspecialchars($this->value) . '</label>';
    }
}
class inputHidden extends baseInput {
    public function __construct($options = array()) {
             
        parent::__construct($options);
    }
    public function __toString() {
        return '<input type="hidden"'. ' id="' . $this->id . '" name="'. $this->name . '"' . $this->writetags().
        ' value="' . htmlspecialchars($this->value) . '">' ;
    }
}
class inputText extends baseInput {
    public $search;
    public $btn;
    public $mask;
    public $maskpattern;
    public $pattern;
    public $inputtype;
    public $addclass='form-control';
    public $type;
    public $uppercase;
    public $lowercase;
    
    public $invalid_feedbak;
    public $infobox;
    public $value;
    // INPUT MATERIAL
    public $materialinput;
    public $materialicon;
    public $materiallabel;
    public $materialinformer;
    public $invalid_feedback;
    public $autocomplete='off';
    public function __construct($options = array()) {
        $options['class']='inputText';
        if(!isset($options['type'])){
            $options['type']='text';        
        }        
        parent::__construct($options);
        if($this->type=='email'){
        	$this->validate.=' email';
        }
    }

    public function __toString() {
    	global $config;
    	if($this->pattern!=''){
    		$_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_REGEXP,['options' => ['regexp' => '/' . $this->pattern . '/']]];
    	}
    	
    	
        return 
        ($config['usebootstrap']?
        '<label for="'.$this->id.'" class="form-label">'.$this->caption.'</label> <div class="input-group">'
        :'<div class="form-group">'. ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':''))
        .($this->infobox!=''?'&nbsp;<span class="mif-question nfinfoicon fg-red" content="'.htmlentities($this->infobox,ENT_QUOTES).'"></span>':'')
        .'<input  name="' .$this->name . '" id="' . $this->id . '"' .
            ' value="' . htmlspecialchars($this->value??'') . '"' .
            ' type="'.$this->type.'"' .                
            //($this->type=='password' ? ' type="password"' : '') . 
            
            // INPUT MATERIAL
            ($this->materialinput ? 'data-role="materialinput" ' : 'data-role="input"') .
            ($this->materialicon ? 'data-icon="' . $this->materialicon . '" ' : '') .
            ($this->materiallabel ? 'data-label="' . $this->materiallabel . '" ' : '').
            ($this->materialinformer ? 'data-informer="' . $this->materialinformer . '" ' : '').
            
            ($this->required ? ' required="required"' : '') .
            ($this->readonly ? ' readonly="readonly"' : '') .
            ($this->disabled ? ' disabled' : '') .
            ($this->uppercase ? ' uppercase="true"' : '') .
            ($this->lowercase ? ' lowercase="true"' : '') .
            ($this->mask ? ' mask="'.$this->mask.'"' : '') .
            ($this->maskpattern ? ' data-mask="'.$this->maskpattern.'"' : '') .
            
            ($this->addclass ? ' class="'.$this->addclass.'"' : '') .
            ($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
            ($this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '') .              
            ($this->pattern ? ' pattern="' . $this->pattern . '"' : '') . $this->writetags().            
        	' data-validate="'.$this->data_validate().'" autocomplete="'.$this->autocomplete.'">'.
        	($this->invalid_feedback!=''?'<span class="invalid_feedback">'.$this->invalid_feedback.'</span>':'').'</div>';
            
            /*
				data-role="materialinput" OK
				placeholder="Enter your email" OK
				data-icon="<span class='mif-envelop'>" OK 
				data-label="User email" OK
				data-informer="Enter a valid email address"
				data-cls-line="bg-cyan"
				data-cls-label="fg-cyan"
				data-cls-informer="fg-lightCyan"
				data-cls-icon="fg-darkCyan"
            */
    }    
}

class inputCurrency extends baseInput {

	public function __toString() {
    	if($this->validate==''){
    		$this->validate="number"; //integer,float 
    	}
    	if($this->validate=='float'||$this->validate=='number'){
    		$_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND];
    	}else{
    		$_SESSION['ANTIXSS'][$this->id]=[
    			FILTER_VALIDATE_INT
    		];
    	}
    	
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
        '<input type="text" id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .
			' value="'.$this->value .'"'.
			($this->datasize ? ' data-size="'.$this->datasize.'"' : '') .
			($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') . 
			($this->addclass ? ' class="'.$this->addclass.'"' : '') .
			($this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '') . 
			' data-validate="'.$this->data_validate().'" autocomplete="off">';
    }
	public function is_valid($newval) {
		return is_numeric($newval);	
	}
}

class inputNumber extends baseInput {
	public $addclass;
	public $value;
	
	public $default;
	public function __construct($options = array()) {
        $options['class']='inputNumber';
        if(!isset($options['type'])){
            $options['type']='number';        
        }        
        parent::__construct($options);
        
     
    }

	
	public function __toMongo($val){
		return ($this->data_validate=='integer'||$this->data_validate=='digits'?(int)$val :(float)$val );
	}
	
    public function __toString() {
    	global $config;
    	if($this->validate==''){
    		$this->validate="number"; //integer,float 
    	}
    	if($this->validate=='float'||$this->validate=='number'){
    		$_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND];
    	}else{
    		/*$_SESSION['ANTIXSS'][$this->id]=[
    			FILTER_VALIDATE_INT,[
		        'options' => [
		            'default' => $this->default,
		            'min_range' => $this->min,
		            'max_range' => $this->max
		        ],
		        'flags' => FILTER_FLAG_ALLOW_HEX]
    		];*/
    		$_SESSION['ANTIXSS'][$this->id]=[
    			FILTER_VALIDATE_INT
    		];
    	}
    	
        return
        ($config['usebootstrap']?
        '<label for="'.$this->id.'" class="form-label">'.$this->caption.'</label> <div class="input-group">'
        :'<div class="form-group">'. ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':''))
        .($this->infobox!=''?'&nbsp;<span class="mif-question nfinfoicon fg-red" content="'.htmlentities($this->infobox,ENT_QUOTES).'"></span>':'')
        .'<input type="number" data-role="input" id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .
			' data-validate="'.$this->data_validate().'" value="'.$this->value .'"'.
			($this->datasize ? ' data-size="'.$this->datasize.'"' : '') .
			($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') . 
			' class="form-control '.$this->addclass.'"'.
			($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
            ($this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '') . 
			' autocomplete="off"></div>';
    }
	public function is_valid($newval) {
		return is_numeric($newval);	
	}
    
}

class inputSpinner extends baseInput {
	public $addclass;
	public function __toMongo($val){
		return ($this->data_validate=='integer'||$this->data_validate=='digits'?(int)$val :(float)$val );
	}
	
    public function __toString() {
    	if($this->validate==''){
    		$this->validate="number"; //integer,float 
    	}
    	if($this->validate=='float'||$this->validate=='number'){
    		$_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND];
    	}else{
    		/*$_SESSION['ANTIXSS'][$this->id]=[
    			FILTER_VALIDATE_INT,[
		        'options' => [
		            'default' => $this->default,
		            'min_range' => $this->min,
		            'max_range' => $this->max
		        ],
		        'flags' => FILTER_FLAG_ALLOW_HEX]
    		];*/
    		$_SESSION['ANTIXSS'][$this->id]=[
    			FILTER_VALIDATE_INT
    		];
    	}
    	
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
        '<input type="text" data-role="spinner" id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .
			' data-validate="'.$this->data_validate().'" value="'.$this->value .'"'.
			($this->datasize ? ' data-size="'.$this->datasize.'"' : '') .
			($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') . 
			($this->addclass ? ' class="'.$this->addclass.'"' : '') .
			' autocomplete="off">';
    }
	public function is_valid($newval) {
		return is_numeric($newval);	
	}
    
}

class inputRating extends baseInput {
	public $tags=['data-values','onchange'];
	public $onchange;
	public function __toString() {
		return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
		'<input data-role="rating"  id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .' data-value="'.$this->value.'">';
	}
}

class inputColor extends baseInput {
	public $tags=['data-values','onchange'];
	public $onchange;
	public function __toString() {
		return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
		'<input type="color" data-role="input" id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .' value="'.$this->value.'">';
	}
}

class inputDate extends baseInput {
    
    public $type;
    public $format='YYYY-MM-DD';//='%Y-%m-%d';
    //public $inputformat='yyyy-mm-dd';
    public $clearbutton=true;
    public function __toString() {
   
		if($this->type==''){
			$this->type='calendarpicker';
		}
		//TODO provisional esperando fix en metroui 
		//$this->type='datetimepicker2date';
		
			$formats=[
				'%d'=>'([0-2][0-9]|(3)[0-1])',
				'%m'=>'(((0)[0-9])|((1)[0-2]))',
				'%Y'=>'\d{4}',
				'%y'=>'\d{2}',
				'/'=>'(\/)',
				'-'=>'-'
				];
		$tmp=$this->format;
		foreach($formats as $of=>$nf){
			$tmp=str_replace($of,$nf,$tmp);
		}
		//add $ to final of regex
		$_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_REGEXP,["options"=>["regexp"=>"/^$tmp/"]]];
    return 
            ($config['usebootstrap']?
        	'<label for="'.$this->id.'" class="form-label">'.$this->caption.'</label> <div class="input-group">'
        	:'<div class="form-group">'. ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'')).
           ($this->infobox!=''?'&nbsp;<span class="mif-question nfinfoicon fg-red" content="'.htmlentities($this->infobox,ENT_QUOTES).'"></span>':'')
        	.'<input type="date" name="' . $this->name . '" id="' . $this->id . '" data-role="input" 
            class="mx-auto form-control" 
            data-format="'.$this->format.'"
            data-date-format="'.$this->format.'"
            data-input-format="'.$this->format.'" 
            data-validate="'.$this->data_validate().'"'.
           	($this->required ? ' required="required"' : '') .
           	($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .//TODO eliminar al arreglar componente
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .
			($this->clearbutton ? ' data-clear-button="true"' : '').
			($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
           $this->addtags . ' '.($this->type=="datepicker"?'data-':' ').'value="' . $this->value 
           . '" autocomplete="off"></div>';
    }
    public function is_valid($date) {
    	$d = DateTime::createFromFormat(str_replace('%','',$this->format), $date);
    	return ($date==''&&!$this->required)||($d && $d->format($format) == $date);
	}
}
class inputTime extends baseInput {
    public $type;
    public function __toString() {
		if($this->type==''){
			$this->type='timepicker';
		}
    return 
           '<input type="time" name="' . $this->name . '" id="' . $this->id . '" data-role="input" ' .
           	($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .
			' data-validate="'.$this->data_validate().'"'.
			($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
           $this->addtags . ' value="' . $this->value . '" autocomplete="off">';
           //<a class="button" onclick="$(this).prev().datetimepicker(\'show\');"><span class="mif-calendar"></span></a>';
    }
    public function is_valid($date) {
      /*$d = DateTime::createFromFormat('H-m-i', $date);
      if(str_replace([' ','-','/','_'],['','','',''],$date)==''){
      	if ($this->required){
      		return false;
      	}else{
      		return true;
      	}
      }
	  return $d && $d->format('Y-m-d') == $date;*/
	}
}
class inputDateTime extends baseInput {
    public function __toString() {
    	global $nframework;
    	//$nframework->csss['099dtime']='//cdn.nlared.com/jquery-datetimepicker/build/jquery.datetimepicker.min.css';
    	 return 
    	 ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
    	 '<input name="' . $this->name . '" id="' . $this->id . '" class="form-control" type="datetime-local" data-role="input"' .
        	($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .  
			' data-validate="'.$this->data_validate().'"'.
        $this->addtags . ' value="' . $this->value 
        . '" data-clear-button="false"  autocomplete="off"/>';
    }
}
class inputRte extends baseInput {
    public function __toString() {
    	global $nframework;
    	$nframework->jss['005rte']='//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js';
    	$nframework->csss['005rte']='//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css';
    	//$nframework['javasonce']['rte']='$(\'textarea[data-role="jqte"]\').jqte();';
    	
    	$_SESSION['ANTIXSS'][($this->id)][0]=['html'];
    	//$nframework['modules']['rte']=true;
    	$_SESSION['ANTIXSS'][($this->id)]=['html'];
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
        '<textarea name="' . $this->name . '" id="' . $this->id. '"' .
    	($this->required ? ' required="required"' : '') .
		($this->readonly ? ' readonly="readonly"' : '') .
		($this->disabled ? ' disabled' : '') .
		($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .     
        $this->addtags . ' data-role="jqte">' . $this->value .
        '</textarea>'; 
    }
}
class inputMCE extends baseInput {
	public $upload=false;
	public $mediadir;
	public $baseurl;
	public $id;
	public $extended_valid_elements;
	public $content_css;
    public function __toString() {
    	global $nframework,$javas;
    	/*
    	'a11ychecker','advcode', 'editimage', 'powerpaste', 'tinymcespellchecker', 'tinydrive'
    	*/
    	
    	$nframework->jss['025']='https://cdn.jsdelivr.net/npm/hugerte@1/hugerte.min.js';
    	//$nframework->jss['905']='https://cdn.nlared.com/hugerte/nf.js';
    	$nframework->jss['905']='https://cdn.nlared.com/hugerte/metro/plugin.js?n='.date('ymdHis');
		/*if(!$nframework->onces['MCE']){
			$javas->addjs("
hugerte.PluginManager.add('myPlugin', function(editor, url) {
    // Agregar un botón
    editor.ui.registry.addButton('myButton', {
        text: 'Mi Botón',
        onAction: function() {
            editor.insertContent('<p>¡Hola desde el plugin!</p>');
        }
    });

    // Agregar un comando
    editor.addCommand('myCommand', function() {
        alert('Comando personalizado ejecutado');
    });

    // Evento de inicialización
    editor.on('init', function() {
        console.log('Plugin personalizado inicializado');
    });
});
			
			");
			
			$nframework->onces['MCE']=true;
		}
		//*/
    	//toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | myButton',
    	
		$javas->addjs("hugerte.init({
	selector:'textarea#".$this->id."',
	plugins: [
    'advlist', 'anchor', 'autolink', 'codesample', 'fullscreen','help',
    'image', 'lists', 'link', 'media', 'preview',
    'searchreplace', 'table', 'visualblocks', 'wordcount', 'code', 'nframework'
    ],
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | nfgrid',
	image_list: '/nframework/tinymceimgs.php?_id=".$this->id."',
	convert_urls: false,".
	($nframework->lang!='en-US'?
	"language_url: '//cdn.nlared.com/hugerte/langs/".$nframework->lang_.".js',
	language: '".$nframework->lang_."',
	":"")."
	content_css: '".$this->content_css."',
	relative_urls: false,
	//document_base_url: '//" .$_SERVER['HTTP_HOST'].$this->baseurl."',
    image_uploadtab: ".($this->upload?'true':'false').",
    images_upload_url: '/nframework/tinymceupload.php?_id=".$this->id."',
	images_upload_base_path: '" .$this->baseurl."',
	extended_valid_elements: '".$this->extended_valid_elements."',
	
	
});");
		
    	$_SESSION['ANTIXSS'][($this->id)][0]=['html'];
    	$_SESSION['ANTIXSS'][($this->id)]=['html'];
        
        $_SESSION['tinymceup'][$this->id] = [
            'mediadir'=>$this->mediadir,
        	'baseurl'=>$this->baseurl,
        ];
        
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
        '<textarea name="' . $this->name . '" id="' . $this->id
            . '"' .
        	($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .
				($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .     
            $this->addtags . ' data-role="tinyMCE">' . $this->value .
        '</textarea>';
    }
}
class textArea extends baseInput {
	var $uppercase;
	var $charscounter;
	var $charscountertemplate;
    public function __toString() {
    	$_SESSION['ANTIXSS'][($this->id)]=['html'];
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<textarea data-role="textarea" name="' .
        $this->name . '" id="' . $this->id . '"' . $this->addtag .
    	($this->required ? ' required="required"' : '') .
		($this->readonly ? ' readonly="readonly"' : '') .
		($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .   
		' data-validate="'.$this->data_validate().'"'.
		($this->uppercase ? ' uppercase="true"' : '').
		($this->addclass ? ' class="'.$this->addclass.'"' : '') .
		($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
		($this->charscounter!='' ? ' data-chars-counter="'.$this->charscounter.'"' : '') .
		($this->charscountertemplate!='' ? ' data-chars-counter-template="'.$this->charscountertemplate.'"' : '') .  
		($this->disabled ? ' disabled' : '') .            
        '>' .htmlentities($this->value) . '</textarea>';
    }
}
class AutoformList extends baseInput {
    public $options=[];
}
class inputRadios extends baseOptions{
    var $rquired;
    public function __toString() {
        $contas=0;
        foreach ($this->options as $value => $text) {
            $result.='<input type="radio" name="'. $this->name.'" id="' . $this->id .'_'. $contas . '" value="' . $value 
            . '" data-role="radio" data-caption="'.$text.'"' .
           	' labelid="'.$this->id.'" data-ovalidate="'.$this->data_validate().'"'.
            ($this->value == $value ? ' checked ' : ' ') .'/>';
            $contas++;
        }
        return ($this->caption!=''?'<label id="'.$this->id.'">'.$this->caption.'</label>':'').$result;
    }
}


function nflistoptions($options,$selected=[]):String{
	$result='';
	if(!is_array($selected)){
		$selected=[0=>$selected];
	}
	foreach($options as $value=>$text){
		if(is_array($text)){
			$result.= '<optgroup label="'.$value.'">'.nflistoptions($text,$selected).'</optgroup>';
		}else{
			$result.='<option value="' . $value . '"' .(in_array($value, $selected) ? ' selected>' : '>') . $text . '</option>';
			//$result.='<option value="' . $value . '"' . ($value == $selected ? ' selected>' : '>') . $text . '</option>';
		}
	}
	return $result;
}

class Select extends baseOptions {
	public $combobox;
    public $multiple;
    public $invalid_feedback;
    public $options=[];
   
    public $canadd;
    public $datafilter=true;
    public function __toString():String {
    	$result='';
        if ($this->combobox && $this->value != '' && !array_search($this->value, $this->options)) {
            $this->options +=[$this->value];
        }
    	$this->role='select';
        if ($this->combobox) {
            $this->role='combobox';
        }
        
        if ($this->multiple) {
            $this->role='select';
            $this->value=(array)($this->value);
        }/*
            //if (!is_array($this->value) && get_class($this->value)!='MongoDB\\Model\\BSONArray')$this->value=[];     
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' .(in_array($value, $this->value) ? ' selected>' : '>') . $text . '</option>';
            }
        } else {
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' . ($value == $this->value ? ' selected>' : '>') . $text . '</option>';
            }
        }*/
        $result.=nflistoptions($this->options,$this->value);
        
        // onfocus=\"Autoformonfocus(this)\" onblur=\"Autoformonblur(this)\">\n";		
        //$_SESSION['ANTIXSS'][$this->name]=[FILTER_VALIDATE_SELE];
        return 
            ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
            '<select name="' . $this->name . ($this->multiple ? '[]" multiple="multiple"' : '"') .
            ' id="' .$this->id . '"' . $this->writetags() .
            ' data-role="'.$this->role.'"' .
     //       ($this->required?' data-validate="required not='.$this->noselected.'"':'').
            ($this->canadd?' canadd="canadd"':'').
            ($this->onChange?' onChange="'.$this->onChange.'"':'').
            ($this->disabled?' disabled="disabled"':'').
            ($this->required?' required="required"':'').
            (!$this->datafilter?' data-filter="false"':'').
            ($this->placeholder?' data-filter-placeholder="'.$this->placeholder.'"':'').
			' data-validate="'.$this->data_validate().'"'.
            ' class="form-select" '.$this->addclass.'"'. '>' .$result.'</select>'.
        	($this->invalid_feedback!=''?'<span class="invalid_feedback">'.$this->invalid_feedback.'</span>':'');;
          
    }
    public function is_valid($newval) {
        //falta validar array
        //return($this->multiple ? true : filter_var($newval));
        //TODO: PROBAR return !is_array($newval);
        return true;
    }
}
//############################################ S T A R T ##################################################
//######################## AGREGUE PARA PONER ICONOS EN LAS OPCIONES DE LOS SELECT########################
function nflistoptionsIcons($options,$selected=[]){
	if(!is_array($selected)){
		$selected=[0=>$selected];
	}
	foreach($options as $value=>$text){
		if(is_array($text)){
			$result.='<option value="' . $value . '" data-template="'.$text['icon'].'" ' .(in_array($value, $selected) ? ' selected>' : '>') . $text['datashow'] . '</option>';
			//$result.= '<optgroup label="'.$value.'">'.nflistoptions($text,$selected).'</optgroup>';
		}else{
			$result.='<option value="' . $value . '"' .(in_array($value, $selected) ? ' selected>' : '>') . $text . '</option>';
       
			//$result.='<option value="' . $value . '"' . ($value == $selected ? ' selected>' : '>') . $text . '</option>';
		}
	}
	return $result;
}

class SelectIcon extends baseOptions {
	public $combobox;
    public $multiple;
    public $options=[];
   
    public $canadd;
    public $datafilter=true;
    public function __toString() {
        if ($this->combobox && $this->value != '' && !array_search($this->value, $this->options)) {
            $this->options +=[$this->value];
        }
    	$this->role='select';
        if ($this->combobox) {
            $this->role='combobox';
        }
        
        if ($this->multiple) {
            $this->role='select';
            $this->value=(array)($this->value);
        }/*
            //if (!is_array($this->value) && get_class($this->value)!='MongoDB\\Model\\BSONArray')$this->value=[];     
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' .(in_array($value, $this->value) ? ' selected>' : '>') . $text . '</option>';
            }
        } else {
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' . ($value == $this->value ? ' selected>' : '>') . $text . '</option>';
            }
        }*/
        $result.=nflistoptionsIcons($this->options,$this->value);
        
        // onfocus=\"Autoformonfocus(this)\" onblur=\"Autoformonblur(this)\">\n";		
        //$_SESSION['ANTIXSS'][$this->name]=[FILTER_VALIDATE_SELE];
        return 
            ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').
            ($this->infobox!=''?'&nbsp;<span class="mif-question nfinfoicon fg-red" content="'.htmlentities($this->infobox,ENT_QUOTES).'"></span>':'')
        	.'<select name="' . $this->name . ($this->multiple ? '[]" multiple="multiple"' : '"') .
            ' id="' .$this->id . '"' . $this->writetags() .
            ' data-role="'.$this->role.'"' .
            ($this->canadd?' canadd="canadd"':'').
            ($this->disabled?' disabled="disabled"':'').
            ($this->required?' required="required"':'').
            (!$this->datafilter?' data-filter="false"':'').
            ($this->placeholder?' data-filter-placeholder="'.$this->placeholder.'"':'').
						($this->validate? 'data-validate="'.$this->validate.'"':'').
			($this->multiple?' multiple':'').
            ($this->addclass? ' class="'.$this->addclass.'"':''). '>' .$result.'</select>'.
        	($this->invalid_feedback!=''?'<span class="invalid_feedback">'.$this->invalid_feedback.'</span>':'');;
          
    }
    public function is_valid($newval) {
        //falta validar array
        //return($this->multiple ? true : filter_var($newval));
        //TODO: PROBAR return !is_array($newval);
        return true;
    }
}
//######################## AGREGUE PARA PONER ICONOS EN LAS OPCIONES DE LOS SELECT #######################
//############################################ E N D #####################################################

class inputCheckBox extends baseInput {
    public $caption;
    public $type;
    public function __toString() {
    	global $config;
       // $name=($this->dataset!=''?$this->dataset->nameprefix.'['.$this->name.']':$this->name);
        
        if ($this->type==''){
            $this->type='checkbox';
        }
        $result= '<input name="' . $this->name . '" id="' . $this->id 
                . '" type="checkbox" data-role="'.$this->type.'" data-caption="'.$this->caption.'"' .
                ($this->value != '' ? ' checked' : '').
                ($this->disabled != '' ? ' disabled' : '').
                $this->addtags . '>';
                
        if($config['usebootstrap'])       {
        	$result='<div class="form-check">'.$result.'
  <label class="form-check-label" for="'.$this->id.'">
    '.$this->caption.'
  </label>';	
        }
        return $result;
        $_SESSION['ANTIXSS'][$this->id]=[FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE];
        
    }
}
class inputCheckBoxs extends Select {
	public $captionposition="right";
	public $horizontal=false;
	public $nometro=false;
    public function __toString() {
        $result = '';
        $tempcheck = $this->value;
        if($this->type==''){
            $this->type='checkbox';
        }
        foreach ($this->options as $value => $text) {
            $result.= ($this->horizontal?'<br>':'').'<input type="checkbox" data-role="checkbox" id="'.$this->id.'_'.$value.'" name="' .
                    $this->name . "[$value]\"" .
                    ($tempcheck[$value] == 'on' ? ' checked' : '') .
                    " data-caption=\"$text\" data-caption-position=\"".$this->captionposition."\">".
                    ($this->nometro?'<label for="'.$this->id.'_'.$value.'">'.$text.'</label>':'')
                    ;
            //$fields.=str_replace('%field%', $result, $this->format['fields'][2]);
            $_SESSION['ANTIXSS'][$this->id.'_'.$value]=[FILTER_VALIDATE_BOOLEAN];
        }
        //$result = str_replace('%fields%', $fields, $this->format['fields'][1]);
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').$result;
        
    }
}
class inputfile extends baseInput{
	public $id;
	public $path;
	public $dir;
	public $drop;
	public $accept;
	function __toString(){
		global $javas,$nframework;
		$nframework->addjqueryui();
    	$nframework->addfileupload();
		$_SESSION['uploads4'][$this->id] = [
            'dir' => dirname($this->path),
            'formname' => $this->name,
            'delete'=>$this->delete,
            'download'=>$this->download,
            'preview'=>$this->preview,
            'extension'=>$nframework->api_path.'/uploadfile_ext_path.php',
            'extensioninfo'=>['path'=>$this->path],
            'onupload'=>'onupload',
            'ondelete'=>'ondelete',
        	'onlist'=>'onlist',
            'countlimit'=>100,
          //  'sizelimit'=>$this->sizelimit,
            'limit_time_start'=>($this->limit_time_start==''?time():$this->limit_time_start),
            'limit_time_end'=>($this->limit_time_end==''? strtotime("+30 minutes"):$this->limit_time_end),
        ];
		
		$javas->addjs('
	$("#'.$this->id.'").fileupload({
      url:  \'/nframework/uploadfile.php\',
      dataType: "json",
      maxNumberOfFiles: 1,
      done: function (e, data) {
          $.each(data.result.files, function (index, file) {
              $("<p/>").text(file.name).appendTo("#files");
          });
          	
          	$.ajax({
				url: "/nframework/preview.php?id='.$this->id.'", 
				cache: false,
				success: function (result) {
					var bhtml="";
					
					$.each(result.links, function (index, link) {
	            		bhtml+=\'<img src="\'+link+\'" loading="lazy">\';
					});
	    			$("#'.$this->id.'_preview").html(bhtml);
				}
			});
      },
      progressall: function (e, data) {
        	var progress = parseInt(data.loaded / data.total * 100, 10);		
	        var pg=$("#'.$this->id.'_progress");
	        if (progress==100){
	        	pg.hide();
			}else{
	        	pg.show();
	        	pg.attr("data-value",progress);
	        
	      	}
      }
	}).bind("fileuploadcompleted",function(e,data){
          console.log("eventFinished");
        }).prop("disabled", !$.support.fileInput)
      .parent().addClass($.support.fileInput ? undefined : "disabled");
','ready');
		return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<p>'.
		'<input type="file" id="'.$this->id.'" name="'.$this->id.'"'.
		($this->disabled ? ' disabled' : '') .
        ($this->prepend ? ' data-prepend="'.$this->prepend.'"' : '').
        ($this->drop ? ' data-mode="drop" data-files-title="archivo(s) seleccionado(s)" data-drop-title="<strong>Selecciona archivo(s)</strong>" ' : '') .
        ($this->accept ? ' accept="'.$this->accept.'"' : '') .'
data-sequential-uploads="true" placeholder="Arrastra hasta aqui para subir archivos"
data-role="file" data-button-title="<span class=\'mif-folder\'></span>"
data-form-data=\'{"mid":"' . $this->id . '"}\'/>
		<div data-role="progress" id="'.$this->id.'_progress" data-type="buffer" data-value="0" data-buffer="100" data-small="true"></div>
		<div id="'.$this->id.'_preview" style="overflow-y: auto;overflow-x: hidden;height:200px">
		</div>';



	}
}





class inputFiles extends baseInput {
	public $dir;
	public $download;
	public $preview;
	public $delete;
	public $disabled;
	public $accept;
	public $drop=true;
	public $ondelete;
	public $onupload;
	public $onlist;
	public $extension;
	public $extensioninfo=[];
	public $countlimit;
	public $limit_time_start;
	public $limit_time_end;
    public function __toString() {
    	global $nframework,$javas;
    	
    //		$javas->addjs('
	//$("#'.$this->id.'").
		$javas->addjs(
    	'jQuery("input[data-sequential-uploads=\'true\']").fileupload({
		url: \'/nframework/uploadfile.php\',
		    sequentialUploads: true,
		dataType: \'json\',
		progressall: function (e, data) {
			var mid=$(this).attr("id");
	        var progress = parseInt(data.loaded / data.total * 100, 10);		
	        var pg=$("#"+mid+"_progressbar");
	        if (progress==100){
	        	pg.hide();
			}else{
	        	pg.show();
	        	pg.attr("data-value",progress);
	        	//console.log(progress);
	        }        
	    },
	    done:function (e, data) {
	    	var mid=$(this).attr("id");
	    	//console.log(data);
	    	nfFileMakeTable(mid,data.result);
	    	toast("Carga de archivo completa");
	    },
	    fail: function(e, data) {
	    	var o=$(this).attr(\'id\');
	  		alert(\'Fail!\'+o);
		}
	});
');
    	$nframework->addjqueryui();
    	$nframework->addfileupload();
    	if($this->id=='')$this->id='veamos';
        $_SESSION['uploads4'][$this->id] = [
            'dir' => $this->dir,
            'formname' => $this->name,
            'delete'=>$this->delete,
            'download'=>$this->download,
            'preview'=>$this->preview,
            'extension'=>$this->extension,
            'extensioninfo'=>$this->extensioninfo,
            'onupload'=>$this->onupload,
            'ondelete'=>$this->ondelete,
        	'onlist'=>$this->onlist,
            'countlimit'=>intval($this->countlimit),
            'sizelimit'=>$this->sizelimit,
            'limit_time_start'=>($this->limit_time_start==''?time():$this->limit_time_start),
            'limit_time_end'=>($this->limit_time_end==''? strtotime("+30 minutes"):$this->limit_time_end),
        ];
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<p>
        <input name="' . $this->name . '" id="' . $this->id . '" type="file"' .
        ($this->disabled ? ' disabled' : '') .
        ($this->prepend ? ' data-prepend="'.$this->prepend.'"' : '').
        ($this->drop ? ' data-mode="drop" data-files-title="archivo(s) seleccionado(s)" data-drop-title="<strong>Selecciona archivo(s)</strong>" ' : '') .
        ($this->accept ? ' accept="'.$this->accept.'"' : '') .
        ' data-url="/nframework/uploadfile.php"
data-sequential-uploads="true"'.($this->justone?'':' multiple').' placeholder="Arrastra hasta aqui para subir archivos"
data-role="file" data-button-title="<span class=\'mif-folder\'></span>"
data-form-data=\'{"mid":"' . $this->id . '"}\'/><div id="' . $this->id . '_list"></div>
<div data-role-aux="file-progress" style="display: none;" data-role="progress" id="' . $this->id . '_progressbar"></div></p>';
    }
}

class mapmarker extends baseInput{
//	public $latitude;
//	public $longitude;
	public $onchange;
	public $height=500;
	public $value=[
		'lat'=>'',
		'lng'=>''
		];
	public $startpoint=[
		'lat'=>25.43328030,
		'lng'=>-100.96047970,
		];
	public function __toString(){
		global $nframework,$javas;
    	$nframework->csss['005rte']='https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="';
    	$nframework->jss['100leaflet']='https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin="';
    	
    	if(!$nframework->onces['maps']){
    		$javas->addjs('var maps=[];');
    		$nframework->onces['maps']=true;
    	}
    	if(!$nframework->onces['mapsmarker']){
    		$javas->addjs('var mapsmarker=[];');
    		$nframework->onces['mapsmarker']=true;
    	}
		$css='<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	crossorigin=""/>';
	
   
	$lat=($this->value['lat']!=''?$this->value['lat']:$this->startpoint['lat']);
    $lng=($this->value['lng']!=''?$this->value['lng']:$this->startpoint['lng']);
	$javas->addjs("
		var startPoint = [$lat,$lng];
		maps['".$this->id."_map'] = L.map('".$this->id."_map', {editable: true}).setView(startPoint, 16),
    	tilelayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {maxZoom: 20, attribution: 'Data \u00a9 <a href=\"https://www.openstreetmap.org/copyright\"> OpenStreetMap Contributors </a> Tiles \u00a9 HOT'})
		.addTo(maps['".$this->id."_map']);
		
		mapsmarker['".$this->id."_mapmarker'] = new L.marker([$lat,$lng], { draggable:'true'});
    	mapsmarker['".$this->id."_mapmarker'].on('dragend', function(event){
            var marker = event.target;
            var position = marker.getLatLng();
            maps['".$this->id."_map'].flyTo(position);
            $('#".$this->id."_lat').val(position.lat);
            $('#".$this->id."_lng').val(position.lng);
            ".(!empty($this->onchange)?$this->onchange:'')."
            marker.setLatLng(position,{draggable:'true'}).bindPopup(position).update();
    	});
		maps['".$this->id."_map'].addLayer(mapsmarker['".$this->id."_mapmarker']);
		");


		return '<div id="'.$this->id.'_map" style="height:'.$this->height.'px;"></div>
		<input name="' . $this->name . '[lat]" id="' . $this->id . '_lat" type="text" value="'.$lat.'">
		<input name="' . $this->name . '[lng]" id="' . $this->id . '_lng" type="text" value="'.$lng.'" >
		';
	}
}



class example extends Base{
    public $content;
    public $title;
    public function __toString() {
        return '<div class="example" data-text="'.$this->title.'">'.$this->content.'</div>';
    }
}

class datasetpdo{
	public $elements; 
    private $collection;
    private $_id;
    public $info=[];
    public $nameprefix;
    public $simpleid;
    public $autosave;
    public $position;
    public $fieldprefix;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    
    public function __construct($options,$query=[]) {              
        foreach ($options as $option=>$value){
            $this->{$option}=$value;
        }
        if ($this->_id != '' && $this->key!='') {
        	
        	
        	//$this->info =(array) $this->collection->findOne(['_id'=>$this->_id]);
            $sth=$this->pdo->query('SELECT * FROM '.$this->table.' WHERE '.$this->key.'="'.$this->_id.'"');
            $this->info=$sth->fetch(PDO::FETCH_ASSOC);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }else{
            	$this->exists=true;
            }
        }
        unset($this->info['']);
    }
    public function save(){
    	foreach($this->elements as $element){
        	$element->value=$_POST[$this->nameprefix][$element->field];
            if($element->disabled!=false&& !$element->is_valid($_POST[$this->nameprefix][$element->field])){
                $errores.='Error en:'.$element->field.'<br/>';
            }
        }
        if ($errores==''){
        		
    		if(!$this->exists){
    			foreach($this->elements as $element){
    				$changes[$element->field]=$_POST[$this->nameprefix][$element->field];
    			}
    			$sql='INSERT INTO '.$this->table
    			.' ('.implode(',',array_keys($changes))      			.') values("'.implode('","',$changes).'")';
    		}else{
    			
    			foreach($this->elements as $element){
    				if($element->field==$this->key){
    					$where=' where '.$element->field.'="'.$this->_id.'"';
    				}else{
    					$sqls[]=$element->field.'="'.$_POST[$this->nameprefix][$element->field].'"';
    				}
    			}
    			$sql.='UPDATE '.$this->table.' SET '.implode(',',$sqls).$where;
    			
    		}
    		echo $sql;
    		$this->pdo->query($sql);
        }
    }
    public function __get($name) {
        $result=false;
        if ($name!=''){
	        if ($name=='_id'){
	            $result= (string)$this->_id;
	        }else{
	             if (array_key_exists($name, $this->info)) {
	            	if (gettype($this->info[$name])=='object'){
	            		$result=iterator_to_array($this->info[$name],true);
	            	}else{
	                	$result= $this->info[$name];
	            	}
	            }
	        }
        }
        return $result;
    }
    public function __isset($name) {
        return isset($this->info[$name]);
    }
}

#[\AllowDynamicProperties]
class dataset  {
    public $elements=[]; 
    private $collection;
    private $_id;
    public $info=[];
    public $nameprefix;
    public $simpleid;
    public $autosave;
    public $mongo_session;
    public $position;
    public $fieldprefix;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    public function __construct($options,$query=[]) {              
        foreach ($options as $option=>$value){
            $this->{$option}=$value;
        }
        if ($this->_id != '') {
        	$this->_id=($this->simpleid==true ?
                    trim($this->_id)
                    :new MongoDB\BSON\ObjectID(trim($this->_id ))
                );
             
            $this->info =(array) $this->collection->findOne(['_id'=>$this->_id]);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }
        }else{
        	$this->_id=new MongoDB\BSON\ObjectID();
            $this->info = ['_id' => $this->_id];
          
	
        }
        unset($this->info['']);
    }
    public function refresh(){
    	if ($this->_id != '') {           
            $this->info = $this->collection->findOne(['_id'=>
                ($this->simpleid==true ?
                    trim($this->_id)
                    : new MongoId(trim($this->_id ))
                )
                ]);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }
        }
    }
    public function __isset($name) {
        return isset($this->info[$name]);
    }
    public function __set($name, $value) {
        if ($name!='_id') {
        	if(property_exists($this,$name)){
        		$this->{$name}=$value;
        	}else{
	        	$options=[];
	        	if(!empty($this->mongo_session)){
	        		$options['session']=$this->mongo_session;
	        	}
	            if ($this->info[$name] != $value) {
	                $this->info[$name] = $value;
	                if ($this->_id==''){
	                	$r=$this->collection->insertOne($this->info,$options);
	                	$this->info['_id']=$r['_id'];
	                }else{
	                	$options['upsert']=true;
	                	$this->collection->updateOne(['_id'=>$this->_id ],['$set'=>[$name=>$value]],$options);
			               $this->info[$name]=$value;
			      //       echo "set"; 
			       //      print_r($this->_id);  	
	                	//$this->collection->save($this->info);
	                }
	                //$this->col->update(['_id'=>$this->id],['$set'=>[$name=>$value]]);            
	            }
        	}   
        }
        return true;
    }
    public function __unset($name) {
       if ($name!='_id'){
       		$options=[];
        	if(!empty($this->mongo_session)){
        		$options['session']=$this->mongo_session;
        	}
            unset($this->info[$name]);            
            $this->collection->updateOne(
                	['_id'=>$this->info['_id']],
                	['$unset'=>[$name=>'']],$options);
            
            //$this->col->update(['_id'=>$this->id],['$unset'=>[$name=>1]]);            
        }
        return true;
    }
    public function __get($name) {
        $result=null;
        if ($name!=''){
	        if ($name=='_id'){
	            $result= (string)$this->_id;
	        }else{
	             if (array_key_exists($name, $this->info)) {
	            	if (gettype($this->info[$name])=='object'){
	            		$result=iterator_to_array($this->info[$name],true);
	            	}else{
	                	$result= $this->info[$name];
	            	}
	            }
	        }
        }
        return $result;
    }
    public function save(){
		$options=[];
    	if(!empty($this->mongo_session)){
    		$options->session=$this->mongo_session;
    	}
        foreach($this->elements as $element){
        	$element->value=$element->__toMongo($_POST[$this->nameprefix][$element->field]);
            if($element->disabled!=false&& !$element->is_valid($_POST[$this->nameprefix][$element->field])){
                $errores.='Error en:'.$element->field.'<br/>';
            }
        }
        if ($errores==''){
        	$toset=[];
        	$tounset=[];
	        foreach($this->elements as $element){
	        	if ($element->field=='_id'){
	        		$element->value=(string)$this->_id;
	        	}else{
	        		if($_POST[$this->nameprefix][$element->field]==''){
        				//assignArrayByPath($tounset,$element->field,1,'.');
        				$changes['$unset'][str_replace('$',$this->position,$this->fieldprefix.$element->field)]=1;
        			}else{
        				//assignArrayByPath($toset,$element->field,$_POST[$this->nameprefix][$element->field],'.');
        		
        				$changes['$set'][str_replace('$',$this->position,$this->fieldprefix.$element->field)]=$element->__toMongo($_POST[$this->nameprefix][$element->field]);
        			}
	        		if(strpos($element->field,'.')!==false){
	        			$punto=true;
	        		}else{
	        			$this->info[$element->field]=$element->__toMongo($_POST[$this->nameprefix][$element->field]);
	        		}
	        	}
	        }
	        if($punto){
	        //	echo '<textarea>'.print_r($changes,true).'</textarea>';
	        	$this->collection->updateOne(['_id'=>$this->_id],$changes,['upsert'=>true],$options);
	        }else{
	        	$options['upsert']=true;
	        	$this->collection->updateOne(['_id'=>$this->_id],['$set'=>$this->info],$options);
	        }
	         return false;
        }else{
        	// $errores;
        	return $errores;
        }
    }
}

class datasetArray{
    private $info;
    public $elements;
    public $nameprefix;
    public $dataset;
    public $name;
    public $field;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    public function __construct($options) {
        $ovars=array_keys( get_object_vars($this));
        foreach ($options as $option => $value) {
            if($option=='value'){
               $this->value=$value;
            }elseif($option=='dataset'){
                $this->dataset=$value;
                $value->addElement($this);               
            }elseif(in_array($option, $ovars)){
                $this->{$option} = $value;
            }else{
                $this->tags[$option]=$value;
            }
        }        
        if($this->dataset!=''){
            if($this->name=='' && $this->field!=''){
                $this->name=$this->field;
            }
            $this->name=$this->dataset->nameprefix.'['.$this->name.']';
            $this->nameprefix=$this->name;
            $this->value=$this->dataset->{$this->field};
        }
    }
    public function save(){
        $this->dataset->{$this->field}=$this->info;
    }
    public function is_valid($value){
        return true; //TODO check 
    }
}

class Icon{
	public $src;
	public function __construct($src){
		$this->src=$src;
	}
	public function __toString(){
		return (strpos($this->src,'.')===false?
            '<span class="icon mif-'.$this->src.'"></span> ':
            '<img src="'.$this->src.'" class="icon">'
        );
	}
}

class TreeViewItem{
	public $children;
	public $icon;
	public $caption;
	public $addnodetag;
	public function __construct($caption,$icon,$options=[]){
		$this->caption=$caption;
		$this->icon=$icon;
		foreach ($options as $option=>$valor){
			$this->{$option}=$valor;
		}
	}
	public function __toString(){
		if(count($this->children)>0){
			$tmp.='<ul>'.implode('',$this->children).'</ul>';
		}
		return '<li class="item" '.$this->addnodetag.' data-icon="'.$this->icon->data().'" data-caption="'.$this->caption.'">'.$tmp.'</li>';
	}
}
class TreeView{
	public $children;
	public function __toString(){
		return 	'<ul data-role="treeview"
			     id="tree_add_leaf_example">'.implode('',$this->children).'</ul>';
	
	}
}

