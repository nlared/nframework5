<?
class offcanvas{
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
	global $javas,$user,$nframework;
return '
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasLabel">'.$this->title.'</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    Content for the offcanvas goes here. You can place just about any Bootstrap component or custom elements here.
  </div>
</div>
';
	
	}
}