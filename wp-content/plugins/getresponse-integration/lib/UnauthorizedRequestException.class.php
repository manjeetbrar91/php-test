<?php

/**
 * Class UnauthorizedRequestException
 */
class UnauthorizedRequestException extends Exception
{
    /**
     * @param string $statusCode
     * @param string $description
     * @return UnauthorizedRequestException
     */
    public static function createForInvalidResponseStatus($statusCode, $description)
    {
        return new self("Invalid response status: " . $statusCode . ", " . $description);
    }
}
