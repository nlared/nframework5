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
		$cparts=count($parts)-1;
		foreach($parts as $i=>$part){
			if($part!='index.php'){
				$title=($i==$cparts?strstr($part, '.', true):$part);
				$title=ucwords($title);
				$crumbs.='<li class="page-item"><a href="'.$this->root.$add.$part.'" class="page-link">'.$title.'</a></li>';
				$add=$part.'/';
			}
		}
		
		return '<ul class="breadcrumbs bg-transparent p-0 m-0 text-small visible-md no-visible">'.$crumbs.'</ul>';
        
	}
}