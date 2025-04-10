<?
class NotificationAction{
    
}

class Notification{
    public $dir = "auto";//NotificationAction
    public String $lang = "";
    public String $body = "";
    public String $tag = "";
    public String $image;
    public String $icon;
    public String $badge;
    public $vibrate;
    public $timestamp;
    public bool $renotify ;
    public bool $silent;
    public bool $requireInteraction ;
    public $data;
    public $actions ;
}

class Notifications{
	public $count=0;
	public function __construct(){
		
		
	}
	
	public function icon():string
	{
		global $m,$config;
		$count=0;
		foreach($m->{$config["sitedb"]}->notifications->find(['to'=>(string)$user->_id]) as $d){
			$nots.=<<<DATA
			<li data-icon="<span class='mif-folder fg-orange'>"
			        data-caption="$d->caption"
			        data-content="$d->content">
		    </li>
DATA;
			$count++;
		}
		
		return '
		<a href="#" class="app-bar-item " id="nf-notifications">
			<span class="mif-bell"></span>
			'.($count>0?'<span class="badge fg-white mt-2 mr-1">{$count}</span>':'').'
		</a>
		<div class="user-block shadow-1 " data-role="collapse" data-collapsed="true">
        	'.$nots.'
		</div>';

	}
}