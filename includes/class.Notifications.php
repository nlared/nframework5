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
		global $m,$config;
		foreach($m->{$config["sitedb"]}->notifications->find(['to'=>(string)$user->_id]) as $d){
			$count++;
		}
		$this->count=$count;
	}
	
	public function icon():string
	{
		return '<a href="#" class="app-bar-item " id="nf-notifications">
		<span class="mif-bell"></span>
		<span class="badge fg-white mt-2 mr-1">'.$this->count.'</span>
		</a>
		<div class="user-block shadow-1 " data-role="collapse" data-collapsed="true">
	        	<ul data-role="listview" data-view="content" data-select-node="true">
				    <li data-icon="<span class=\'mif-folder fg-orange\'>"
				        data-caption="Video"
				        data-content="<div class=\'mt-1\' data-role=\'progress\' data-value=\35\' data-small=\'true\'>"></li>
				    <li data-icon="<span class=\'mif-folder fg-cyan\'>"
				        data-caption="Images"
				        data-content="<div class=\'mt-1\' data-role=\'progress\' data-value=\'78\' data-small=\'true\'>"></li>
				   
				</ul>
		</div>
    ';
	}
}