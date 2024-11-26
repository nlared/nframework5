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
if($config['usebootstrap']){
	
	
	$nframework->csss['100']='https://vitali.nlared.com/assets/js/main.js';
$nframework->csss['101']='https://vitali.nlared.com/assets/css/style.css';
$nframework->csss['051']='https://vitali.nlared.com/assets/libs/simplebar/simplebar.min.css';
$nframework->csss['052']='https://vitali.nlared.com/assets/css/icons.css';
$nframework->csss['053']='https://vitali.nlared.com/assets/libs/flatpickr/flatpickr.min.css';
$nframework->csss['054']='https://vitali.nlared.com/assets/libs/@simonwep/pickr/themes/nano.min.css';
$nframework->csss['055']='https://vitali.nlared.com/assets/libs/choices.js/public/assets/styles/choices.min.css';
 
$nframework->jss['069']='https://vitali.nlared.com/assets/libs/@popperjs/core/umd/popper.min.js';
$nframework->jss['050']='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous';
$nframework->jss['101']='https://vitali.nlared.com/assets/js/main.js';
$nframework->jss['102']='https://vitali.nlared.com/assets/js/defaultmenu.min.js';
$nframework->jss['103']='https://vitali.nlared.com/assets/libs/node-waves/waves.min.js';
$nframework->jss['104']='https://vitali.nlared.com/assets/js/sticky.js';
$nframework->jss['105']='https://vitali.nlared.com/assets/libs/simplebar/simplebar.min.js';
$nframework->jss['106']='https://vitali.nlared.com/assets/js/simplebar.js';
$nframework->jss['107']='https://vitali.nlared.com/assets/libs/@simonwep/pickr/pickr.es5.min.js';
$nframework->jss['108']='https://vitali.nlared.com/assets/libs/apexcharts/apexcharts.min.js';
//$nframework->jss['109']='https://vitali.nlared.com/assets/js/checkbox-selectall.js';
//$nframework->jss['110']='https://vitali.nlared.com/assets/js/index1.js';
//$nframework->jss['111']='https://vitali.nlared.com/assets/js/custom-switcher.min.js';
//$nframework->jss['112']='https://vitali.nlared.com/assets/js/custom.js?t='.date('mdhmi');
$nframework->html_addtag=' dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark"';
$nframework->body_addtag=' class="app sidebar-mini"';
	
	$result='<!-- PAGE -->
<div class="page">
    <div class="page-main">
        <!-- app-header -->
        <header class="app-header header sticky">
            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid">
                <!-- Start::header-content-left -->
                <div class="header-content-left align-items-center">
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="index.html" class="header-logo">
                                <img src="../assets/images/brand/desktop-logo.png" alt="logo" class="desktop-logo">
                                <img src="../assets/images/brand/toggle-logo.png" alt="logo" class="toggle-logo">
                                <img src="../assets/images/brand/desktop-dark.png" alt="logo" class="desktop-dark">
                                <img src="../assets/images/brand/toggle-dark.png" alt="logo" class="toggle-dark">
                            </a>
                        </div>
                    </div>
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link -->
                        <a href="javascript:void(0);" class="sidemenu-toggle header-link" data-bs-toggle="sidebar">
                            <span class="open-toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                    viewBox="0 0 24 24">
                                    <path d="M24 0v24H0V0h24z" fill="none" opacity=".87" />
                                    <path
                                        d="M18.41 16.59L13.82 12l4.59-4.59L17 6l-6 6 6 6 1.41-1.41zM6 6h2v12H6V6z" />
                                </svg>
                            </span>
                            <span class="close-toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 24 24"
                                    fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path
                                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                                </svg>
                            </span>
                        </a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->


                    <div class="main-header-center  d-none d-lg-block  header-link">
                        <input type="text" class="form-control" id="typehead" placeholder="Search for results..."
                            autocomplete="off">
                        <button class="btn pe-1"><i class="fe fe-search" aria-hidden="true"></i></button>
                        <div id="headersearch" class="header-search">
                            <div class="p-3">
                                <div class="">
                                    <p class="fw-semibold text-muted mb-2 fs-13">Recent Searches</p>
                                    <div class="ps-2">
                                        <a href="javascript:void(0);" class="search-tags"><i
                                                class="fe fe-search me-2"></i>People<span></span></a>
                                        <a href="javascript:void(0);" class="search-tags"><i
                                                class="fe fe-search me-2"></i>Pages<span></span></a>
                                        <a href="javascript:void(0);" class="search-tags"><i
                                                class="fe fe-search me-2"></i>Articles<span></span></a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="fw-semibold text-muted mb-2 fs-13">Apps and pages</p>
                                    <ul class="ps-2">
                                        <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                            <a href="calendar2.html"><span><i
                                                        class="bi bi-calendar me-2 fs-14 bg-primary-transparent avatar rounded-circle "></i>Calendar</span></a>
                                        </li>
                                        <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                            <a href="email-inbox.html"><span><i
                                                        class="bi bi-envelope me-2 fs-14 bg-primary-transparent avatar rounded-circle"></i>Mail</span></a>
                                        </li>
                                        <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                            <a href="buttons.html"><span><i
                                                        class="bi bi-dice-1 me-2 fs-14 bg-primary-transparent avatar rounded-circle "></i>Buttons</span></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-3">
                                    <p class="fw-semibold text-muted mb-2 fs-13">Links</p>
                                    <ul class="ps-2">
                                        <li class="p-1 align-items-center text-muted mb-1 search-app">
                                            <a href="javascript:void(0);"
                                                class="text-primary"><u>http://spruko/html/spruko.com</u></a>
                                        </li>
                                        <li class="p-1 align-items-center text-muted mb-1 search-app">
                                            <a href="javascript:void(0);"
                                                class="text-primary"><u>http://spruko/demo/spruko.com</u></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="py-3 border-top px-0">
                                <div class="text-center">
                                    <a href="javascript:void(0);"
                                        class="text-primary text-decoration-underline fs-15">View all</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- header search -->



                </div>
                <!-- End::header-content-left -->

                <!-- Start::header-content-right -->
                <div class="header-content-right">
                    <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                        aria-controls="navbarSupportedContent-4" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                    </button>
                    <div class="navbar navbar-collapse responsive-navbar p-0">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                            <div class="d-flex align-items-center">
                               

                                

                                <!-- Start::header-element -->
                                <div class="header-element header-search ">
                                    <!-- Start::header-link -->
                                    <a href="javascript:void(0);" class="header-link d-lg-none d-block"
                                        data-bs-toggle="modal" data-bs-target="#searchModal">
                                        <!-- <i class="bi bi-search-alt-2   ps-0"></i> -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon"
                                            viewBox="0 0 24 24" width="24px">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path
                                                d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                                        </svg>
                                    </a>
                                    <!-- End::header-link -->
                                </div>
                                <!-- End::header-element -->

                                
								'.$notificatons_cart
								.$notifications_notifications
								.$notification_apps.'   
                                


                            </div>
                        </div>
                    </div>
                    '.$sidebarswitcher.'

                </div>
                <!-- End::header-content-right -->
            </div>
            <!-- End::main-header-container -->
        </header>
        <!-- /app-header -->
        <!-- Start::app-sidebar -->
		<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="../assets/images/brand/desktop-logo.png" alt="logo" class="desktop-logo">
            <img src="../assets/images/brand/toggle-logo.png" alt="logo" class="toggle-logo">
            <img src="../assets/images/brand/desktop-dark.png" alt="logo" class="desktop-dark">
            <img src="../assets/images/brand/toggle-dark.png" alt="logo" class="toggle-dark">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                '.$this->sidemenu.'
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
                    </path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside><div class="main-content app-content mt-0">';
}else{
	
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
    </div></div>
        ';
        
}
        
        
      return $result;  
        
        
        
        
	}
}
