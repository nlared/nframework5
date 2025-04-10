<?
class embededArray{
	public $action;
	public $id;
	public $containerid;
	public $dialogid;
	public $dataset;
	public $embededArray;
	
	
	private $database;
	private $collection;
	public $nameprefix;
	private $historic;
	private $_id;
	private $simpleid;
	
	public $template;
    public function __construct($options=[]){
    	global $nframework;
		foreach ($options as $option => $value) {
    		$this->{$option}=$value;	        
        }
       
        $this->database=$this->dataset->collection->getDatabaseName();
		$this->collection=$this->dataset->collection->getCollectionName();
		$this->nameprefix=$this->dataset->nameprefix;
		$this->historic=$this->dataset->historic;
		$this->simpleid=$this->dataset->simpleid;
		$this->_id=$this->dataset->_id;
		if(empty($this->id)){
        	$this->id='ArrayFront_'.hash('crc32', $_SERVER['PHP_SELF'].$this->_id).'_'.$nframework->counters('ajaxdialog');
        }
    }
    public function function_new(){
    	return $this->id.'_show()';
    }
	public function __toString(){
	
		$_SESSION['nfembeded'][$this->id]=[
			'database'=>$this->database,
			'collection'=>$this->collection,
			'nameprefix'=>$this->nameprefix,
			'historic'=>$this->historic,
			'simpleid'=>$this->simpleid,
			'_id'=>$this->_id,
			'field'=>$this->field,
			'template'=>$this->template,
		];
		global $javas;
$java=<<<JAVA
	function {$this->id}_show(){
		{$this->dialogid}.showModal();
    	$('#{$this->dialogid}_op').val('add');
	}

	function {$this->id}_load(){
		$.ajax({
			url: "/nframework/embeded.php?_id={$this->id}",
			method: 'post',
		}).done(function(result) {
			$('#{$this->containerid}').html(result.container);
		});
			
	}
	function {$this->id}_get(pos){
		$.ajax({
			url: "/nframework/embeded.php?_id={$this->id}",
			method: 'post',
			data:{
				op: 'load',
				pos: pos
			}
		}).done(function(result) {
			{$this->dialogid}.showModal();
			$("#{$this->dialogid}_op").val('update');
			$('#{$this->dialogid}_pos').val(pos);
			
			Object.keys(result.item).forEach(key => {
				console.log(key);
				 
			    const input = document.querySelector('#{$this->nameprefix}_'+key);
			    if (input) {
			        input.value = result.item[key];
			    }
			});
		});
			
	}
	function {$this->id}_ok(){
		formData = $("#{$this->dialogid}_form").serialize();
		$.ajax({
			url: "/nframework/embeded.php?_id={$this->id}",
			method: 'post',
			data: formData
		}).done(function(result) {
			$('#{$this->containerid}').html(result.tabla);
		});
		{$this->dialogid}.close();
	}
	function {$this->id}_delete(pos){
		Swal.fire({
			title: 'Estas seguro?',
			text: 'No podras deshacer esto!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, borrar!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "/nframework/embeded.php?_id={$this->id}",
					method: 'post',
					data:{
						op: 'delete',
						pos: pos
					}
				}).done(function(result) {
					$('#{$this->containerid}').html(result.container);
				});
			}
		});
	}
	{$this->id}_load();
	$('#{$this->dialogid}_btnAcept').on("click", function(){
		formData = $("#{$this->dialogid}_form").serialize();
		$.ajax({
			url: "/nframework/embeded.php?_id={$this->id}",
			method: 'post',
			data: formData
		}).done(function(result) {
			$('#{$this->containerid}').html(result.container);
		});
		{$this->dialogid}.close();
	});
	
JAVA;
	
		$javas->addjs($java);
		return '';
	}
}