<?php


namespace Velstack\Mnotify\Traits;


trait Commands
{
    private static function bindParamsToEndPoint(string $endpoint): string
    {
        return $endpoint."/?key=".self::apiKey();
    }

}
