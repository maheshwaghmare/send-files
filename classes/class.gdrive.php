<?php



class GDrive {

	function __construct() {

	}

	// get google dive atuh url
	public function googleAuthUrl() {
		$drive_client = new Google_Client();
		$drive_client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));
		$drive_client->setAuthConfig(SENDFILES_PATH.'admin/client_secret.json');
		$drive_client->addScope(Google_Service_Drive::DRIVE);
		$drive_authUrl = $drive_client->createAuthUrl();
		return $drive_authUrl;
	}
}


