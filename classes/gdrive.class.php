<?php

				


class GoogleDrive implements iUploadFile{

	protected $g_drive;

	public function __construct() {
		define('APPLICATION_NAME', 'Send Files');
		define('CLIENT_SECRET_PATH', SENDFILES_PATH.'admin/client_secret.json');
		define('SCOPES', implode(' ', array(
			Google_Service_Drive::DRIVE_METADATA)
		));
		$this->g_drive = new Google_Client();
		$this->g_drive->setHttpClient(new GuzzleHttp\Client(['verify' => false]));
		$this->g_drive->setApplicationName(APPLICATION_NAME);
		$this->g_drive->setScopes(SCOPES);
		$this->g_drive->setAuthConfig(CLIENT_SECRET_PATH);
		$this->g_drive->setAccessType('offline');
	}

    /**
     * set accesstoken to database
     *
     * @param 
     *
     * @return 
     *
     */
    
	public function setAccessToken($auth_code) {
	
	    $accessToken = $this->g_drive->fetchAccessTokenWithAuthCode($auth_code);
	    $this->g_drive->setAccessToken($accessToken);
		if ($this->g_drive->isAccessTokenExpired()) {
			$this->g_drive->fetchAccessTokenWithRefreshToken($this->g_drive->getRefreshToken());
			$accessToken = $this->g_drive->getAccessToken();
		}

	    $values['access_token'] = $accessToken['access_token'];

	    $deprecated = null;
	    $autoload = 'no';
		$option_name = 'sendfiles-auth' ;
		if ( get_option( $option_name ) !== false ) {

		    // The option already exists, so we just update it.
		    update_option( $option_name, $values );

		} else {

		    add_option( $option_name, $values, $deprecated, $autoload );
		}
		 return $accessToken['access_token'];
	}

    

	public function uploadFile()
	{
	}
	
}