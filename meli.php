<?php

class Meli {

    const VERSION  = "1.0.0";

    protected static $API_ROOT_URL = "https://api.mercadolibre.com";
    protected static $AUTH_URL     = "https://auth.mercadolivre.com/authorization";
    protected static $OAUTH_URL    = "/oauth/token";

    public static $CURL_OPTS = array(
        CURLOPT_USERAGENT => "MELI-PHP-SDK-1.0.0", 
        CURLOPT_CONNECTTIMEOUT => 10, 
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => array("Accept: application/json")
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
        $params = array("client_id" => $this->client_id, "response_type" => "code", "redirect_uri" => $redirect_uri);
        $auth_uri = self::$AUTH_URL."?".http_build_query($params);
        return $auth_uri;
    }

    public function authorize($code, $redirect_uri = null) {

        if($redirect_uri)
            $this->redirect_uri = $redirect_uri;

        $body = array(
            "grant_type" => "authorization_code", 
            "client_id" => $this->client_id, 
            "client_secret" => $this->client_secret, 
            "code" => $code, 
            "redirect_uri" => $this->redirect_uri
        );

        $opts = array(
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $body
        );
    
        $request = $this->execute(self::$OAUTH_URL, $opts, $params);

        if($request["httpCode"] == 200) {             
            $this->access_token = $request["body"]->access_token;

            if($request["body"]->refresh_token)
                $this->refresh_token = $request["body"]->refresh_token;

            return $this->access_token;

        } else {
            return $request;
        }
    }

    public function get_refresh_token() {

        if($this->refresh_token) {
             $body = array(
                "grant_type" => "refresh_token", 
                "client_id" => $this->client_id, 
                "client_secret" => $this->client_secret, 
                "refresh_token" => $this->refresh_token
            );

            $opts = array(
                CURLOPT_POST => true, 
                CURLOPT_POSTFIELDS => $body
            );
        
            $request = $this->execute(self::$OAUTH_URL, $opts, $params);

            if($request["httpCode"] == 200) {             
                $this->access_token = $request["body"]->access_token;

                if($request["body"]->refresh_token)
                    $this->refresh_token = $request["body"]->refresh_token;

                return $this->refresh_token;

            } else {
                return $request;
            }   
        } else {
            throw new Exception("Offline-Access is not allowed.");
        }        
    }

    public function get($path, $params = null) {
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    public function post($path, $body = null, $params = array()) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $body
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    public function put($path, $body = null, $params = null) {
        $body = json_encode($body);
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $body
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    public function delete($path, $params = null) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => $params
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    public function options($path, $params = null) {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => "OPTIONS"
        );
        
        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    private function execute($path, $opts = array(), $params = array()) {
        $uri = $this->make_path($path, $params);

        $ch = curl_init($uri);
        curl_setopt_array($ch, self::$CURL_OPTS);

        if(!empty($opts))
            curl_setopt_array($ch, $opts);

        $return["body"] = json_decode(curl_exec($ch));
        $return["httpCode"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        
        return $return;
    }

    private function make_path($path, $params = array()) {
        if (!preg_match("/^http/", $path)) {
            if (!preg_match("/^\//", $path)) {
                $path = '/'.$path;
            }
            $uri = self::$API_ROOT_URL.$path;
        } else {
            $uri = $path;
        }

        if(!empty($params)) {
            $params = '?'.http_build_query($params);
            $uri = $uri.$params;
        }

        return $uri;
    }
}
?>