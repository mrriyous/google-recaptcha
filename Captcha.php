<?php 
 /**
 *
 * Google ReCaptcha Library
 *		@author    Alfi Syahri
 *  	@link      http://anjir.esy.es
 *		
 *
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          https://developers.google.com/recaptcha/docs/php
 *    - Get a reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin/create
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * 	Recaptcha From Google Inc.
 *
 * 	@copyright Copyright (c) 2014, Google Inc.
 * 	@link      http://www.google.com/recaptcha
 *
 */

class Captcha
{
	private $secret;

	private $google_url = "https://www.google.com/recaptcha/api/siteverify"; // Google Recaptcha API

   	private $verifyCaptcha = false;	
   	
   	private $response;
   	
   	private $remoteIp;

   	private $verifyurl;

	function __construct($secret)
	{
		$this->secret = $secret;
	}

	public function verify($response,$remoteIp=null){
		
		if(empty($response) or $response==""){
			return json_decode(json_encode(array('success' => 'false','error-codes' => 'Invalid Response !!')));
		}

		$this->response = $response;
		
		if(!empty($remoteIp)){
			$this->remoteIp = $remoteIp;
		}

		$this->verifyurl = $this->_url();
		
		$curl = curl_init();
	        
		curl_setopt($curl, CURLOPT_URL, $this->verifyurl);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
	    $curlData = curl_exec($curl);

	    curl_close($curl);

	    $res = json_decode($curlData, TRUE);
	    return $res;
	}

	private function _url(){
		$l = $this->google_url."?secret=".$this->secret."&response=".$this->response;
		if($this->remoteIp !== ""){
			$l.="&remoteip=".$this->remoteIp;
		}
		return $l;
	}
}