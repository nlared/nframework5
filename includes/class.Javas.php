<?

class Javas {
    public array $js;//=['general'=>'','resize'=>'','ready'=>''];
    public bool $flushed=false;
	public array $docend=[];
    function __construct() {
        $this->js = ['general'=>'','resize'=>'','ready'=>'','scroll'=>''];
        //$this->docend=[];
    }


    function addjs($jss, $seccion = 'general') {
        $this->js[$seccion].=$jss;
    }

    function __toString():string {
    	global $nframework,$csrftoken;
        if (!$this->flushed) {
            $this->flushed = true;
            
            
            
  /*$("input[data-custom-buttons=\'customCalendarButton\']").datetimepicker({
		format:\'Y-m-d H:i\',mask:false,lang:\'es\'
	});*/
            
            
    		$js='
    		
    		var nbacklink="/";
'. $this->js['general'] . '
var datatables=[];    
var ajaxdialogs=[];

function nfWindowResize() {
'.$this->js['resize'].'
};
var nfWindowResizeTimer;
$(window).resize(function() {
    clearTimeout(nfWindowResizeTimer);
    nfWindowResizeTimer = setTimeout(nfWindowResize, 100);
});

function syscalls() {
    $.ajax({
      url: "/nframework/kernel.php", // URL to send the request to
      type: "GET", // Type of request (GET or POST)
      success: function(result){
	      console.log(result);
    	if (result.hasOwnProperty("pids")){
        	console.log("pids");
        	$(".bg_process").each(function() {
				var pid=$(this).attr("id").substring(10);
				if (!result.pids.hasOwnProperty(pid)){
					var icon = $(this).find("span");
					icon.removeClass("mif-stop");
					icon.addClass("mif-play");
				}
			});	
    	}else{
			$(".bg_process").each(function() {
				var icon = $(this).find("span");
				icon.removeClass("mif-stop");
				icon.addClass("mif-play");
			});
    	}
      },
      error: function(xhr, status, error){
        console.error("Error: " + error); // Handle any errors
      }
    });
}
setInterval(syscalls, 10000);


$(window).scroll(function(){
'.$this->js['scroll'].'
});


function speak(text,callback){
  	if (\'speechSynthesis\' in window) {
	  	var u = new SpeechSynthesisUtterance();
	    u.text = text;
	    u.lang = \'es-MX\';
	    u.onend = function () {
	        if (callback) {
	            callback();
	        }
	    };
	    u.onerror = function (e) {
	        if (callback) {
	            callback(e);
	        }
	    };
	    speechSynthesis.speak(u);
  	} else {
    	console.log("Oops! Your browser does not support HTML SpeechSynthesis.")
  	}
}
const dialogLoading = document.querySelector("#dialogLoading");
//const showButton = document.querySelector("dialog + button");

$("#dialogCancel").on("click", function(){
  dialogLoading.close();
});

$(document).ready(function() {


	$.extend(jQuery.expr.pseudos, {
	  \'containsi\': function(elem, i, match, array)
	  {
	    return (elem.textContent || elem.innerText || \'\').toLowerCase()
	    .indexOf((match[3] || "").toLowerCase()) >= 0;
	  }
	});
    window.addEventListener("keyup", function(e){
    	if(e.keyCode == 27)
    	window.location.href=nbacklink;
    }, false);
    
    $("input[data-role=\'spiner\']").spinner();
    $("div[data-role-aux=\'file-progress-bar\']").hide();
    $("input[data-sequential-uploads=\'true\']").each(function( index ) {
		var mid=$(this).attr("id");
	    $.ajax({
			url: \'/nframework/uploadfile.php\',
			method:"POST",
			data: "mid="+mid, 
			dataType: \'json\',
			success: function(data) {
				nfFileMakeTable(mid, data);
				
			}
		});
    });
    $("input[uppercase=\'true\']").each(function(index){
      this.addEventListener("keypress", forceKeyPressUppercase, false);
    });
     $("input[lowercase=\'true\']").each(function(index){
      this.addEventListener("keypress", forceKeyPressLowercase, false);
    });
  
	
	$(\'.nfinfoicon\').click(function() {
	 var content=$(this).attr(\'content\');
	  Metro.infobox.create(content);
	});
	
	$(".ajaxform").submit(function(e) {
	    var form=$(this);
	    var url = form.attr( "action" );; // the script where you handle the form input.
	    var f=form.attr("data-on-success");
	    if (f === undefined || f === null) {
	    	f="nAjaxFormDone"
	    }
	    $.ajax({
			type: "post",
			url: url,
			beforeSend: function(xhr) { 
    		  xhr.setRequestHeader("X-CSRF-Token", "'.$csrftoken.'"); 
			}, 
			data: form.serialize(), // serializes the forms elements.
			success: function(data){
			   	Metro.utils.callback(f,[data]);
			},
			error:function(jqXHR, textStatus) {
				  alert( "Request failed: " + textStatus );
			}
		});
		
	    e.preventDefault(); // avoid to execute the actual submit of the form.
	});
	$(".ajaxform2").submit(function(e) {
	    var form=$(this);
	    var url = form.attr( "action" );; // the script where you handle the form input.
	    var f=form.attr("data-on-success");
	    if (f === undefined || f === null) {
	    	f="nAjaxFormDone"
	    }
	    $.ajax({
			type: "post",
			url: url,
			data: form.serialize(), // serializes the forms elements.
			success: function(data){
			   	Metro.utils.callback(f,[data]);
			},
			error:function(jqXHR, textStatus) {
				  //alert( "Request failed: " + textStatus );
				  toast("Datos Guardados.");
			}
		});
		
	    e.preventDefault(); // avoid to execute the actual submit of the form.
	});
	$(".secureop").click(function() {
		var op = $(this).closest("form").find("input[name=\"op\"]");
		op.val($(this).val());
	});
	 '. implode("\r\n",$nframework->javasonce). $this->js['initializecomponent'] . '
	 '. $this->js['ready'] . '
	 nfWindowResize();
});
';

/*

jQuery.datetimepicker.setLocale(\''.$nframework->langshort.'\');
   $(\'.datetimepicker2date\').datetimepicker({
  timepicker:false,
  format:\'Y-m-d\',
   i18n:{
  '.$nframework->langshort.':{
   months:'.json_encode($nframework->languages[$nframework->lang]['calendar']['months']).',
   dayOfWeek:'.json_encode($nframework->languages[$nframework->lang]['calendar']['days']).'
  }
 },
});
    $(".ui-spinner").addClass("w-100");
   
});

*/

//$packer = new Tholu\Packer\Packer($js, 'Normal', true, false, true);
//$packed_js = $packer->pack();
return implode("\r\n",array_reverse($this->docend)).'
<script>
'.$js.' 
</script>';
        }else{
            return '';
        }
    }
}