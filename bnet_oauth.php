<?php
// OAuth Endpoint Changes
// GET /oauth/userinfo replaces the GET /account/user endpoint previously used in the Mashery portal.

class bnet_oauth
{
	private $config = array(
		'client_id' => '',
		'client_secret' => '',
		'api_baseurl' => 'https://us.api.blizzard.com',
		'access_token' => null,
	);

	/**
	 * https://develop.battle.net/documentation/guides/using-oauth/client-credentials-flow
	 */
	public function __construct()
	{
		/* Step 1, get access_token */
		$ch = curl_init();
		$curlopt_options = array(
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HEADER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query( array('grant_type' => 'client_credentials' ) ),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://us.battle.net/oauth/token',
			CURLOPT_USERPWD => $this->config['client_id'] . ':' . $this->config['client_secret'],
		);
		curl_setopt_array($ch, $curlopt_options);
		$json = curl_exec($ch);
		$data = json_decode($json, true);
		if ($data != null)
		{
			$this->config['access_token'] = $data['access_token'];
		}
		curl_close($ch);
	}

	public function getCharacterFeed($realm, $character)
	{
		$uri = 'character/' . $realm . '/' . urlencode($character);
		
		$data = $this->_call($uri, array("fields" => "feed"));

		return $data;
	}

	public function _call($uri, $params = array())
	{
		if (is_null($this->config['access_token']))
		{
			return '{}';
		}
		$addl_params = array(
			'locale' => 'en_US',
			'access_token' => $this->config['access_token'],
		);
		$params = array_merge($params, $addl_params);
		$parameters = '?' . http_build_query($params);

		$ch = curl_init();

		$curlopt_options = array(
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $this->config['access_token']),
			CURLOPT_URL => $this->config['api_baseurl'] . '/wow/' . $uri . $parameters,
		);
		curl_setopt_array($ch, $curlopt_options);
		$json = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($json, true);
		if (is_null($json))
		{
			return '{}';
		}
		return $json;
	}
}
