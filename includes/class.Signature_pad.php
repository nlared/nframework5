<?
class Signature_pad extends baseInput {
	public $minWidth=0.5;
	public $maxWidth=2.5;
	public $throttle=16;
	public $minDistance=5;
	public $backgroundColor='rgba(0,0,0,0)';
	public $penColor='black';
	public $velocityFilterWeight=0.7;
	public $canvasContextOptions='';
	public $name;
	function __toString():string{
		global $nframework,$javas;
	
		if(!$nframework->onces['Signature_pad']){
			$nframework->jss['115']='https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js';
    		$javas->addjs('
    		var canvaspad=[];
    		var signaturePad=[];
    		');
    		$javas->addjs('
    		const ratio =  Math.max(window.devicePixelRatio || 1, 1);
    		','resize');
    		$nframework->onces['Signature_pad']=true;
    	}
    	$options=[
			'minWidth',
			'maxWidth',
			'throttle',
			'minDistance',
			'backgroundColor',
			'penColor',
			'velocityFilterWeight',
			'canvasContextOptions'
    		];
		
		foreach($options as $option){
			if(!empty($this->{$option})){
				$data[$option]=$this->{$option};
			}
		}
		
		$javas->addjs('
		signaturePad["'.$this->id.'"] = new SignaturePad(document.getElementById("canvaspad_'.$this->id.'"),'.json_encode($data).' );');
		$javas->addjs('
			canvaspad_'.$this->id.'.width = canvaspad_'.$this->id.'.offsetWidth * ratio;
    		canvaspad_'.$this->id.'.height = canvaspad_'.$this->id.'.offsetHeight * ratio;
    		canvaspad_'.$this->id.'.getContext("2d").scale(ratio, ratio);
    		signaturePad["'.$this->id.'"].clear(); 
    	','resize');
		return '<canvas id="canvaspad_'.$this->id.'" class="signature-pad" style="left: 0;top: 0;width:400px; height:200px;"></canvas>';
	}
}