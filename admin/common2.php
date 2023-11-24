<?

require 'include.php';
$nframework->usecommon=true;
if(!$user->in('admins')){
	header('Location: /');
	exit();
}//*/


$sidemenu.='
	<li class="slide">
		<a href="/" class="side-menu__item">
	        <span class="icon"><span class="mif-home"></span></span>
	        <span class="caption">Home</span>
	    </a>
    </li>
    <li class="slide">
    <a href="/admin/pages"  class="side-menu__item">
        <span class="icon"><span class="mif-document-file-html"></span></span>
        <span class="caption">Pages</span>
    </a></li>
    <li class="slide"><a href="/admin/menus" >
        <span class="icon"><span class="mif-document-file-html"></span></span>
        <span class="caption">Menus</span>
    </a></li>
    <li class="slide"><a href="/admin/site" >
        <span class="icon"><span class="mif-cog"></span></span>
        <span class="caption">Site</span>
    </a></li>
    <li class="slide"><a href="/admin/theme/tconfig.php" >
        <span class="icon"><span class="mif-document-file-html"></span></span>
        <span class="caption">Theme</span>
    </a></li>
    
    <li class="slide"><a href="/admin/users" >
        <span class="icon"><span class="mif-user"></span></span>
        <span class="caption">Users</span>
    </a></li>
    <li class="slide"><a href="/admin/usersgroups" >
        <span class="icon"><span class="mif-users"></span></span>
        <span class="caption">Groups</span>
    </a></li>
    <li class="slide"><a href="/admin/errorlog" >
        <span class="icon"><span class="mif-bug"></span></span>
        <span class="caption">Error log</span>
    </a></li>
	';





$sidebar=new Sidebar([
//'color'=> '',
'darkcolor'=> 'black',
'focuscolor'=> 'grayMouse',
'title'=>'Admin',
'sidemenu'=>$sidemenu]);
echo $sidebar;