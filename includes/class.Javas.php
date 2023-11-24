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


$(document).ready(function() {
	$.extend($.expr[\':\'], {
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
    $("input[data-sequential-uploads=\'true\']").fileupload({
		url: \'/nframework/uploadfile.php\',
		    sequentialUploads: true,
		dataType: \'json\',
		progressall: function (e, data) {
			var mid=$(this).attr("id");
	        var progress = parseInt(data.loaded / data.total * 100, 10);		
	        var pg=$("#"+mid+"_progressbar");
	        if (progress==100){
	        	pg.hide();
			}else{
	        	pg.show();
	        	pg.attr("data-value",progress);
	        	//console.log(progress);
	        }        
	    },
	    done:function (e, data) {
	    	var mid=$(this).attr("id");
	    	//console.log(data);
	    	nfFileMakeTable(mid,data.result);
	    	toast("Carga de archivo completa");
	    },
	    fail: function(e, data) {
	    	var o=$(this).attr(\'id\');
	  		alert(\'Fail!\'+o);
		}
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
	 '. implode("\r\n",$nframework->javasonce). $this->js['ready'] . '
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