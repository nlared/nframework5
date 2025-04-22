<?php
class Sidebar{
	public string $title="Sidebar";
	public string $sidemenu='';
	public string $content='';
	public string $menuAdd='';
	public string $color='cyan';
	public string $contentclass='';
	public string $focuscolor='cyan';
	public string $darkcolor='darkCyan';
	public string $footer='';
	public function __construct($options){
		foreach($options as $k=>$v){
			$this->{$k}=$v;
		}
	}
	
	function __toString():string{
		global $javas,$user,$nframework,$config;
		$nframework->csss['101']='https://cdn.nlared.com/pandora/css/index.css';
		$javas->addjs("
$('#buscar').keyup(function(){
    $('#side-menu>li>a').hide();
    
    var a=$(this).val();
    console.log(a);
    if (a!=''){
		$('#side-menu>li>a:not(:containsi(\"'+a+'\")').show();
    }else{
    	$('#side-menu>li>a').show();
    }
});
$('.item').click(function(e) {
  	window.location.href = $(this).attr('link') ;
   	e.preventDefault();
    e.stopPropagation();
    return false;
});
");

$nframework->docend[]='</div></div></div>';		

	
	$BreadCrumbs=new BreadCrumbs();
$result= '
<body class="m4-cloak h-vh-100">
<div data-role="navview" data-toggle="#paneToggle" class="navbar" data-expanded="xl" data-compact="lg" data-active-state="true">
    <div class="thenavview navview-pane" style="z-index:1000">
        <div class="controlnavview bg-'.$this->color.' d-flex flex-align-center">
            <button class="pull-button m-0 bg-'.$this->darkcolor.'-hover bg-'.$this->focuscolor.'-focus">
                <span class="mif-menu fg-white"></span>
            </button>
            <h2 class="text-light m-0 fg-white pl-7" style="line-height: 52px">'.$this->title.'</h2>
        </div>
        <div class="suggest-box">
            <input type="text" id="buscar" data-role="input" data-clear-button="false" data-search-button="true">
            <button class="holder">
                <span class="mif-search fg-white"></span>
            </button>
        </div>
        <ul class="thenavviewmenu navview-menu nav flex-column mt-4" id="side-menu">
           '.$this->sidemenu.'
        </ul>
        <div class="thenavviewcredit w-100 text-center text-small data-box p-2 border-top bd-grayMouse" style="position: absolute; bottom: 0">
            '.$this->footer.'
            <div>Created with <a href="https://nframework.nlared.com" target="_blank" class="text-muted fg-white-hover no-decor">nframework 5</a></div>
        </div>
    </div>
    <div class="navview-content h-100">
        <div data-role="appbar" class="thebar pos-absolute bg-'.$this->darkcolor.' fg-white">
            <a href="#" class="app-bar-item d-block d-none-lg" id="paneToggle"><span class="mif-menu"></span></a>
            <div class="app-bar-container ml-auto">
            	'.$this->menuAdd.$user->notifications->icon().'
            	
                <div class="app-bar-container">
                    '.$user->usermenu().'
                </div>
            </div>
        </div>
        <div id="content-wrapper" class="content-inner h-100 '.($this->contentclass).' " style="overflow-y: auto">
        <div class="row border-bottom bd-lightGray m-3">
		    <div class="cell-md-4 d-flex flex-align-center">
		        <h3 class="dashboard-section-title text-center text-left-md w-100">'.$this->title.'<small>'.$this->subtitle.'</small></h3>
		    </div>
		    <div class="cell-md-8 d-flex flex-justify-center flex-justify-end-md flex-align-center">
		        '.$BreadCrumbs.'
		    </div>
		</div>';
        
      return $result;  
	}
}
