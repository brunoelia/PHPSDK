<?php

class Meli {

    const VERSION  = '1.0.0';

    protected static $API_ROOT_URL = 'https://api.mercadolibre.com';
    protected static $AUTH_URL     = 'https://auth.mercadolivre.com/authorization';
    protected static $OAUTH_URL    = '/oauth/token';

    public static $CURL_OPTS = array(
        CURLOPT_USERAGENT => "MELI-PHP-SDK-1.0.0", 
        CURLOPT_CONNECTTIMEOUT => 10, 
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => array('Accept: application/json')
    );


    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $access_token;
    protected $refresh_token;

    public function __construct($client_id = null, $client_secret = null, $access_token = null, $refresh_token = null) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }

    public function auth_url($redirect_uri) {
        $this->redirect_uri = $redirect_uri;
        $params = array('client_id' => $this->client_id, 'response_type' => 'code', 'redirect_uri' => $redirect_uri);
        $auth_uri = self::$AUTH_URL.'?'.http_build_query($params);
        return $auth_uri;
    }

    public function authorize($code, $redirect_uri = null) {

        if($redirect_uri)
            $this->redirect_uri = $redirect_uri;

        $params = array(
            'grant_type' => 'authorization_code', 
            'client_id' => $this->client_id, 
            'client_secret' => $this->client_secret, 
            'code' => $code, 
            'redirect_uri' => $this->redirect_uri
        );

        $url = self::$API_ROOT_URL.self::$OAUTH_URL;
        $opts = self::$CURL_OPTS;

        $ch = curl_init($url);
        curl_setopt_array($ch, $opts);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($result);

        if($httpCode == 200) {             
            $this->access_token = $result->access_token;

            if($result->refresh_token)
                $this->refresh_token = $result->refresh_token;

            return $this->access_token;

        } else {
            return $result;
        }
    }

    public function get_refresh_token() {

        if($this->refresh_token) {
             $params = array(
                'grant_type' => 'refresh_token', 
                'client_id' => $this->client_id, 
                'client_secret' => $this->client_secret, 
                'refresh_token' => $this->refresh_token
            );

            $url = self::$API_ROOT_URL.self::$OAUTH_URL;
            $opts = self::$CURL_OPTS;

            $ch = curl_init($url);
            curl_setopt_array($ch, $opts);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);
            $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($result);

            if($httpCode == 200) {             
                $this->access_token = $result->access_token;

                if($result->refresh_token)
                    $this->refresh_token = $result->refresh_token;

                return $this->refresh_token;

            } else {
                return $result;
            }   
        } else {
            throw new Exception('Offline-Access is not allowed.');
        }        
    }
}
