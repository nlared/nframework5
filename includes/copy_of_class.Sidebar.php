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
	//	$nframework->csss['101']='https://cdn.nlared.com/pandora/css/index.css';
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

$nframework->docend[]='</main>
    </div>
</div>';		

	
	$BreadCrumbs=new BreadCrumbs();
	
	/*
	<div class="app-bar-item-static d-none-fs d-flex-md">
                <div class="text-bold enlarge-2" id="content-title">[...]</div>
            </div>

            <ul class="app-bar-menu ml-auto">
                <li><a data-hotkey="alt+h" href="https://metroui.org.ua">Metro UI</a></li>
                <li><a href="https://docs.metroui.org.ua">Docs</a></li>
                <li><a href="https://pimenov.com.ua">Author</a></li>
                <li><a href="https://github.com/olton/metroui"><span class="mif-github mif-2x"></span></a></li>
            </ul>

            <div class="app-bar-item-static">
                <input type="checkbox" data-role="theme-switcher" />
            </div>
	
	
	<a href="/" class="d-flex flex-align-center text-logo bg-transparent" style="width: calc(100% - 54px)">
                <img src="/images/panda.png" alt="logo" height="30" width="30" class="border-radius-half"/>
                <div class="enlarge-2 ml-3 text-weight-9">Panda 1.0</div>
            </a>
	
	*/
	
$result= '
<body class="cloak">
<div id="navview" data-role="navview" data-expand-point="md">
    <div class="navview-pane">
        <div class="logo-container">
            <button class="pull-button">
                <span class="mif-menu"></span>
            </button>
            '.$this->title.'
        </div>

        <div class="suggest-box mt-4">
            <input type="text" data-role="input" data-clear-button="false" data-search-button="true">
            <button class="holder">
                <span class="mif-search"></span>
            </button>
        </div>
        <ul class="navview-menu pad-second-level" id="side-menu">
        '.$this->sidemenu.'
        </ul>
    </div>
	<div class="navview-content">
        <div data-role="appbar" class="bg-reserve-steppe border-bottom bd-default" data-expand-point="fs">
            <div class="app-bar-item-static d-none-fs d-flex-md">
                <div class="text-bold enlarge-2" id="content-title">[...]</div>
            </div>

            <ul class="app-bar-menu ml-auto">
                '.$this->menuAdd.$user->usermenu().'
            </ul>

            <div class="app-bar-item-static">
                <input type="checkbox" data-role="theme-switcher" />
            </div>
        </div>
        <main id="page-content">
        ';
      return $result;  
	}
}
