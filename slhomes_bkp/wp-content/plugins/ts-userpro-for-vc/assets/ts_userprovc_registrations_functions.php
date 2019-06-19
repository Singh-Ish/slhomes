<?php
    global $VISUAL_COMPOSER_USERPRO;
    
    // Function to check current User Role
    // -----------------------------------
    if (!function_exists('TS_USERPROFORVC_CheckUserRole')){
        function TS_USERPROFORVC_CheckUserRole($roles,$user_id=NULL) {
            // Get user by ID, else get current user
            if ($user_id) {
                $user = get_userdata($user_id);
            } else {
                $user = wp_get_current_user();
            }
            // No user found, return
            if (empty($user)) {
                return FALSE;
            }
            // Append administrator to roles, if necessary
            if (!in_array('administrator', $roles)) {
                $roles[] = 'administrator';
            }
            // Loop through user roles
            foreach ($user->roles as $role) {
                // Does user have role
                if (in_array($role,$roles)) {
                    return TRUE;
                }
            }
            // User not in roles
            return FALSE;
        }
    }
    
    // Function to extract String in between Strings
    // ---------------------------------------------
    if (!function_exists('TS_USERPROFORVC_GetStringBetween')){
        function TS_USERPROFORVC_GetStringBetween ($string, $start, $finish) {
            $string = " " . $string;
            $position = strpos($string, $start);
            if ($position == 0) return "";
            $position += strlen($start);
            $length = strpos($string, $finish, $position) - $position;
            return substr($string, $position, $length);
        }
    }
    
    // Function to retrieve Current Post Type
    // --------------------------------------
    if (!function_exists('TS_USERPROFORVC_GetCurrentPostType')){
        function TS_USERPROFORVC_GetCurrentPostType() {
            global $post, $typenow, $current_screen; 
            if ($post && $post->post_type) {
                // We have a post so we can just get the post type from that
                return $post->post_type;		
            } else if ($typenow) {
                // Check the global $typenow
                return $typenow;
            } else if ($current_screen && $current_screen->post_type) {
                // Check the global $current_screen Object
                return $current_screen->post_type;	
            } else if (isset($_REQUEST['post_type'])) {
                // Check the Post Type QueryString
                return sanitize_key($_REQUEST['post_type']);
            }
            //we do not know the post type!
            return null;
        }
    }

    // Other Utilized Functions
    // ------------------------
    if (!function_exists('TS_USERPROFORVC_CountArrayMatches')){
        function TS_USERPROFORVC_CountArrayMatches(array $arr, $arg, $filterValue) {
            $count = 0;
            foreach ($arr as $elem) {
                if (is_array($elem) && isset($elem[$arg]) && $elem[$arg] == $filterValue) {
                    $count++;
                }
            }
            return $count;
        }
    }
    if (!function_exists('TS_USERPROFORVC_XMLtoArray')){
        function TS_USERPROFORVC_XMLtoArray($XML) {
            $xml_array = array();
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $XML, $vals);
            xml_parser_free($xml_parser);
            $_tmp='';
            foreach ($vals as $xml_elem) {
                $x_tag=$xml_elem['tag'];
                $x_level=$xml_elem['level'];
                $x_type=$xml_elem['type'];
                if ($x_level!=1 && $x_type == 'close') {
                    if (isset($multi_key[$x_tag][$x_level]))
                        $multi_key[$x_tag][$x_level]=1;
                    else
                        $multi_key[$x_tag][$x_level]=0;
                }
                if ($x_level!=1 && $x_type == 'complete') {
                    if ($_tmp==$x_tag)
                        $multi_key[$x_tag][$x_level]=1;
                    $_tmp=$x_tag;
                }
            }
            foreach ($vals as $xml_elem) {
                $x_tag=$xml_elem['tag'];
                $x_level=$xml_elem['level'];
                $x_type=$xml_elem['type'];
                if ($x_type == 'open')
                    $level[$x_level] = $x_tag;
                $start_level = 1;
                $php_stmt = '$xml_array';
                if ($x_type=='close' && $x_level!=1)
                    $multi_key[$x_tag][$x_level]++;
                while ($start_level < $x_level) {
                    $php_stmt .= '[$level['.$start_level.']]';
                    if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                        $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
                    $start_level++;
                }
                $add='';
                if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
                    if (!isset($multi_key2[$x_tag][$x_level]))
                        $multi_key2[$x_tag][$x_level]=0;
                    else
                        $multi_key2[$x_tag][$x_level]++;
                    $add='['.$multi_key2[$x_tag][$x_level].']';
                }
                if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
                    if ($x_type == 'open')
                        $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                    else
                        $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                if (array_key_exists('attributes', $xml_elem)) {
                    if (isset($xml_elem['value'])) {
                        $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                        eval($php_stmt_main);
                    }
                    foreach ($xml_elem['attributes'] as $key=>$value) {
                        $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                        eval($php_stmt_att);
                    }
                }
            }
            return $xml_array;
        }
    }
    if (!function_exists('TS_USERPROFORVC_Memory_Usage')){
        function TS_USERPROFORVC_Memory_Usage($decimals = 2) {
            $result = 0;
            if (function_exists('memory_get_usage')) {
                $result = memory_get_usage() / 1024;
            } else {
                if (function_exists('exec')) {
                    $output = array();
                    if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN') {
                        exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                        $result = preg_replace('/[\D]/', '', $output[5]);
                    } else {
                        exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);
                        $output = explode('  ', $output[0]);
                        $result = $output[1];
                    }
                }
            }
            return number_format(intval($result) / 1024, $decimals, '.', '');
        }
    }
    if (!function_exists('TS_USERPROFORVC_LetToNumber')){
        function TS_USERPROFORVC_LetToNumber( $v ) {
            $l   = substr( $v, -1 );
            $ret = substr( $v, 0, -1 );
            switch ( strtoupper( $l ) ) {
                case 'P': // fall-through
                case 'T': // fall-through
                case 'G': // fall-through
                case 'M': // fall-through
                case 'K': // fall-through
                    $ret *= 1024;
                    break;
                default:
                    break;
            }
            return $ret;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CleanNumberData')){
        function TS_USERPROFORVC_CleanNumberData($a) {
            if(is_numeric($a)) {
                $a = preg_replace('/[^0-9,]/s', '', $a);
            }
            return $a;
        }
    }
    if (!function_exists('TS_USERPROFORVC_IsBase64Encoded')){
        function TS_USERPROFORVC_IsBase64Encoded($s){
            // Check if there are valid base64 characters
            if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
            // Decode the string in strict mode and check the results
            $decoded = base64_decode($s, true);
            if (false === $decoded) return false;
            // Encode the string again
            if (base64_encode($decoded) != $s) return false;
            return true;
        }
    }
    if (!function_exists('TS_USERPROFORVC_FormatSizeUnits')){
        function TS_USERPROFORVC_FormatSizeUnits($bytes) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' Bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' Byte';
            } else {
                $bytes = '0 Bytes';
            }
            return $bytes;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CurrentPageURL')){
        function TS_USERPROFORVC_CurrentPageURL() {
            $pageURL = 'http';
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CurrentPageName')){
        function TS_USERPROFORVC_CurrentPageName() {
            return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        }
    }
    if (!function_exists('TS_USERPROFORVC_PluginIsActive')){
        function TS_USERPROFORVC_PluginIsActive($plugin_path) {
            $return_var = in_array($plugin_path, apply_filters('active_plugins', get_option('active_plugins')));
            return $return_var;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CheckShortcode')){
        function TS_USERPROFORVC_CheckShortcode($shortcode = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no short code was provided, return false
            if (!$shortcode) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '[' . $shortcode) !== false) {
                // we have found the short code
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CheckString')){
        function TS_USERPROFORVC_CheckString($string = '') {
            $post_to_check = get_post(get_the_ID());
            // false because we have to search through the post content first
            $found = false;
            // if no string was provided, return false
            if (!$string) {
                return $found;
            }
            // check the post content for the short code
            if (stripos($post_to_check->post_content, '' . $string) !== false) {
                // we have found the string
                $found = true;
            }
            // return our final results
            return $found;
        }
    }
    if (!function_exists('TS_USERPROFORVC_DeleteOptionsPrefixed')){
        function TS_USERPROFORVC_DeleteOptionsPrefixed($prefix) {
            global $wpdb;
            $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
        }
    }
    if (!function_exists('TS_USERPROFORVC_SortMultiArray')){
        function TS_USERPROFORVC_SortMultiArray(&$array, $key) {
            foreach($array as &$value) {
                $value['__________'] = $value[$key];
            }
            /* Note, if your functions are inside of a class, use: 
                usort($array, array("My_Class", 'TS_USERPROFORVC_SortByDummyKey'));
            */
            usort($array, 'TS_USERPROFORVC_SortByDummyKey');
            foreach($array as &$value) {   // removes the dummy key from your array
                unset($value['__________']);
            }
            return $array;
        }
    }
    if (!function_exists('TS_USERPROFORVC_SortByDummyKey')){
        function TS_USERPROFORVC_SortByDummyKey($a, $b) {
            if($a['__________'] == $b['__________']) return 0;
            if($a['__________'] < $b['__________']) return -1;
            return 1;
        }
    }
    if (!function_exists('TS_USERPROFORVC_CaseInsensitiveSort')){
        function TS_USERPROFORVC_CaseInsensitiveSort($a,$b) { 
            return strtolower($b) < strtolower($a); 
        }
    }
    if (!function_exists('TS_USERPROFORVC_getRemoteFile')){
        function TS_USERPROFORVC_getRemoteFile($url) {
            // get the host name and url path
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'];
            if (isset($parsedUrl['path'])) {
                $path = $parsedUrl['path'];
            } else {
                // the url is pointing to the host like http://www.mysite.com
                $path = '/';
            }
            if (isset($parsedUrl['query'])) {
                $path .= '?' . $parsedUrl['query'];
            }
            if (isset($parsedUrl['port'])) {
                $port = $parsedUrl['port'];
            } else {
                // most sites use port 80
                $port = '80';
            }
            $timeout = 10;
            $response = '';
            // connect to the remote server
            $fp = @fsockopen($host, '80', $errno, $errstr, $timeout );
            if( !$fp ) {
                echo "Cannot retrieve $url";
            } else {
                // send the necessary headers to get the file
                fputs($fp, "GET $path HTTP/1.0\r\n" .
                "Host: $host\r\n" .
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                "Accept: */*\r\n" .
                "Accept-Language: en-us,en;q=0.5\r\n" .
                "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                "Keep-Alive: 300\r\n" .
                "Connection: keep-alive\r\n" .
                "Referer: http://$host\r\n\r\n");
                // retrieve the response from the remote server
                while ( $line = fread( $fp, 4096 ) ) {
                    $response .= $line;
                }
                fclose( $fp );
                // strip the headers
                $pos = strpos($response, "\r\n\r\n");
                $response = substr($response, $pos + 4);
            }
            // return the file content
            return $response;
        }
    }
    if (!function_exists('TS_USERPROFORVC_retrieveExternalData')){
        function TS_USERPROFORVC_retrieveExternalData($url){
            if (function_exists('curl_init')) {
                //echo 'Using CURL';
                // initialize a new curl resource
                $ch = curl_init();
                $timeout = 5;
                // set the url to fetch
                curl_setopt($ch, CURLOPT_URL, $url);
                // don't give me the headers just the content
                curl_setopt($ch, CURLOPT_HEADER, 0);
                // return the value instead of printing the response to browser
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // set error timeout
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                // use a user agent to mimic a browser
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
                $content = curl_exec($ch);
                // remember to always close the session and free all resources
                curl_close($ch);
            } else if (ini_get('allow_url_fopen') == '1') {
                //echo 'Using file_get_contents';
                $content = @file_get_contents($url);
                if ($content !== false) {
                    $content = $content;
                } else {
                    $content = '';
                }
            } else {
                //echo 'Using Others';
                $content = TS_USERPROFORVC_getRemoteFile($url);
            }
            return $content;
        }
    }
    if (!function_exists('TS_USERPROFORVC_cURLcheckBasicFunctions')){
        function TS_USERPROFORVC_cURLcheckBasicFunctions() {
            if( !function_exists("curl_init") &&
                !function_exists("curl_setopt") &&
                !function_exists("curl_exec") &&
                !function_exists("curl_close") ) return false;
            else return true;
        }
    }
    if (!function_exists('TS_USERPROFORVC_checkValidURL')){
        function TS_USERPROFORVC_checkValidURL($url) {
            if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
                return true;
            } else {
                return false;
            }
        }
    }
    if (!function_exists('TS_USERPROFORVC_makeValidURL')){
        function TS_USERPROFORVC_makeValidURL($url) {
            if (preg_match("~^(?:f|ht)tps?://~i", $url)) {
                return $url;
            } else {
                return 'http://' . $url;
            }
        }
    }
    if (!function_exists('TS_USERPROFORVC_numberOfDecimals')){
        function TS_USERPROFORVC_numberOfDecimals($value) {
            if ((int)$value == $value) {
                return 0;
            } else if (!is_numeric($value)) {
                // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
                return false;
            }
            return strlen($value) - strrpos($value, '.') - 1;
        }
    }
?>