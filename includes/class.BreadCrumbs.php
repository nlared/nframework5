<?
class BreadCrumbs{
	public $root;
	function __construct($options=[]){
		
	
		if(empty($this->root)){
			$this->root='/';
			$this->self=substr($_SERVER['PHP_SELF'],1);
		}
	}
	
	
	function __tostring(): string
	{
		$crumbs='<li class="page-item"><a href="'.$this->root.'" class="page-link"><span class="mif-meter"></span></a></li>';
		$parts=explode('/',$this->self);
		foreach($parts as $part){
			$crumbs.='<li class="page-item"><a href="'.$this->root.$add.$part.'" class="page-link">'.ucwords($part).'</a></li>';
			$add=$part.'/';
		}
		
		return '<ul class="breadcrumbs bg-transparent">'.$crumbs.'</ul>';
        
	}
}