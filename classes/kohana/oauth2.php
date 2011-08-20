<?php defined('SYSPATH') or die('No direct script access.');

include_once Kohana::find_file('vendor', 'OAuth2', 'inc');
include_once Kohana::find_file('vendor', 'OAuth2Client', 'inc');
include_once Kohana::find_file('vendor', 'OAuth2Exception', 'inc');

/**
 *
 *
 * @package    MIP Website
 * @category   Controller
 * @author     Managed I.T.
 * @copyright  (c) 2011 Managed I.T.
 */
class Kohana_OAuth2 extends OAuth2 {

	public static $db_group = 'default';

	protected function getSupportedGrantTypes()
	{
		return array(
			OAUTH2_GRANT_TYPE_AUTH_CODE,
		);
	}

	protected function checkClientCredentials($client_id, $client_secret = NULL)
	{
		$query = DB::query(Database::SELECT, "SELECT client_secret FROM clients WHERE client_id = :client_id");

		$query->param(':client_id', $client_id);

		$result = $query->execute(Kohana_OAuth2::$db_group);

		if ($client_secret === NULL)
			return ($result->count() == 1);

		return $result[0]["client_secret"] == $client_secret;
	}

	protected function getRedirectUri($client_id)
	{
		$query = DB::query(Database::SELECT, "SELECT redirect_uri FROM clients WHERE client_id = :client_id");

		$query->param(':client_id', $client_id);

		$result = $query->execute(Kohana_OAuth2::$db_group);

		if ($result->count() != 1)
			return FALSE;

		return $result[0]['redirect_uri'];
	}

	protected function getAuthCode($code)
	{
		$query = DB::query(Database::SELECT, "SELECT code, client_id, redirect_uri, expires, scope FROM auth_codes WHERE code = :code");

		$query->param(':code', $code);

		$result = $query->execute(Kohana_OAuth2::$db_group)->as_array();

		return (count($result) == 1) ? $result[0] : NULL;
	}

	protected function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL)
	{
		$query = DB::query(Database::INSERT, "INSERT INTO auth_codes (code, client_id, redirect_uri, expires, scope) VALUES (:code, :client_id, :redirect_uri, :expires, :scope)");

		$query->param(":code", $code);
		$query->param(":client_id", $client_id);
		$query->param(":redirect_uri", $redirect_uri);
		$query->param(":expires", $expires);
		$query->param(":scope", $scope);

		$result = $query->execute();
	}

	protected function getAccessToken($oauth_token)
	{
		$query = DB::query(Database::SELECT, "SELECT client_id, expires, scope FROM tokens WHERE oauth_token = :oauth_token");

		$query->param(':oauth_token', $oauth_token);

		$result = $query->execute(Kohana_OAuth2::$db_group)->as_array();

		return (count($result) == 1) ? $result[0] : NULL;
	}

	protected function setAccessToken($oauth_token, $client_id, $expires, $scope = NULL)
	{
		$query = DB::query(Database::INSERT, "INSERT INTO tokens (oauth_token, client_id, expires, scope) VALUES (:oauth_token, :client_id, :expires, :scope)");

		$query->param(":oauth_token", $oauth_token);
		$query->param(":client_id", $client_id);
		$query->param(":expires", $expires);
		$query->param(":scope", $scope);

		$result = $query->execute();
	}

}