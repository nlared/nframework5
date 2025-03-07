<?php

class User implements ArrayAccess {
    public $info;
    private $m;
    private $db;
    public $notifications;
    
    function __construct($info) {
        global $m, $config;
        $this->m = $m;
        $this->info=[];
        $find=$info;
        if ($config['sitedb'] != '') {
            $this->db = $config['sitedb']; ///checar usuario no injection
            if (isset($info['_id'])){
            	$info['_id']=tomongoid($info['_id']);
            }
            if (isset($info['password'])){
            	$passwords=[];
               	foreach($config['users']['algos'] as $algo){
	    			$passwords[] = hash($algo, $info['password']);
		    	}
		    	$find['password']=['$in'=>$passwords];
            }
            $info = $this->m->{$config['sitedb']}->users->findOne($find);
            if(!empty($info)){
            	$id=(string)$info->_id;
	            $this->info=mongotoarray($info);
	            $this->info['_id']=$id;
	            if($this->info['activationcode']!=$info['activationcode']){
	            	header('Location: /account/activate.php');
	            	exit();
	            }
            }
            
        }
        $this->notifications=new Notifications();
    }
    
    public function requireAuth(){
    	$_SESSION['nframework']['logiopage']=$_SERVER['DOCUMENT_URI'];
    	if($this->info['username']=='guest'){
    		header ('location: /account/login.php');
    		exit();
    	}
    }
    public function can($verb){
    	return ($this->info['permissions'][$verb]=='on');
    }
    public function in($verb){
    	global $config;
    	$f=$this->m->{$config['sitedb']}->usersgroups ->findOne([
    		'users' => (string)$this->_id,
    		'name'=>$verb
    	]);
    	return (!empty($f));
    }
    
    public function create($info) {
    	global $config;
    	$info['username']=strtolower($info['username']);
        $this->info = $this->m->{$config['sitedb']}->users->findOne(array('username' => $info['username']));
        if($this->info['activationcode']!=''){
        	header('Location: /account/activate.php');
        	exit();
        }
        
        if ($this->info) {
            $this->info['error'] = 'Cuenta ya existe';
        } else {
            $info['password'] = hash('sha512', $info['password']); //hash
            $info['activationcode'] = uniqid();
            $this->m->{$this->db}->users->insertOne($info);
            $this->info =(array) $this->m->{$this->db}->users->findOne($info);
        }
    }

    public function data() {
        return $info;
    }

	public function gravatar($width='',$height=''){
		return '/images/resize/users/32/32/'.$this->info['_id'].'.png';
	}
	

    public function usermenu() {
        global $themecolor,$config;
	
        
        
        $addtheme = ' ' . $themecolor;
        if($this->info['username'] != 'guest' &&$this->info['username']!=''){
        	$bo='<!-- Start::header-element|main-profile-user -->
                                <div class="header-element main-profile-user">
                                    <!-- Start::header-link|dropdown-toggle -->
                                    <a href="javascript:void(0);" class="header-link dropdown-toggle d-flex align-items-center"
                                        id="mainHeaderProfile" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="me-2">
                                            <img src="/images/resize/users/100/100/'.$this->info['_id'].'.png" alt="img" width="30"
                                                height="30" class="rounded-circle">
                                        </span>
                                        <div class="d-xl-block d-none lh-1">
                                            <h6 class="fs-13 font-weight-semibold mb-0">Json Taylor</h6>
                                            <span class="op-8 fs-10">Web Designer 12</span>
                                        </div>
                                    </a>
                                    <!-- End::header-link|dropdown-toggle -->
                                    <ul class="dropdown-menu pt-0 overflow-hidden dropdown-menu-end mt-1"
                                        aria-labelledby="mainHeaderProfile">
                                        <li><a class="dropdown-item" href="/account/profile.php"><i
                                                    class="ti ti-user-circle fs-18 me-2 op-7"></i>Profile</a></li>
                                        <li><a class="dropdown-item" href="/"><i
                                                    class="ti ti-inbox fs-18 me-2 op-7"></i>Home</a></li>
                                        <li><a class="dropdown-item border-block-end" href="blog.html"><i
                                                    class="ti ti-clipboard-check fs-18 me-2 op-7"></i>Posts &
                                                Activities</a></li>
                                        <li><a class="dropdown-item" href="/account/settings.php"><i
                                                    class="ti ti-adjustments-horizontal fs-18 me-2 op-7"></i>Settings
                                                & Privacy</a></li>
                                        <li><a class="dropdown-item border-block-end" href="faq.html"><i
                                                    class="ti ti-help fs-18 me-2 op-7"></i>Help Center</a></li>
                                        <li>
                                            <hr class="dropdown-divider my-0">
                                        </li>
                                        <li><a class="dropdown-item" href="/account/register.php"><i
                                                    class="ti ti-user-plus fs-18 me-2 op-7"></i>Add Another
                                                Account</a></li>
                                        <li><a class="dropdown-item" href="/account/logout.php"><i
                                                    class="ti ti-power fs-18 me-2 op-7"></i>Sign Out</a></li>
                                        <li>
                                            <hr class="dropdown-divider my-0">
                                        </li>
                                        <li class="d-flex justify-content-center p-2">
                                            <span><a class="fs-12 px-2 border-end"
                                                    href="javascript:void(0);">Privacy Policy</a></span>
                                            <span><a class="fs-12 px-2 border-end"
                                                    href="javascript:void(0);">Terms</a></span>
                                            <span><a class="fs-12 px-2"
                                                    href="javascript:void(0);">Cookies</a></span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End::header-element|main-profile-user -->';
          $result='
        		<a href="#" class="app-bar-item">
                        <img src="/images/resize/users/32/32/'.$this->info['_id'].'.png" class="avatar">
                        <span class="ml-2 app-bar-name">'.$this->info['nombres'].'</span>
                    </a>
                    <div class="user-block shadow-1" data-role="collapse" data-collapsed="true">
                        <div class="bg-darkCyan fg-white p-2 text-center">
                            <img src="/images/resize/users/120/120/'.$this->info['_id'].'.png" class="avatar">
                            <div class="h4 mb-0">'.$this->info['nombres'].'</div>
                            <div>'.$this->title.'</div>
                        </div>
                        <div class="bg-white d-flex flex-justify-between flex-equal-items p-2">
                            <a href="/account/myprofile.php" class="button flat-button fg-black">
                            	<span class="mif-profile icon"></span>&nbsp;Perfil</a>
                            <a href="/account/cpassword.php" class="button flat-button fg-black">
                            	<span class="mif-key"></span>&nbspContraseña</a>
                            
                        </div>
                        <div class="bg-white d-flex flex-justify-between flex-equal-items p-2 bg-light">
                            <a href="#" class="button fg-black mr-1">
                            	<span class="mif-bug"></span>&nbsp;Reportar un problema</a>
                            <a href="/account/logout.php" class="button fg-black">
                            	<span class="mif-exit"></span>&nbsp;Salir</a>
                        </div>
                    </div>
         ';
            
        }else{        
        $result='<a href="#" class="app-bar-item">
        <span class="mif-enter icon"></span><span class="visible-md">&nbsp;Iniciar</span></a>
        <ul class="d-menu context place-right" data-role="dropdown" id="logindrop" data-no-close="true">
			<div class="p-3 bg-white fg-black" style="width:300px">
                <form method="POST" data-role="validator" action="/account/login.php">
                	<input type="hidden" name="CSRFToken" value="'. csrfToken('/account/login.php').'">
                    <h4 class="text-light">Iniciar sesión...</h4>
                    
                    <div class="frm-group">
                    	<label>Usuario</label>
                        <input name="login[username]" data-role="input" data-prepend="<span class=\'mif-user\'></span>"  
                        type="text" data-validate="required">
                    </div>
                    <div class="frm-group">
                    	<label>Contraseña</label>
                        <input name="login[password]" data-role="input" data-prepend="<span class=\'mif-lock\'></span>" 
                        type="password" data-validate="required">
                    </div>
                    <label class="input-control checkbox small-check">
                        <input name="login[remember]" type="checkbox">
                        <span class="check"></span>
                        <span class="caption">Recordar me</span>
                    </label>
                   
                   <button class="button mini js-push-btn"></button><br>
                   <button class="button" onclick="Metro.getPlugin(\'#logindrop\',\'dropdown\').close();">Cerrar</button>'.
                   ($config['canregister']?
                   '<button href="/account/new.php" class="button">Registrate</button>'
                   :
                   	''
                   ).
                   '<button name="op" value="Iniciar" class="button" type="submit">Iniciar</button>
                </form>
            </div>
		</ul>'; 
        }       
        
        return $result;
    }

    public function __isset($name) {
        return isset($this->info[$name]);
    }

    public function __set($name, $value) {
        switch ($name) {
            case 'username':
            case '_id':
                return true;
                break;
            default:
                if ($this->info[$name] != $value) {
                    $this->info[$name] = $value;
                    $this->m->{$this->db}->users->updateOne(
                    	['_id'=>$this->info['_id']],
                    	['$set'=>[$name=>$value]]
                    );
                }
        }
    }

    public function __unset($name) {
        switch ($name) {
            case 'username':
            case '_id':
                return true;
                break;
            default:
                unset($this->info[$name]);
                $this->m->{$this->db}->users->updateOne(
                	['_id'=>$this->info['_id']],
                	['$unset'=>[$name=>'']]);
        }
    }

    public function __get($name) {
    	$result=null;
        switch ($name) {
            case 'fullname':
                $result= $this->info['nombres'] . ' ' .
                $this->info['primerap'].' '.
                $this->info['segundoap'];
                break;
            case '':
                $result= false;
                break;
            case '_id':
                $result= (string)  $this->info['_id'];
                break;                
            default:
               // if ($this->info)) {
               if (array_key_exists($name, $this->info)) {
               //     if (property_exists( $this->info,$name)) {
                        $result=$this->info[$name];
                 //   }
               }
        }
        return $result;
    }
    public function __debugInfo(){
    	return [
    		'db'=>$this->db,
    		'info'=>$this->info
    		];
    }
    public function offsetSet(mixed $name, mixed $value) : void{
     switch ($name) {
            case 'username':
            case '_id':
               break;
            default:
                if ($this->info[$name] != $value) {
                    $this->info[$name] = $value;
                    $this->m->{$this->db}->users->updateOne(
                    	['_id'=>$this->info['_id']],
                    	['$set'=>[$name=>$value]]
                    );
                }
        }
    }
    
     public function offsetExists(mixed $name):bool {
      return isset($this->info[$name]);
    }

    public function offsetUnset(mixed $name):void {
        switch ($name) {
            case 'username':
            case '_id':
                break;
            default:
                unset($this->info[$name]);
                $this->m->{$this->db}->users->updateOne(
                	['_id'=>$this->info['_id']],
                	['$unset'=>[$name=>'']]);
        }
    }

    public function offsetGet(mixed $name): mixed {
        $result=null;
        switch ($name) {
            case 'fullname':
                $result= $this->info['nombres'] . ' ' .
                $this->info['primerap'].' '.
                $this->info['segundoap'];
                break;
            case '':
                $result= false;
                break;
            case '_id':
                $result= (string)  $this->info['_id'];
                break;                
            default:
               // if ($this->info)) {
               if (array_key_exists($name, $this->info)) {
               //     if (property_exists( $this->info,$name)) {
                        $result=$this->info[$name];
                 //   }
               }
        }
        return $result;
    }
    
}