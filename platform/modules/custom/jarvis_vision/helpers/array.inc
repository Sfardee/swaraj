<?php
/****************************************************************************************************
 Array Helpers
 Ascii only
 Combined from Codeigniter, Yii and Laravel
****************************************************************************************************/


// ------------------------------------------------------------------------
if (!function_exists('to_array')) 
{
    /**
     * Converts an object or an array of objects into an array.
     * @param object|array $object the object to be converted into an array
     * @param array $properties a mapping from object class names to the properties that need to put into the resulting arrays.
     * The properties specified for each class is an array of the following format:
     *
     * ~~~
     * [
     *     'app\models\Post' => [
     *         'id',
     *         'title',
     *         // the key name in array result => property name
     *         'createTime' => 'created_at',
     *         // the key name in array result => anonymous function
     *         'length' => function ($post) {
     *             return strlen($post->content);
     *         },
     *     ],
     * ]
     * ~~~
     *
     * The result of `ArrayHelper::toArray($post, $properties)` could be like the following:
     *
     * ~~~
     * [
     *     'id' => 123,
     *     'title' => 'test',
     *     'createTime' => '2013-01-01 12:00AM',
     *     'length' => 301,
     * ]
     * ~~~
     *
     * @param boolean $recursive whether to recursively converts properties which are objects into arrays.
     * @return array the array representation of the object
     */
    function to_array($object, $properties = [], $recursive = true)
    {
    	if (empty($object))
    		return array();
    	
        if (is_array($object)) {
            if ($recursive) {
                foreach ($object as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $object[$key] = to_array($value, $properties, true);
                    }
                }
            }
            return $object;
        } elseif (is_object($object)) {
            if (!empty($properties)) {
                $className = get_class($object);
                if (!empty($properties[$className])) {
                    $result = [];
                    foreach ($properties[$className] as $key => $name) {
                        if (is_int($key)) {
                            $result[$name] = $object->$name;
                        } else {
                            $result[$key] = array_get($object, $name);
                        }
                    }
                    return $recursive ? to_array($result, $properties) : $result;
                }
            }

            $result = [];
            foreach ($object as $key => $value) {
                $result[$key] = $value;
            }

            return $recursive ? to_array($result) : $result;
        } else {
            return [$object];
        }
    }
}



// ------------------------------------------------------------------------
if (!function_exists('is_associative')) 
{
    /**
     * Returns a value indicating whether the given array is an associative array.
     *
     * An array is associative if all its keys are strings. If `$allStrings` is false,
     * then an array will be treated as associative if at least one of its keys is a string.
     *
     * Note that an empty array will NOT be considered associative.
     *
     * @param array $array the array being checked
     * @param boolean $allStrings whether the array keys must be all strings in order for
     * the array to be treated as associative.
     * @return boolean whether the array is associative
     */
    function is_associative($array, $allStrings = true)
    {
        if (!is_array($array) || empty($array)) {
            return false;
        }
        if ($allStrings) {
            foreach ($array as $key => $value) {
                if (!is_string($key)) {
                    return false;
                }
            }
            return true;
        } else {
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    return true;
                }
            }
            return false;
        }
    }
}


// ------------------------------------------------------------------------
if (!function_exists('is_indexed')) 
{
    /**
     * Returns a value indicating whether the given array is an indexed array.
     *
     * An array is indexed if all its keys are integers. If `$consecutive` is true,
     * then the array keys must be a consecutive sequence starting from 0.
     *
     * Note that an empty array will be considered indexed.
     *
     * @param array $array the array being checked
     * @param boolean $consecutive whether the array keys must be a consecutive sequence
     * in order for the array to be treated as indexed.
     * @return boolean whether the array is associative
     */
    function is_indexed($array, $consecutive = false)
    {
        if (!is_array($array)) {
            return false;
        }
        if (empty($array)) {
            return true;
        }
        if ($consecutive) {
            return array_keys($array) === range(0, count($array) - 1);
        } else {
            foreach ($array as $key => $value) {
                if (!is_integer($key)) {
                    return false;
                }
            }
            return true;
        }
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_dot')) 
{
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array   $array
     * @param  string  $prepend
     * @return array
     */
    function array_dot($array, $prepend = '')
    {
        $results = array();

        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $results = array_merge($results, array_dot($value, $prepend.$key.'.'));
            }
            else
            {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_except')) 
{
    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_except($array, $keys)
    {
        return array_diff_key($array, array_flip((array) $keys));
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_fetch')) 
{
    /**
     * Fetch a flattened array of a nested array element.
     *
     * @param  array   $array
     * @param  string  $key
     * @return array
     */
    function array_fetch($array, $key)
    {
        foreach (explode('.', $key) as $segment)
        {
            $results = array();

            foreach ($array as $value)
            {
                $value = (array) $value;

                $results[] = $value[$segment];
            }

            $array = array_values($results);
        }

        return array_values($results);
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_flatten')) 
{
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @return array
     */
    function array_flatten($array)
    {
        $return = array();

        array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; });

        return $return;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_forget')) 
{
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    function array_forget(&$array, $keys)
    {
        $original =& $array;

        foreach ((array) $keys as $key)
        {
            $parts = explode('.', $key);

            while (count($parts) > 1)
            {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part]))
                {
                    $array =& $array[$part];
                }
            }

            unset($array[array_shift($parts)]);

            // clean up after each pass
            $array =& $original;
        }
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_get')) 
{
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key=null, $default = null)
    {
        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_array($array) || ! array_key_exists($segment, $array))
            {
                return array_get($default);
            }

            $array = $array[$segment];
        }

        return $array;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_only')) 
{
    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_column')) 
{
    /**
     * Extracts an column of values from a multi-dimensional array based on the dot notation path
     *
     * @param  array   $array
     * @param  string  $value
     * @param  string  $key
     * @return array
     */
    function array_column($array, $key)
    {
        $results = array();

        foreach ($array as $item)
            $results[] = array_get($item, $key);

        return $results;
    }
}



// ------------------------------------------------------------------------
if (!function_exists('array_index')) 
{
    /**
     * Indexes an array according to a specified key.
     * The input array should be multidimensional or an array of objects.
     *
     * The key can be a key name of the sub-array, a property name of object, or an anonymous
     * function which returns the key value given an array element.
     *
     * If a key value is null, the corresponding array element will be discarded and not put in the result.
     *
     * For example,
     *
     * ~~~
     * $array = [
     *     ['id' => '123', 'data' => 'abc'],
     *     ['id' => '345', 'data' => 'def'],
     * ];
     * $result = ArrayHelper::index($array, 'id');
     * // the result is:
     * // [
     * //     '123' => ['id' => '123', 'data' => 'abc'],
     * //     '345' => ['id' => '345', 'data' => 'def'],
     * // ]
     *
     * // using anonymous function
     * $result = ArrayHelper::index($array, function ($element) {
     *     return $element['id'];
     * });
     * ~~~
     *
     * @param array $array the array that needs to be indexed
     * @param string|\Closure $key the column name or anonymous function whose result will be used to index the array
     * @return array the indexed array
     */
    function array_index($array, $key)
    {
        $result = [];
        foreach ($array as $element) {
            $value = array_get($element, $key);
            $result[$value] = $element;
        }
        return $result;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_map')) 
{
    /**
     * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
     * The `$from` and `$to` parameters specify the key names or property names to set up the map.
     * Optionally, one can further group the map according to a grouping field `$group`.
     *
     * For example,
     *
     * ~~~
     * $array = [
     *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
     *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
     *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
     * ];
     *
     * $result = ArrayHelper::map($array, 'id', 'name');
     * // the result is:
     * // [
     * //     '123' => 'aaa',
     * //     '124' => 'bbb',
     * //     '345' => 'ccc',
     * // ]
     *
     * $result = ArrayHelper::map($array, 'id', 'name', 'class');
     * // the result is:
     * // [
     * //     'x' => [
     * //         '123' => 'aaa',
     * //         '124' => 'bbb',
     * //     ],
     * //     'y' => [
     * //         '345' => 'ccc',
     * //     ],
     * // ]
     * ~~~
     *
     * @param array $array
     * @param string|\Closure $from
     * @param string|\Closure $to
     * @param string|\Closure $group
     * @return array
     */
    function array_map($array, $from, $to, $group = null)
    {
        $result = [];
        foreach ($array as $element) {
            $key = array_get($element, $from);
            $value = array_get($element, $to);
            if ($group !== null) {
                $result[array_get($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_set')) 
{
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = array();
            }

            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}


// ------------------------------------------------------------------------
if (!function_exists('array_window')) 
{
    /**
     * Splits an array into a tree
     * Each argument defines the size of the windowin the next higher level
     * E.g. array_window($arr, 4, 3) will create a number of branches, with 3 windows having a size of 4 each. 
     * The node must all have a size variable otherwise the size is assumed to be 1.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_window($array)
    {
        $args = array_slice(func_get_args(), 1);
        $windows = [[]];

        if (count($args) == 1) {
        	$size = $args[0];
        	$currentsize = 0;
        	$index = 0;
        	foreach ($array as $elm) {
        		// If dealing with objects then we use the size, otherwise lets use each array as a size of 1
        		$nsize = (is_object($elm) && @$elm->size) ? $elm->size : 1;

        		// If it doesnt fit in the current window, create a new window and reset the size counter
        		if ($currentsize + $nsize > $size) {
					$index++;
					$windows[$index] = array();
        			$currentsize = 0;
        		}

        		$windows[$index][] = $elm;
        		$currentsize += $nsize;
        	}
        } else {
        	$size = array_pop($args);
        	array_unshift($args, $array);
        	return array_window(call_user_func_array('array_window', $args), $size);
        }

        return $windows;
    }
}

// ------------------------------------------------------------------------
if (!function_exists('empty_recursive'))
{
	/**
	 * Check an array is empty recursively
	 * 
	 *
	 * @param  array   $array
	 * @return boolean
	 */
	function empty_recursive($v) {
		$empty = true;
	
		if (is_array($v) && count($v) > 0) {
			foreach ($v as $vv)
				$empty = $empty && empty_recursive($vv);
		} else {
			$empty = empty($v);
		}
	
		return $empty;
	}
}

// ------------------------------------------------------------------------
if (!function_exists('echopre'))
{
    /**
     * Check an array is empty recursively
     * 
     *
     * @param  array   $array
     * @return none
     */
    function echopre($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}