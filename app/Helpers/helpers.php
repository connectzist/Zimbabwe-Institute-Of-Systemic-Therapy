<?php

if (!function_exists('getPageName')) {
    /**
     * 
     *
     * @param string $fileName
     * @return string
     */
    function getPageName($fileName)
    {
        return basename($fileName, '.blade.php');
    }
}
