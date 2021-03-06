<?php

namespace SymfonyTools\Services;

class Exception
{

    /**
     * Genera un Exception en base al mensaje y el código
     * @param string $message
     * @param int $code
     * @throws \Exception
     */
    public static function error(string $message, $code = 0)
    {

        if (empty($code) || $code < 100) {
            $code = 500;
        }

        throw new \Exception($message, $code);

    }

    /**
     * Genera una Exception por falta de privilegios del rol
     */
    public static function errorNotAuthorized()
    {
        self::error('you do not have permission for this action', 403);
    }

}