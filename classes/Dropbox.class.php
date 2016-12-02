<?php

use Dropbox as dbx;

class Dropbox {

	function __construct() {

	}

	public function getWebAuth() {
		$appInfo = dbx\AppInfo::loadFromJsonFile(DROPIT_PATH.'admin/app-info.json');
	    $clientIdentifier = "DropIt/1.0";
	    $webAuth = new dbx\WebAuthNoRedirect($appInfo , $clientIdentifier, "en");
	    return $webAuth;
	}

	public function getAuthUrl() {
	    $authorizeUrl = self::getWebAuth()->start();
	    return $authorizeUrl;
	}

	public function getClient($accessToken)
	{
		// get user info from accesstoken
			$clientIdentifier = "DropIt/1.0";
            $client = new dbx\Client($accessToken, $clientIdentifier);
            return $client->getAccountInfo();
	}
}