<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class SAM_Encryption extends CI_Encryption
{
	
	public function sam_encrypt($original_data,$cipher="AES-128-CBC")
	{
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt(json_encode($original_data), $cipher, $this->_key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->_key, $as_binary=true);
        $crypted_data = $this->urlsafeB64Encode( $iv.$hmac.$ciphertext_raw );

        return $crypted_data;
	}
	
	public function sam_decrypt($encrypted_data,$cipher="AES-128-CBC")
	{
        $c = $this->urlsafeB64Decode($encrypted_data);
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = substr($c, 0, $ivlen);
		$hmac = substr($c, $ivlen, $sha2len=32);
		$ciphertext_raw = substr($c, $ivlen+$sha2len);
		$original_data = openssl_decrypt($ciphertext_raw, $cipher, $this->_key, $options=OPENSSL_RAW_DATA, $iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $this->_key, $as_binary=true);
		if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
		{
		    return $original_data;
		}
	}

	public static function urlsafeB64Decode($input)
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

	public static function urlsafeB64Encode($input)
    {
        return \str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }
}