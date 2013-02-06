<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Whiskers_post_appnet extends CI_Driver {

    const API_URL = 'https://alpha-api.app.net';
	const DEFAULT_HTTP_TIMEOUT = 30;
	
    private $CI;
	private	$consumer_key;
	private $consumer_secret;
	private $token;
	private $nickname;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI =& get_instance();
		
        // Load local config
        $appnet_settings = $this->CI->settings->get('appnet');
        $this->consumer_key = $appnet_settings->appnet_consumer_key;
        $this->consumer_secret = $appnet_settings->appnet_consumer_secret;
        $this->token = $appnet_settings->token;
        $this->nickname = $appnet_settings->user->nickname;
    }


    public function save_post($text)
    {
        if (empty($text))
        {
            $this->CI->session->setMessage('Text cannot be empty', 'error');
            return FALSE;
        }

		// Post text to service
		$post = $this->makeCall('/stream/0/posts', array('text' => $text), 'POST');
		
		// Grab post ID from returned data
		$id = $post->data->id;

        $time = time();

        // Update has been posted, save to DB
        $key = sha1($time.':'.$text);
        $saved = $this->CI->posts->update($key, array(
            'type' => 'post',
            'text' => $text,
            'time' => $time,
            'source_urls' => array(
                'appnet' => 'https://alpha.app.net/'.$this->nickname.'/post/'.$id		// URL pointing to post on service's site
            ) 
        ));

        return ( ! $saved) ? FALSE : $id;
    }


    public function remove_post($key)
    {
        $update = $this->CI->posts->get($key);
        $url_pieces = explode('/', $update->source_urls->appnet);
        $id = $url_pieces[count($url_pieces)-1];	// Last part of URL is post ID

        if (empty($id))
        {
            return $this->CI->posts->rm($key);
        }

        // remove from App.net
        try {
			$post = $this->makeCall('/stream/0/posts/'.$id, null , "DELETE");
        } catch (Exception $e) {
            $this->session->setMessage($e, 'error');
            return FALSE;
        }

        if ( !$update )
        {
            $this->session->setMessage('Could not delete post from App.net', 'error');
            return FALSE;
        }

        if ( ! $this->CI->posts->rm($key) )
        {
            $this->session->setMessage('Could not remove post from Database', 'error');
            return FALSE;
        }

        return $id;
    }

    private function makeCall($path, $parameters = null, $method = 'GET')
    {
		if ($parameters == null) {
			$parameters = array();
		}

        if (empty($this->token)) {
            $this->session->setMessage('App.net: not authorized', 'error');
            return FALSE;
        }

		$url = self::API_URL . $path;
		
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, self::DEFAULT_HTTP_TIMEOUT);

		// Add authorization header (see http://developers.app.net/docs/authentication/#making-authenticated-api-requests )
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(sprintf("Authorization: Bearer %s", $this->token)));

        if ( 'POST' == $method ) {
            curl_setopt($curlHandle, CURLOPT_POST, TRUE);
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameters);
        } else {
            // For GET/DELETE pass params on URL
            curl_setopt($curlHandle, CURLOPT_URL, "$url?" . $this->joinParameters($parameters));
        }

        $response = curl_exec($curlHandle);
        $headers = curl_getinfo($curlHandle);

        curl_close($curlHandle);

        return ( empty($response) ? NULL : json_decode($response) );
    }

	
	// Join parameters together into URL-encoded string
    private function joinParameters($parameters)
    {
        foreach ($parameters as $key => $value) {
			$pairs[] = urlencode($key) . '=' . urlencode($value);
        }
        return implode("&", $pairs);
    }	
}
