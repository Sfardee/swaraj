<?php
/****************************************************************************************************
 String Helpers
 Ascii only
 Combined from Codeigniter, Yii and Laravel
****************************************************************************************************/

require_once('src/Inflector.php');
require_once('src/htmlpurifier/HTMLPurifier.auto.php');

// ------------------------------------------------------------------------
if (!function_exists('cascade')) 
{
    /**
     * Cascade select of a variable. If the first argument is empty then move on the next and the next until a non-empty value is found
     *
     * @param  mixed    $var1, $var2, $var3 any number of values
     * @return mixed    The first non-empty variable found
     */ 
    function cascade() {
        foreach (func_get_args() as $v) {
            if (!empty($v))
                return $v;
        }
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_contains'))
{
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) !== false) return true;
        }

        return false;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_is'))
{
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string  $pattern
     * @param  string  $value
     * @return bool
    */
    function str_is($value, $pattern)
    {
        if ($pattern == $value)
            return true;

        //$pattern = preg_quote($pattern, '#');

        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('*', '.*', $pattern);
        $pattern = str_replace('?', '.', $pattern).'\z';
        return (bool) preg_match('#^'.$pattern.'$#', $value);
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_starts_with'))
{
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) return true;
        }

        return false;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_ends_with'))
{
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_ends_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ((string) $needle === substr($haystack, -strlen($needle))) return true;
        }

        return false;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_plural'))
{
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int     $count
     * @return string
     */
    function str_plural($value, $count = 2)
    {
        if ($count > 1) {
            $plural = Inflector::pluralize($value);
            return str_match_case($plural, $value);
        }

        return $value;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_singular'))
{
    /**
     * Get the singular form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    function str_singular($value)
    {
        $singular = Inflector::singularize($value);
        return str_match_case($singular, $value);
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_match_case'))
{
    /**
     * Attempt to match the case on two strings.
     *
     * @param  string  $value
     * @param  string  $comparison
     * @return string
     */
    function str_match_case($value, $comparison)
    {
        $functions = array('mb_strtolower', 'mb_strtoupper', 'ucfirst', 'ucwords');
        foreach ($functions as $function)
        {
            if (call_user_func($function, $comparison) === $comparison)
            {
                return call_user_func($function, $value);
            }
        }
        return $value;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_truncate'))
{
    /**
     * Truncates a string to the number of characters specified.
     *
     * @param string $string The string to truncate.
     * @param integer $length How many characters from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     * @return string the truncated string.
     */
    function str_truncate($string, $length, $suffix = '...')
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length) . $suffix;
        } else {
            return $string;
        }
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_truncate_words'))
{
   
    /**
     * Truncates a string to the number of words specified.
     *
     * @param string $string The string to truncate.
     * @param integer $count How many words from original string to include into truncated string.
     * @param string $suffix String to append to the end of truncated string.
     * @param boolean $asHtml Whether to treat the string being truncated as HTML and preserve proper HTML tags.
     * This parameter is available since version 2.0.1.
     * @return string the truncated string.
     */
    function str_truncate_words($string, $length, $suffix = '...')
    {
        $length = abs((int)$length);
        if (strlen($string) > $length)
            $string = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1' . $suffix, $string);

        return $string;
    }
}


// ------------------------------------------------------------------------
if ( ! function_exists('str_truncate_sentences'))
{
	 
	/**
	 * Truncates a string to the number of words specified.
	 *
	 * @param string $string The string to truncate.
	 * @param integer $count How many sentences from original string to include into truncated string.
	 * This parameter is available since version 2.0.1.
	 * @return string the truncated string.
	 */
	function str_truncate_sentences($string, $length)
	{
		$string = strip_tags($string);
		preg_match('/^([^.!?]*[\.!?]+){0,'.$length.'}/', strip_tags($string), $string);
		return $string[0];
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_truncate_html'))
{
    /**
     * Truncate a string while preserving the HTML.
     * 
     * @param string $string The string to truncate
     * @param integer $count
     * @param string $suffix String to append to the end of the truncated string.
     * @return string
     */
    function str_truncate_html($string, $length, $suffix='...', $words=true)
    {
        $config = HTMLPurifier_Config::create(null);
        $lexer = HTMLPurifier_Lexer::create($config);
        $tokens = $lexer->tokenizeHTML($string, $config, null);
        $openTokens = 0;
        $totalLength = 0;
        $reached = false;
        $truncated = [];
        foreach ($tokens as $token) {
            if ($token instanceof HTMLPurifier_Token_Start) { //Tag begins
                $openTokens++;
                $truncated[] = $token;
            } else if ($token instanceof HTMLPurifier_Token_Text && !$reached) {
                $sub = '';
                if ($words) {
                    $sub = str_truncate_words($token->data, $length - $totalLength, $suffix);
                } else {
                    $sub = str_truncate($token->data, $length - $totalLength, $suffix);
                }
                $currentLength = strlen($sub);
                $totalLength += $currentLength;

                if ($currentLength < strlen($token->data) || $totalLength == $length)
                    $reached = true;
                
                $token->data = $sub;
                $truncated[] = $token;
            } else if ($token instanceof HTMLPurifier_Token_End) { //Tag ends
                $openTokens--;
                $truncated[] = $token;
            } else if ($token instanceof HTMLPurifier_Token_Empty) { //Self contained tags, i.e. <img/> etc.
                $truncated[] = $token;
            }
            if (0 === $openTokens && $reached) {
                break;
            }
        }
        $context = new HTMLPurifier_Context();
        $generator = new HTMLPurifier_Generator($config, $context);
        $text =  $generator->generateFromTokens($truncated);
        
        //Process badly formed HTML and returns the semantic HTML
        $doc = new DOMDocument();
        $doc->loadHTML($text);
        return $doc->saveHTML();
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_trim_slashes'))
{
    /**
     * Trim Slashes
     * Removes any leading/trailing slashes from a string:
     *
     * /this/that/theother/
     * becomes:
     * this/that/theother
     *
     * @todo    Remove in version 3.1+.
     * @deprecated  3.0.0   This is just an alias for PHP's native trim()
     *
     * @param   string
     * @return  string
     */
    function str_trim_slashes($str)
    {
        return trim($str, '/');
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_strip_quotes'))
{
    /**
     * Strip Quotes
     *
     * Removes single and double quotes from a string
     *
     * @param   string
     * @return  string
     */
    function str_strip_quotes($str)
    {
        return str_replace(array('"', "'"), '', $str);
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_quotes_to_entities'))
{
    /**
     * Quotes to Entities
     * Converts single and double quotes to entities
     *
     * @param   string
     * @return  string
     */
    function str_quotes_to_entities($str)
    {
        return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $str);
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_reduce_slashes'))
{
    /**
     * Reduce Slashes
     *
     * Converts multiple slashes in a string to a single slash,
     * except those found in http://
     *
     * http://www.some-site.com//index.php
     * becomes:
     * http://www.some-site.com/index.php
     *
     * @param   string
     * @return  string
     */
    function str_reduce_slashes($str)
    {
        return preg_replace('#(^|[^:])//+#', '\\1/', $str);
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_reduce'))
{
    /**
     * Reduce Multiples
     * Reduces multiple instances of a particular character.  Example:
     *
     * Fred, Bill,, Joe, Jimmy
     * becomes:
     * Fred, Bill, Joe, Jimmy
     *
     * @param   string
     * @param   string  the character you wish to reduce
     * @param   bool    TRUE/FALSE - whether to trim the character from the beginning/end
     * @return  string
     */
    function str_reduce($str, $character = ',', $trim = FALSE)
    {
        $str = preg_replace('#'.preg_quote($character, '#').'{2,}#', $character, $str);
        return ($trim === TRUE) ? trim($str, $character) : $str;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_random'))
{
    /**
     * Create a Random String
     * Useful for generating passwords or hashes.
     *
     * @param   string  type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
     * @param   int number of characters
     * @return  string
     */
    function str_random($type = 'alnum', $len = 8)
    {
        switch ($type)
        {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type)
                {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_increment'))
{
    /**
     * Add's _1 to a string or increment the ending number to allow _2, _3, etc
     *
     * @param   string  required
     * @param   string  What should the duplicate number be appended with
     * @param   string  Which number should be used for the first dupe increment
     * @return  string
     */
    function str_increment($str, $separator = '_', $first = 1)
    {
        preg_match('/(.+)'.$separator.'([0-9]+)$/', $str, $match);
        return isset($match[2]) ? $match[1].$separator.($match[2] + 1) : $str.$separator.$first;
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists('str_alternate'))
{
    /**
     * Alternator
     * Allows strings to be alternated. See docs...
     *
     * @param   string (as many parameters as needed)
     * @return  string
     */
    function str_alternate($args)
    {
        static $i;
        if (func_num_args() === 0)
        {
            $i = 0;
            return '';
        }
        $args = func_get_args();
        return $args[($i++ % count($args))];
    }
}

if(!function_exists('str_clean'))
{
    /**
     * Translate a text string into a clean equivalent for pretty url formatting.
     *
     * @param string $str string to transform 
     * @param string $delimiter delimiter between words. Defaults to -
     * @return string
     */
    function str_clean($str, $delimiter='-')
    {
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        if(empty($clean)) $clean = date('Ymd');

        return $clean;
    }
}