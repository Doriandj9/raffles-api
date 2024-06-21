<?php

use  App\Helpers\SanitizerHelper;

if (!function_exists('sanitize_data')) {
    /**
     * @param $value
     * @return mixed
     */
    function sanitize_data($value)
    {
        return resolve(SanitizerHelper::class)->filterData($value);
    }
}
