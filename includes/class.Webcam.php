<?
class Webcam{
	public String $path;
	public String $exitpath='/';
	function __toString():string{
		global $nframework,$javas;
		$_SESSION['nf5photo']=[
			'path'=>$this->path
		];
		$nframework->jss['101']='https://unpkg.com/webcam-easy/dist/webcam-easy.min.js';
		$nframework->jss['102']='/nframework/webcam.js?'.date('mdis');
		$javas->addjs('

		
		');
		$nframework->csss['102']='/nframework/webcam.css';
		return '<main id="webcam-app">
	<div class="form-control webcam-start webcam-on" id="webcam-control">
			<label class="form-switch">
			<input type="checkbox" id="webcam-switch">
			<i></i> 
			<span id="webcam-caption">on</span>
			</label>      
			<button id="cameraFlip" class="button large rounded dark d-none"><span class="mif-loop"></span></button>
			<select id="videoSource"></select>
	</div>
	
	<div id="errorMsg" class="col-12 col-md-6 alert-danger d-none">
		Fail to start camera, please allow permision to access camera. <br>
		If you are browsing through social media built in browsers, you would need to open the page in Sarafi (iPhone)/ Chrome (Android)
		<button id="closeError" class="btn btn-primary ml-3">OK</button>
	</div>
	<div class="md-modal md-effect-12 md-show">
		<div id="app-panel" class="app-panel md-content row p-0 m-0">     
			<div id="webcam-container" class="webcam-container col-12 p-0 m-0">
				<video id="webcam" autoplay="" playsinline="" width="640" height="480" style="transform: scale(-1, 1);"></video>
				<canvas id="canvas" class="d-none" height="754" width="1006"></canvas>
				<div class="flash" style="opacity: 0.3; display: none;"></div>
				<audio id="snapSound" src="/nframework/snap.wav" preload="auto"></audio>
			</div>
			<div id="cameraControls" class="cameraControls">
				<a href="'.$this->exitpath.'" id="exit-app" title="Salir" class="button large dark d-none">
					<i class="fg-white mif-exit"></i>&nbsp;Cerrar
				</a>
				<a href="#" id="take-photo" title="Tomar foto" class="button large rounded dark">
					<i class="fg-white mif-photo-camera "></i>&nbsp;Tomar foto
				</a>
				<a href="#" id="upload-photo" title="Subir foto" class="button large rounded dark d-none">
					<i class="fg-white mif-upload "></i>&nbsp;Subir foto
				</a>
				<a href="#" id="resume-camera" title="Volver a tomar foto" class="button large rounded dark d-none">
					<span class="fg-white mif-undo "></span>&nbsp;Volver a tomar
				</a>
			</div>
		</div>        
	</div>
	<div class="md-overlay"></div>
</main>';
	}
}

/*
<a href="#" id="download-photo" download="selfie.png" target="_blank" title="Save Photo" class="d-none" rel="noopener noreferrer">
					<i class="fg-white mif-upload mif-4x"></i>
				</a>

*/