<?php

use Dropbox as dbx;

class Dropbox implements iUploadFile{

	protected $clientIdentifier;
	protected $appInfo;

	function __construct() {

		$this->clientIdentifier =  "SendFiles/1.0";
		$this->appInfo = dbx\AppInfo::loadFromJsonFile(SENDFILES_PATH.'admin/app-info.json');

	}

    /**
     * get webauth
     *
     * @param 
     *
     * @return webauth
     *
     */
    
	public function getWebAuth() {
		
	    $webAuth = new dbx\WebAuthNoRedirect($this->appInfo , $this->clientIdentifier, "en");
	    return $webAuth;
	}

    /**
     * get auth url 
     *
     * @param 
     *
     * @return auhtentication url
     *
     */

	public function getAuthUrl() {
	    $authorizeUrl = self::getWebAuth()->start();
	    return $authorizeUrl;
	}

    /**
     * get client 
     *
     * @param string $accessToken
     *
     * @return client
     *
     */

	public function getClient($accessToken)
	{
		$client = new dbx\Client($accessToken, $this->clientIdentifier);
		return $client;
	}

    /**
     * get account info
     *
     * @param string $accessToken
     *
     * @return account info
     *
     */

	public function getAccountInfo($accessToken)
	{
		// get user info from accesstoken
		$client = new dbx\Client($accessToken, $this->clientIdentifier);
		return $client->getAccountInfo();
	}

    /**
     * set access token & user id to database
     *
     * @param string $accessToken
     *		  string $userId
     *		  string $displayName
     *
     * @return 
     *
     */	
	public function setAccessTokenUserDetails($accessToken, $userId, $displayName){

		$values['access_token'] = $accessToken;
		$values['user_id'] = $userId;
		$values['display_name'] = $displayName;
		$deprecated = null;
		$autoload = 'no';
		$option_name = 'sendfiles-auth' ;
		if ( get_option( $option_name ) !== false ) {
			// The option already exists, so we just update it.
			update_option( $option_name, $values );

		} else {
			add_option( $option_name, $values, $deprecated, $autoload );
		}
	}

    /**
     * upload file to dropbox server & save file name to database
     *
     * @param 
     *
     * @return $dw_link
     *			sharable url for uploaded file
     *
     */
    public function uploadFile()
	{
		ini_set('max_execution_time', 300);
		$values    = get_option( 'sendfiles-auth' , array() );
		$dbxClient = self::getClient($values['access_token']);
		$name      = $_FILES["sendfiles-files"]["name"];
		$f         = fopen($_FILES["sendfiles-files"]["tmp_name"], "rb");
		$result    = $dbxClient->uploadFile("/".$name, dbx\WriteMode::add(), $f);
		fclose($f);
		$file = $dbxClient->getMetadata('/'.$name);

		$dropboxPath = $result['path'];
		$pathError = dbx\Path::findError($dropboxPath);
		if ($pathError !== null) {
			fwrite(STDERR, "Invalid <dropbox-path>: $pathError\n");
			die;
		}

		$link = $dbxClient->createTemporaryDirectLink($dropboxPath);
		$dw_link = $link[0];

		$uploaded_file_details  = '<b>PATH: </b>' . $dw_link . '?dl=1 <br/>';
		$uploaded_file_details .= '<hr/><br/>';
		$uploaded_file_details .= '<b>USER ID: </b>' . $values['user_id'] . '<br/>';
		$uploaded_file_details .= '<b>DATE: </b>' . date('l jS \of F Y h:i:s A') . '<br/>';

		$custom_post_sendfiles = array(
			'post_type'    => 'sendfiles_list',
			'post_title'   => $result['path'],
			'post_content' => $uploaded_file_details,
			'post_status'  => 'publish'
		);

		// insert uploaded file to post table
		wp_insert_post( $custom_post_sendfiles, true );

		echo $dw_link.'?dl=1';//return the uploaded file url
		die();
	}
	
}