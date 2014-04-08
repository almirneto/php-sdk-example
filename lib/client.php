<?php
class VerificaHeaders{
	const URL = 'http://verifica.la/api/';
	const USER_AGENT = 'Verifica Lá: PHP-SDK;';
}
class VerificaClient{
	var $key;
	var $context_options;
	var $lang;

	function __construct($key, $lang){
		$this->key = $key;
		$this->lang = $lang;
	}

	function rfopen($service, $method, $params=array())
	{
		if(!$this->context_options) {
			$this->context_options = array('http' => array(
				'method' => "POST",
				'header' => "Authorization: {$this->key}\r\n".
					"Content-type: application/x-www-form-urlencoded\r\n".
					"Accept-Language: {$this->lang}",
				'user_agent' => VerificaHeaders :: USER_AGENT));
		}
		$this->context_options['http']['content'] = http_build_query($params);
		$context = stream_context_create($this->context_options);
		$url = (VerificaHeaders :: URL)."{$service}/{$method}/";
		$fp = fopen($url, 'r', false, $context);
		$result = '';
		while($r = fread($fp, 1024)) {
			$result .= $r;
		}
		fclose($fp);
		return json_decode($result, true);
	}


	function rcurl($service, $method, $params=array())
	{
		if(!$this->context_options)
		{
			$this->context_options = array('http' => array(
				'method' => "POST",
				'header' => "Authorization: {$this->key}\r\n".
					"Content-type: application/x-www-form-urlencoded\r\n".
					"Accept-Language: {$this->lang}",
				'user_agent' => VerificaHeaders :: USER_AGENT));
		}

		$this->context_options['http']['content'] = http_build_query($params);
		$url = (VerificaHeaders :: URL)."{$service}/{$method}/";

		//open connection
		$ch = curl_init();

		//headers
		$headers = array($this->context_options['http']['header']);

		// Curls
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, (VerificaHeaders :: USER_AGENT));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->context_options['http']['content']);

		//execute post
		$result = curl_exec($ch);

		curl_close($ch);

		return json_decode($result, true);

	}


	function request($service, $method, $params=array())
	{
		if (function_exists('curl_version'))
			return $this->rcurl($service, $method, $params);
		else
			return $this->rfopen($service, $method, $params);
	}

}

?>