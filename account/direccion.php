<?
$lat=($dataset->lat==''?'25.43228030':$dataset->lat);
$lng=($dataset->lng==''?'-101.00447970':$dataset->lng);

	$nframework->usecommon=true;
	$javas->addjs('
	var geocoder;
	function initMap() {
		geocoder = new google.maps.Geocoder();
	}
	
	');
	$javas->addjs('llamarApiDenueBus();initMap()', 'ready'); 
	$nframework->jss['200']='https://maps.googleapis.com/maps/api/js?key='.$config['google-maps-api'].'&libraries=places';
?>
	<div class="bg-cyan fg-white p-3"><h4>Dirección</h4></div>
	<div class="bg-white p-3">
		<div class="grid">
			<div class="row">
				<div class="cell">
					<div class="row">
						<div class="cell-md-6"><?=$cp?></div>
						
					</div>
					<div class="row">
						<div class="cell-md-6"><?=$estado?></div>
						<div class="cell-md-6"><?=$municipio?></div>
					</div>
					<div class="row">
						<div class="cell-md-6"><?=$localidad?></div>
						<div class="cell-md-6"><?=$asentamiento?></div>
					</div>
					
					<div class="row">
						<div class="cell-md-6"><?=$vialidad?></div>
						<div class="cell-md-3"><?=$noext?></div>
						<div class="cell-md-3"><?=$noint?></div>
					</div>
					<div class="row">
						<div class="cell"><button class="button" id="txttomap">Buscar direccion en el mapa</button></div>
						
					</div>
				</div>
				<div class="cell"><?=$mapa?></div>
			</div>
		</div>
	</div>




<script>
var estadosn=[];
var estadosc=[];
var estadoi;
var municipiosn=[];
var municipiosc=[];
var municipioi;
var localidadesn=[];
var localidadesc=[];
var localidadi;
var asentamientosn=[];
var asentamientosc=[];
var asentamientoi;


function llamarApiDenueBus(){
	$.ajax({
        type: 'GET',
        url: 'https://gaia.inegi.org.mx/wscatgeo/mgee/',
        cache: false,
        async: false,
        dataType: "json",
        success: function (data) {
        	var input= $('#data_estado').data('input');
        	estadosn=[];
        	estadosc=[];
            for (var i = 0; i < data.datos.length; i++) {
            	estadosn.push(data.datos[i].nom_agee);
            	estadosc.push(data.datos[i].cve_agee);
            }
            input.autocomplete=estadosn;
			val=$('#data_estado').val();
            console.log(val);
            load_mun(val,false);
            
        }, complete: function (xhr, status) {
           //dialogLoading.close();
        }
    });
}


function load_mun(val,clear){
	estadoi=$.inArray(val,estadosn);
 	if (estadoi!=-1){
 		//console.log(val,index,estadosc[index]);
 		$.ajax({
	        type: 'GET',
	        url: 'https://gaia.inegi.org.mx/wscatgeo/mgem/'+estadosc[estadoi],
	        cache: false,
	        async: false,
	        dataType: "json",
	        success: function (data) {
	        	var input= $('#data_municipio').data('input');
	        	municipiosn=[];
	        	municipiosc=[];
	            for (var i = 0; i < data.datos.length; i++) {
	            	municipiosn.push(data.datos[i].nom_agem);
	            	municipiosc.push(data.datos[i].cve_agem);
	            }
	            input.autocomplete=municipiosn;
	            if(clear){
	            	$('#data_municipio').val('');
	            }else{
	            	val=$('#data_municipio').val();
	            	load_loc(val,false);
	            }
	        }, complete: function (xhr, status) {
	            $('#spinner').hide();
	        }
	    });
 	}
}


 
$('#data_estado').change(function(){
 	var val=$(this).val();
 	load_mun(val,true);
 });
 
 function load_loc(val,clear){
 	municipioi=$.inArray(val,municipiosn);
 	if (municipioi!=-1){
 		//console.log(val,index,estadosc[index]);
 		$.ajax({
	        type: 'GET',
	        url: 'https://gaia.inegi.org.mx/wscatgeo/localidades/'+estadosc[estadoi]+municipiosc[municipioi],
	        cache: false,
	        async: false,
	        dataType: "json",
	        success: function (data) {
	        	var input= $('#data_localidad').data('input');
	        	localidadesn=[];
	        	localidadesc=[];
	            for (var i = 0; i < data.datos.length; i++) {
	            	localidadesn.push(data.datos[i].nom_loc);
	            	localidadesc.push(data.datos[i].cve_loc);
	            }
	            input.autocomplete=localidadesn;
	            if(clear){
	            	$('#data_localidad').val('');
	            }else{
	            	val=$('#data_localidad').val();
	            	load_asent(val,false);
	            }
	        }, complete: function (xhr, status) {
	            $('#spinner').hide();
	        }
	    });
 	}
 }
 
  
 $('#data_municipio').change(function(){
 	var val=$(this).val();
 	load_loc(val,true);
 });
 
 
 function load_asent(val,clear){
 	localidadi=$.inArray(val,localidadesn);
 	if (localidadi!=-1){
 		//console.log(val,index,estadosc[index]);
 		$.ajax({
	        type: 'GET',
	        url: 'https://gaia.inegi.org.mx/wscatgeo/asentamientos/'+estadosc[estadoi]+municipiosc[municipioi]+localidadesc[localidadi],
	        cache: false,
	        async: false,
	        dataType: "json",
	        success: function (data) {
	        	var input= $('#data_asentamiento').data('input');
	        	asentamientosn=[];
	        	asentamientosc=[];
	            for (var i = 0; i < data.datos.length; i++) {
	            	//data.datos[i].tipo_asen+' '
	            	asentamientosn.push(data.datos[i].nom_asen);
	            	asentamientosc.push(data.datos[i].cve_asen);
	            }
	            input.autocomplete=asentamientosn;
	            if(clear){
	            	$('#data_asentamiento').val('');
	            }
	        }, complete: function (xhr, status) {
	            $('#spinner').hide();
	        }
	    });
	    $.ajax({
	        type: 'GET',
	        url: 'https://gaia.inegi.org.mx/wscatgeo/vialidades/'+estadosc[estadoi]+municipiosc[municipioi]+localidadesc[localidadi],
	        cache: false,
	        async: false,
	        dataType: "json",
	        success: function (data) {
	        	var input= $('#data_vialidad').data('input');
	        	viasn=[];
	        	viasc=[];
	            for (var i = 0; i < data.datos.length; i++) {
	            	if(data.datos[i].nom_via!==''){
		            	if($.inArray(data.datos[i].nom_via,viasn)==-1){
		            		viasn.push(data.datos[i].tipovial+' '+data.datos[i].nomvial);
		            		viasc.push(data.datos[i].cve_via);
		            	}
	            	}
	            }
	            console.log(viasn);
	            input.autocomplete=viasn;
	            if(clear){
	            	$('#data_vialidad').val('');
	            }
	        }, complete: function (xhr, status) {
	            $('#spinner').hide();
	        }
	    });
	    
 	}
 }
 
 
 $('#data_localidad').change(function(){
 	var val=$(this).val();
 	load_asent(val,true);
 
 });
 
 $('#txttomap').click(function(){
 	geocodeAddress();
 });
 
 
 /*
 $('#data_asentamiento').change(function(){
 	var val=$(this).val();
 	asentamientoi=$.inArray(val,asentamientosn);
 	if (asentamientoi!=-1){
 		//console.log(val,index,estadosc[index]);
 		$.ajax({
	        type: 'GET',
	        url: 'https://gaia.inegi.org.mx/wscatgeo/asentamientos/'+estadosc[estadoi]+municipiosc[municipioi]+localidadesc[localidadi],
	        cache: false,
	        async: false,
	        dataType: "json",
	        success: function (data) {
	        	var input= $('#data_vialidad').data('input');
	        	vialidadsn=[];
	        	vialidadsc=[];
	            for (var i = 0; i < data.datos.length; i++) {
	            	vialidadsn.push(data.datos[i].nom_asen);
	            	vialidadsc.push(data.datos[i].cve_asen);
	            }
	            input.autocomplete=vialidadsn;
	            
	        }, complete: function (xhr, status) {
	            $('#spinner').hide();
	        }
	    });
 	}
 });*/





function buscard(){
	var lat=$("#data_mapa_lat").val();
	var lng=$("#data_mapa_lng").val();
	
	
	const latlng = {
    lat: parseFloat(lat),
    lng: parseFloat(lng),
  };
 dialogLoading.showModal();
  geocoder
    .geocode({ location: latlng })
    .then((response) => {
      if (response.results[0]) {
		response.results[0].address_components.forEach((element) => {
    		element.types.forEach((type)=>{
    			let tipos = { route: "data_vialidad", sublocality: 'data_asentamiento' ,locality:'data_localidad',administrative_area_level_1:'data_estado',postal_code:'data_cp'};
    			console.log(type);
    			console.log(element);
    			if (Object.hasOwn(tipos, type)){
    				console.log(type+' '+element.long_name);
    				$('#'+tipos[type]).val(element.long_name);
    			}
    		},element);
		});
      } else {
        console.log("No results found");
      }
       dialogLoading.close();
    })
    .catch((e) => {console.log("Geocoder failed due to: " + e); dialogLoading.close();});
    
 }
 
 
 
 function geocodeAddress() {
 dialogLoading.showModal();
  var direccion=$("#data_vialidad").val()+' '+$("#data_noext").val() +' '+$("#data_asentamiento").val()+' '+$("#data_localidad").val()+' '+$("#data_municipio").val();
  console.log(direccion);
  geocoder
    .geocode({
      address: direccion,
      componentRestrictions: {
        country: "MX",
        postalCode: $('#data_cp').val(),
      },
    })
    .then(({ results }) => {
	    if( results[0].geometry.location!==undefined){
	    	var lat=results[0].geometry.location.lat();
	    	var lng=results[0].geometry.location.lng()
			$("#data_mapa_lat").val(lat);
			$("#data_mapa_lng").val(lng);
			var newLatLng = new L.LatLng(lat, lng);
			mapsmarker['data_mapa_mapmarker'].setLatLng(newLatLng);
			maps['data_mapa_map'].flyTo(newLatLng);
    	}else{
    		
    	}
    	dialogLoading.close();
    })
    .catch((e) =>{
      window.alert("Geocode was not successful for the following reason: " + e); dialogLoading.close();
    	
    });
}
 
 function loadselect(id,url,func){
 		$.ajax({
        type: 'GET',
        url: url,
        cache: false,
        async: false,
        dataType: "json",
        success: function (data) {
        	var sel=Metro.getPlugin(id,'select');
        	var datas='<option value="">Seleccione..</option>';
            for (var i = 0; i < data.datos.length; i++) {
            	datas+=func(data.datos[i]);
            }
            sel.data(datas);
        }, error: function (objeto, tipo, causa) {
            if (objeto.status == "404" || objeto.status == "200") {
                var func = function () {
                    window.location.href = "inicioweb.jsp";
                };
                $.avisoMsg("La sesión ha caducado", func);
            } else {
                alert(tipo + "  " + causa + "\nStatusfw:" + objeto.status);
            }
        }, complete: function (xhr, status) {
            $('#spinner').hide();
        }
    });
 }

</script>