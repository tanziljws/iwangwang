<?php

if (!function_exists('https_asset')) {
    /**
     * Generate an asset URL with HTTPS if request is HTTPS
     */
    function https_asset($path)
    {
        $url = asset($path);
        
        // If request is HTTPS, force HTTPS for asset URL
        if (request()->getScheme() === 'https' || 
            request()->header('X-Forwarded-Proto') === 'https' ||
            app()->environment('production')) {
            $url = str_replace('http://', 'https://', $url);
        }
        
        return $url;
    }
}

