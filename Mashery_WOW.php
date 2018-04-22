<?php
/**
 * https://dev.battle.net/io-docs
 */
class Mashery_WOW
{
	private $apikey = '';

	private $apilocale = 'en_US';

	public function __construct($apikey)
	{
		$this->apikey = $apikey;
	}

	public function getCharacterFeed($realm, $character)
	{
		$uri = 'character/' . $realm . '/' . $character;
		
		$data = $this->_call($uri, array("fields" => "feed"));

		return $data;
	}

	private function _call($uri, $params = array())
	{
		$addl_params = array(
			'locale' => $this->apilocale,
			'apikey' => $this->apikey,
		);
		$params = array_merge($params, $addl_params);
		ksort($params);
		$parameters = '?' . http_build_query($params);
		$url = 'https://us.api.battle.net/wow/' . $uri . $parameters;
		
		$ch = curl_init($url);
		$curlopt_options = array(
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
		);
		curl_setopt_array($ch, $curlopt_options);
		$data = curl_exec($ch);
		return $data;
	}
}
?>
