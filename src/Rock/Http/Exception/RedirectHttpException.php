<?php

namespace Rock\Http\Exception;


class RedirectHttpException extends HttpException
{
    public function __construct($url, $statusCode = 302, \Exception $previous = null, $code = 0)
    {
        parent::__construct($statusCode, null, $previous, array(
            'Location' => $url,
        ), $code);
    }
}
