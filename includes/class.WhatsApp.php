<?
use GuzzleHttp\Client;

class WhatsApp{
	private $accessToken;
	private  $phoneNumberId;
	public function __construct(){
		global $config;
		
		
	}
	
	private function EnviarImagen($toPhoneNumber,$mediaId){
		$client = new Client();
		$url = "https://graph.facebook.com/v12.0/$this->phoneNumberId/messages";
		$response = $client->post($url, [
		    'headers' => [
		        'Authorization' => "Bearer $this->accessToken",
		        'Content-Type' => 'application/json'
		    ],
		    'json' => [
		        'messaging_product' => 'whatsapp',
		        'to' => $toPhoneNumber,
		        'type' => 'image',
		        'image' => [
		            'id' => $mediaId
		        ]
		    ]
		]);
		return $response->getBody();
	}
	private function EnviarTexto($toPhoneNumber,$message){
		$client = new Client();
		$url = "https://graph.facebook.com/v12.0/$this->phoneNumberId/messages";
		$response = $client->post($url, [
		    'headers' => [
		        'Authorization' => "Bearer $this->accessToken",
		        'Content-Type' => 'application/json'
		    ],
		    'json' => [
		        'messaging_product' => 'whatsapp',
		        'to' => $toPhoneNumber,
		        'type' => 'text',
		        'text' => [
		            'body' => $message
		        ]
		    ]
		]);
		return $response->getBody();
	}
	
	

	public function SendImage($imagePath){
		if(file_exists($imagePath)){
			$fileName = basename($imagePath);
			$response = $client->post("https://graph.facebook.com/v12.0/$this->phoneNumberId/media", [
			    'headers' => [
			        'Authorization' => "Bearer $this->accessToken"
			    ],
			    'multipart' => [
			        [
			            'name' => 'file',
			            'contents' => fopen($imagePath, 'r'),
			            'filename' => $fileName
			        ],
			        [
			            'name' => 'messaging_product',
			            'contents' => 'whatsapp'
			        ]
			    ]
			]);
			
			$responseData = json_decode($response->getBody(), true);
			$mediaId = $responseData['id'];
			return $mediaId;
		}
		
	}
	
}