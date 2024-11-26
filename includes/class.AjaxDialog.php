<?
class AjaxDialog{
	public $url;
	public $id;
    public function __construct($options=[]){
    	global $nframework;
		foreach ($options as $option => $value) {
    		$this->{$option}=$value;	        
        }
        if(empty($this->id)){
        	$this->id='AjaxDialog_'.$nframework->counters('ajaxdialog');
        }
    }
	
	 public function __toString(){
	 	global $javas;
	 	
	 	 $javas->addjs('
	 		function ajaxdialogload(el){
	 			$.ajax({
	 				url: \''.$this->url.'\',
	 				cache: false
	 			}).done(function(html){
	 				var content=html; 
	 				Metro.dialog.create({
			            title: "Use Windows location service?",
			            content: content,
			            actions: [
			                {
			                    caption: "Agree",
			                    cls: "js-dialog-close alert",
			                    onclick: function(){
			                        alert("You clicked Agree action");
			                    }
			                },
			                {
			                    caption: "Disagree",
			                    cls: "js-dialog-close",
			                    onclick: function(){
			                        alert("You clicked Disagree action");
			                    }
			                }
			            ]
			        });
	 			});
	 		}
	 	');
	 	
	 	return '<button class="button info" url="'.$this->url.'" onclick="ajaxdialogload">Open dialog</button>';
	 	
	 }
	 
}