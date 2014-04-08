<?php
require_once 'lib/client.php';

define('VERIFICA_VERSION', 'v1');

class VerificaOrderBy{
	const COUNTRY_CODE = 0;
	const COUNTRY_NAME = 1;
	const COUNTRY_ACRONYM = 2;
}

class VerificaLang {
	const pt_BR = 'pt-br';
	const en = 'en';
	const en_US = 'en';
}

class VerificaDeliveryMethod {
	const SMS = 0;
	const CALL = 1;
}

class Verifica{
	var $client;
	var $lang;
	function __construct($key, $lang=VerificaLang :: pt_BR) {
		$this->client = new VerificaClient($key, $lang);
	}

	function getPublicKey(){
		$r = $this->client->request(VERIFICA_VERSION, 'getPublicKey');
		if ($r["code"] > 0)
			throw new Exception($r["message"]);
		else
			return $r["data"]["key"];
		return FALSE;
	}

	function validateToken($country, $phone_number, $token){
		$r = $this->client->request(VERIFICA_VERSION, 'validateToken', array(
			'country' => $country,
			'phone_number' => $phone_number,
			'token' => $token
			));
		if ($r["code"] > 0)
			throw new Exception($r["message"]);
		else
			return $r["data"];
		return FALSE;
	}

	function deliveryToken($country, $phone_number, $method=VerificaDeliveryMethod :: SMS){
		return $this->client->request(VERIFICA_VERSION, 'deliveryToken', array(
			'phone_number' => $phone_number,
			'country' => $country,
			'delivery_method' => $method
			));
	}

	function getCountryCodes($order=VerificaOrderBy :: COUNTRY_NAME) {
		$r = $this->client->request(VERIFICA_VERSION, 'getCountryCodes', array(
			'order' => $order
			));
		if ($r["code"] > 0)
			throw new Exception($r["message"]);
		else
			return $r["data"];
		return FALSE;
	}
}
?>