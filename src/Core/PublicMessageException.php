<?php

namespace App\Core;

use Exception;

class PublicMessageException extends Exception
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
        parent::__construct($response->getMsg() ?: 'Es ist ein Fehler aufgetreten');
    }

    /**
     * Get the Response object associated with this exception.
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
