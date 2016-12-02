<?php

use Dropbox as dbx;

class Dropbox {

	function __construct() {

	}

	// get web auth
	public function getWebAuth() {
		$appInfo = dbx\AppInfo::loadFromJsonFile(SENDFILES_PATH.'admin/app-info.json');
	    $clientIdentifier = "SendFiles/1.0";
	    $webAuth = new dbx\WebAuthNoRedirect($appInfo , $clientIdentifier, "en");
	    return $webAuth;
	}

	// get auth url
	public function getAuthUrl() {
	    $authorizeUrl = self::getWebAuth()->start();
	    return $authorizeUrl;
	}

	public function getClient($accessToken)
	{
		// get user info from accesstoken
			$clientIdentifier = "SendFiles/1.0";
            $client = new dbx\Client($accessToken, $clientIdentifier);
            return $client->getAccountInfo();
	}
}