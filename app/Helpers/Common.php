<?php

use App\Models\GeneralSetting;

/**
 * Get the value of a setting
 */

if (!function_exists('settings')) {
    function settings()
    {
        $settings = GeneralSetting::take(1)->first();
        if (! is_null($settings)) {
            return $settings;
        }
    }
}
