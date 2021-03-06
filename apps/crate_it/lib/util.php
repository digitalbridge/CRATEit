<?php

namespace OCA\crate_it\lib;

use OCP\Template;

class util
{
    public static function renderTemplate($template, $params)
    {
        // TODO: Use util method to get appName
        $template = new Template('crate_it', $template);
        foreach ($params as $key => $value) {
            $template->assign($key, $value);
        }
        return $template->fetchPage();
    }

    public static function getTimestamp($format="YmdHis")
    {
        date_default_timezone_set('Australia/Sydney');
        $timestamp = date($format);
        return $timestamp;
    }

    public static function getConfig()
    {
        $configFile = Util::joinPaths(Util::getDataPath(), 'CRATEit_config.json');
        $config = null; // Allows tests to work
        // TODO: Throw a better error when there is invalid json or the config is not found
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
        }
        return $config;
    }

    public static function getUserPath()
    {
        $userId = \OC::$server->getUserSession()->getUser()->getUID();
        return Util::joinPaths(Util::getDataPath(), $userId);
    }

    public static function getPathByuser($uid)
    {
        return Util::joinPaths(Util::getDataPath(), $uid);
    }

    public static function getDataPath()
    {
        return \OC::$server->getConfig()->getSystemValue('datadirectory');
    }

    public static function getTempPath()
    {
        return \OC::$server->getTempManager()->getTemporaryFolder();
    }

    public static function getpublishPath()
    {
       return Util::getConfig()['crate_path'] . '/publish';
    }

    public static function joinPaths()
    {
        $paths = array();
        foreach (func_get_args() as $arg) {
            if ($arg !== '') {
                $paths[] = $arg;
            }
        }
        return preg_replace('#/+#', '/', join('/', $paths));
    }

    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function prettyPrint($json)
    {
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = null;
        $json_length = strlen($json);

        for ($i = 0; $i < $json_length; $i++) {
            $char = $json[$i];
            $new_line_level = null;
            $post = "";
            if ($ends_line_level !== null) {
                $new_line_level = $ends_line_level;
                $ends_line_level = null;
            }
            if ($in_escape) {
                $in_escape = false;
            } else {
                if ($char === '"') {
                    $in_quotes = !$in_quotes;
                } else {
                    if (!$in_quotes) {
                        switch ($char) {
                            case '}':
                            case ']':
                                $level--;
                                $ends_line_level = null;
                                $new_line_level = $level;
                                break;

                            case '{':
                            case '[':
                                $level++;
                            case ',':
                                $ends_line_level = $level;
                                break;

                            case ':':
                                $post = " ";
                                break;

                            case " ":
                            case "\t":
                            case "\n":
                            case "\r":
                                $char = "";
                                $ends_line_level = $new_line_level;
                                $new_line_level = null;
                                break;
                        }
                    } else {
                        if ($char === '\\') {
                            $in_escape = true;
                        }
                    }
                }
            }
            if ($new_line_level !== null) {
                $result .= "\n".str_repeat("  ", $new_line_level);
            }
            $result .= $char.$post;
        }
        return $result;
    }
}
