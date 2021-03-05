<?php

namespace App\Domain\Exception;

use LogicException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DomainException extends LogicException
{
    /**
     * @param null $message
     * @param int $code
     * @return static
     */
    public static function factory($message = null, $code = 0)
    {
        return new static($message, $code);
    }

    /**
     * @param ResponseInterface $response
     * @return DomainException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public static function factoryByResponse(ResponseInterface $response)
    {
        $message = null;
        $code = $response->getStatusCode();
        try {
            $json = json_decode($response->getBody()->getContents());
            if (property_exists($json, 'message')) {
                $message = $json->message;
            } else {
                $message = $response->getReasonPhrase();
            }
        } catch (\Exception $e) {
            $message = $response->getBody()->getContents();
        }
        return new static($message, $code);
    }
}