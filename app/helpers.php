<?php

if (!function_exists('randomNumber'))
{
    /**
     * Returns a random number with the given length
     * */
    function randomNumber($length = 10)
    {
        $number = '';
        for ($i = 0; $i < $length; $i ++)
        {
            $number .= rand(0, 9);
        }
        return $number;
    }
}
