<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Exceptions;

use Exception;

/**
 * Base exception class for ZaloBot-related errors.
 *
 * This exception extends the native PHP Exception class and provides
 * additional context such as the HTTP status code and the raw response
 * (if available) from the Zalo API.
 */
class BaseException extends Exception
{
    /**
     * The HTTP status code associated with the error.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * The raw response data returned from the API, if any.
     *
     * @var array|null
     */
    protected ?array $response;

    /**
     * Create a new BaseException instance.
     *
     * @param string     $message     The error message.
     * @param int        $statusCode  The HTTP status code (default: 0).
     * @param array|null $response    The raw API response, if available.
     */
    public function __construct(string $message, int $statusCode = 0, ?array $response = null)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->response = $response;
    }

    /**
     * Get the HTTP status code associated with the exception.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the raw API response associated with the exception, if any.
     *
     * @return array|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }
}
