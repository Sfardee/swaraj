<?php // class_FormToken.php
/**
 * A helper class for form token processing
 *
 * Originally published, with discussion, here:
 * https://www.experts-exchange.com/articles/28802/
 */

Class FormToken
{
    const FORM_TOKEN_PREFIX = 'form_token_';
    const FORM_TOKEN_EXPIRY = 3600;

    /**
     * Static method get() returns a form token object
     */
    public static function get()
    {
        $obj = new StdClass;

        $obj->time  = time();
        if (function_exists('random_bytes')) {
            $obj->name  = static::FORM_TOKEN_PREFIX . bin2hex( random_bytes(32) );
            $obj->token = static::FORM_TOKEN_PREFIX . bin2hex( random_bytes(32) );
        }
        // Fall-back for PHP < 7
        else {
            $obj->name  = static::FORM_TOKEN_PREFIX . md5( uniqid() . rand() );
            $obj->token = static::FORM_TOKEN_PREFIX . md5( uniqid() . rand() );
        }

        return $obj;
    }

    /**
     * Static method tidy() removes expired tokens
     */
    public static function tidy()
    {
        $timex = time() - static::FORM_TOKEN_EXPIRY;
        $prefix_length = strlen(static::FORM_TOKEN_PREFIX);
        foreach ($_SESSION as $key => $value) {
            if (substr($key,0,$prefix_length) == static::FORM_TOKEN_PREFIX) {
                if ($token = json_decode($value)) {
                    if (!empty($token->time) && ($token->time < $timex)) {
                        unset($_SESSION[$key]);
                        session_write_close();
                    }
                }
            }
        }
    }

    /**
     * Static method check() verifies that a token is valid
     */
    public static function check()
    {
        $valid = FALSE;

        // Remove expired tokens
        static::tidy();

        // Rudimentary same-origin check
        $regex = '#' . preg_quote($_SERVER['HTTP_HOST']) . '#i';
        if (preg_match($regex, $_SERVER['HTTP_REFERER'])) {

            // If same-origin check passes
            $prefix_length = strlen(static::FORM_TOKEN_PREFIX);
            foreach ($_SESSION as $key => $value) {
                if (substr($key,0,$prefix_length) == static::FORM_TOKEN_PREFIX) {
                    if ($session_token_obj = json_decode($value)) {
                        if (!empty($_POST[$session_token_obj->name]) && ($_POST[$session_token_obj->name] == $session_token_obj->token)) {
                            $valid = TRUE;

                            // Make each token into a single-use token
                            unset($_SESSION[$key]);
                            session_write_close();
                        }
                    }
                }
            }
        }

        return $valid;
    }

}
