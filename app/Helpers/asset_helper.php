<?php

/**
 * Generate a cache-busted asset URL.
 * Appends the file's last-modified timestamp as a query parameter.
 *
 * Usage: asset_url('js/RawMaterial.js')
 *        asset_url('css/style.css')
 */
if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $filePath = FCPATH . $path;
        $version = file_exists($filePath) ? filemtime($filePath) : '1';
        return base_url($path) . '?v=' . $version;
    }
}

?>