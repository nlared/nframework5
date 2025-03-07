const webcamElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const snapSoundElement = document.getElementById('snapSound');
const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
const videoSourceElement = document.getElementById('videoSource');
let currentDeviceId = '';

navigator.mediaDevices.enumerateDevices()
.then(devices => {
    devices.forEach(device => {
        if (device.kind === 'videoinput') {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.text = device.label || `Camera ${videoSourceElement.length + 1}`;
            videoSourceElement.appendChild(option);
        }
    });
})
.catch(err => {
    console.log(err.name + ": " + err.message);
});
videoSourceElement.onchange = () => {
    currentDeviceId = videoSourceElement.value;
    webcam.stop();
    startWebcam(currentDeviceId);
};

function startWebcam(deviceId) {
    const constraints = {
        video: {
            deviceId: deviceId ? { exact: deviceId } : undefined
        }
    };
    console.log(constraints);
    webcam.start(constraints)
    .then(result => {
        console.log("webcam started with device: ", deviceId);
    })
    .catch(err => {
        console.log(err);
    });
}

//startWebcam(currentDeviceId);

$("#webcam-switch").change(function () {
    if(this.checked){
        $('.md-modal').addClass('md-show');
        //webcam.start()
        const constraints = {
	        video: {
	            deviceId: currentDeviceId ? { exact: currentDeviceId } : undefined
	        }
	    };
	    console.log(constraints);
	    webcam.start(constraints)
            .then(result =>{
               cameraStarted();
               console.log("webcam started");
            })
            .catch(err => {
                displayError();
            });
    }else{
        cameraStopped();
        webcam.stop();
        console.log("webcam stopped");
    }   
});

$('#cameraFlip').click(function() {
    webcam.flip();
    webcam.start();  
});

$('#closeError').click(function() {
    $("#webcam-switch").prop('checked', false).change();
});

function displayError(err = ''){
    if(err!=''){
        $("#errorMsg").html(err);
    }
    $("#errorMsg").removeClass("d-none");
}

function cameraStarted(){
    $("#errorMsg").addClass("d-none");
    $('.flash').hide();
    $("#webcam-caption").html("on");
    $("#webcam-control").removeClass("webcam-off");
    $("#webcam-control").addClass("webcam-on");
    $(".webcam-container").removeClass("d-none");
    if( webcam.webcamList.length > 1){
        $("#cameraFlip").removeClass('d-none');
    }
    $("#wpfront-scroll-top-container").addClass("d-none");
    window.scrollTo(0, 0); 
    $('body').css('overflow-y','hidden');
}

function cameraStopped(){
    $("#errorMsg").addClass("d-none");
    $("#wpfront-scroll-top-container").removeClass("d-none");
    $("#webcam-control").removeClass("webcam-on");
    $("#webcam-control").addClass("webcam-off");
    $("#cameraFlip").addClass('d-none');
    $(".webcam-container").addClass("d-none");
    $("#webcam-caption").html("Click to Start Camera");
    $('.md-modal').removeClass('md-show');
}

var picture;
$("#take-photo").click(function () {
    beforeTakePhoto();
    picture = webcam.snap();
    //document.querySelector('#upload-photo').href = picture;
    afterTakePhoto();
});


$('#upload-photo').click(function() {
	$.ajax({
	    // En data puedes utilizar un objeto JSON, un array o un query string
	    data: {"photo" : picture},
	    //Cambiar a type: POST si necesario
	    type: "POST",
	    // Formato de datos que se espera en la respuesta
	    
	    // URL a la que se enviará la solicitud Ajax
	    url: "/nframework/webcamup.php",
	})
	 .done(function( data, textStatus, jqXHR ) {
	         alert("La solicitud se ha completado correctamente." );
	     
	 })
	 .fail(function( jqXHR, textStatus, errorThrown ) {
	         alert( "La solicitud a fallado: " +  textStatus);
	});
});

function beforeTakePhoto(){
    $('.flash')
        .show() 
        .animate({opacity: 0.3}, 500) 
        .fadeOut(500)
        .css({'opacity': 0.7});
    window.scrollTo(0, 0); 
    $('#webcam-control').addClass('d-none');
    $('#cameraControls').addClass('d-none');
}

function afterTakePhoto(){
    webcam.stop();
    $('#canvas').removeClass('d-none');
    $('#take-photo').addClass('d-none');
    $('#exit-app').removeClass('d-none');
    $('#upload-photo').removeClass('d-none');
    $('#resume-camera').removeClass('d-none');
    $('#cameraControls').removeClass('d-none');
}

function removeCapture(){
    $('#canvas').addClass('d-none');
    $('#webcam-control').removeClass('d-none');
    $('#cameraControls').removeClass('d-none');
    $('#take-photo').removeClass('d-none');
    $('#exit-app').addClass('d-none');
    $('#upload-photo').addClass('d-none');
    $('#resume-camera').addClass('d-none');
}

$("#resume-camera").click(function () {
    webcam.stream()
        .then(facingMode =>{
            removeCapture();
        });
});

$("#exit-app").click(function () {
    removeCapture();
    $("#webcam-switch").prop("checked", false).change();
});