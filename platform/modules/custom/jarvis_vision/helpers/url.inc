<?php
if ( ! function_exists('urlencode_rfc3986'))
{
    function urlencode_rfc3986($input)
    {
        if (is_array($input))
            return array_map('urlencode_rfc3986', $input);
        else if (is_scalar($input))
            return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
     
        return '';
    }
}

if ( ! function_exists('canonize_query'))
{
    function canonize_query(array $params)
    {
        if (!is_array($params) || 0 === count($params))
            return '';

        // Urlencode both keys and values.
        $keys = urlencode_rfc3986(array_keys($params));
        $values = urlencode_rfc3986(array_values($params));
        $params = array_combine($keys, $values);

        // Parameters are sorted by name, using lexicographical byte value ordering.
        // Ref: Spec: 9.1.1 (1)
        uksort($params, 'strcmp');

        $pairs = array();
        foreach ($params as $parameter => $value)
        {
            $lower_param = strtolower($parameter);

            if (is_array($value))
            {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                natsort($value);
                foreach ($value as $duplicate_value)
                    $pairs[] = $lower_param . '=' . $duplicate_value;
            }
            else
            {
                $pairs[] = $lower_param . '=' . $value;
            }
        }

        if (0 === count($pairs))
            return '';

        return implode('&', $pairs);
    }
}

/**
 * Converts a string to a slug, for use in URLs or CSS classes. This function properly replaces
 *  letters with accents with their non-accented counterparts.
 *
 * @param string The string to convert.
 * @return string The slug.
 */

if ( ! function_exists('string_to_slug'))
{
function string_to_slug($string) {
	$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   return $slug;
}
}