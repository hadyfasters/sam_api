<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SAM_Security extends CI_Security{

    protected $_auth_token_name = 'X-Auth-Token';

	/**
     * CSRF Verify
     *
     * @return  CI_Security
     */
	public function csrf_verify()
    {
        $this->encryption =& load_class('Encryption');

        // If it's not a POST request we will set the CSRF cookie
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
        {
            return $this->csrf_set_cookie();
        }

        // Check if URI has been whitelisted from CSRF checks
        if ($exclude_uris = config_item('csrf_exclude_uris'))
        {
            $uri = load_class('URI', 'core');
            foreach ($exclude_uris as $excluded)
            {
                if (preg_match('#^'.$excluded.'$#i'.(UTF8_ENABLED ? 'u' : ''), $uri->uri_string()))
                {
                    return $this;
                }
            }
        }

        // Do the tokens exist in both the _POST and _COOKIE arrays?
        if ( ! isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])) {
            // Check X-Auth-Token
            $header = getallheaders();
            // No token found in $_POST - checking JSON data
            $input_data = $this->setInput(trim(file_get_contents('php://input')));
            if ((!$input_data || !isset($input_data[$this->_csrf_token_name], $header[$this->_auth_token_name])))
                $this->csrf_show_error(); // Nothing found
            else {
                // Decrypt X-Auth-Token
                // $header[$this->_auth_token_name] = json_decode($this->encryption->sam_decrypt($header[$this->_auth_token_name]),true);
                $header[$this->_auth_token_name] = strrev($header[$this->_auth_token_name]);
                // Do the tokens match?
                // if ($input_data[$this->_csrf_token_name] != $header[$this->_auth_token_name][$this->_csrf_token_name]){
                //     $this->csrf_show_error();
                // }
                if ($input_data[$this->_csrf_token_name] != $header[$this->_auth_token_name]){
                    $this->csrf_show_error();
                }
            }
        }
        else {
            // Do the tokens match?
            if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name])
                $this->csrf_show_error();
        }        // We kill this since we're done and we don't want to

        // polute the _POST array
        unset($_POST[$this->_csrf_token_name]);

        // Regenerate on every submission?
        if (config_item('csrf_regenerate'))
        {
            // Nothing should last forever
            unset($_COOKIE[$this->_csrf_cookie_name]);
            $this->_csrf_hash = NULL;
        }

        $this->_csrf_set_hash();
        $this->csrf_set_cookie();

        log_message('debug', 'CSRF token verified');

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Show CSRF Error
     *
     * @return  void
     */
    public function csrf_show_error()
    {
        $response = [
            'status' => FALSE,
            'message' => 'The action you have requested is not allowed.'
        ];

        http_response_code(403);
        header('Content-Type: application/json');
        die(json_encode($response));
    }

    // --------------------------------------------------------------------

    public function setInput($input_data)
    {
        // $this->encryption =& load_class('encryption');
        $result = json_decode($input_data, true);
        if(empty($result)) 
            $result = json_decode($this->encryption->sam_decrypt($input_data), true);
        return $result;
    }
}