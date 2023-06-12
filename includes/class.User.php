<?php

class User implements ArrayAccess {
    public $info;
    private $m;
    private $db;
    function __construct($info) {
        global $m, $config;
        //$this->info=array('username'=>'guest');
        //print_r($info);
        $this->m = $m;
        $this->info=[];
        if ($config['sitedb'] != '') {
            $this->db = $config['sitedb']; ///checar usuario no injection
            if (!isset($_SESSION['user'])) {
                if (isset($info['password'])){
                	$info['password'] = hash('sha512', $info['password']);
                }
                
                filter_var($info['username'], FILTER_VALIDATE_EMAIL);
                $this->info = (array)$this->m->{$config['sitedb']}->users->findOne($info);
                
                //TODO:Aguas
                if($this->info['activationcode']!=$info['activationcode']){
                	header('Location: /account/activate.php');
                	exit();
                }
                
            }else {
                $this->info =(array) $this->m->{$config['sitedb']}->users->findOne(['username' => $_SESSION['user']]);
            }
        }
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
		$_SESSION['images']['avatar'.$width.'x'.$height]=[
			'src'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
			'dst'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/mini/',
			'width'=>$width,
		];
		return '/nframework/imagen.php?id=usermini&file='.$this->info['_id'].'.png';
	}
	

    public function usermenu() {
        global $themecolor,$config;
		$_SESSION['images']['usermini']=[
			'src'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
			'dst'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/mini/',
			'width'=>'32',
			
		];
        
        
        $addtheme = ' ' . $themecolor;
        if($this->info['username'] != 'guest' &&$this->info['username']!=''){
          $result='
        	<a href="#" class="app-bar-item">
                        <img src="/nframework/imagen.php?id=usermini&file='.$this->info['_id'].'.png" class="avatar">
                        <span class="ml-2 app-bar-name">'.$this->info['nombres'].'</span>
                    </a>
                    <div class="user-block shadow-1" data-role="collapse" data-collapsed="true">
                        <div class="bg-darkCyan fg-white p-2 text-center">
                            <img src="/nframework/imagen.php?id=usermini&file='.$this->info['_id'].'.png" class="avatar">
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
    public function offsetSet($name, $value) {
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
    
     public function offsetExists($name) {
      return isset($this->info[$name]);
    }

    public function offsetUnset($name) {
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

    public function offsetGet($name) {
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