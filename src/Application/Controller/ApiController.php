<?php

namespace App\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiController extends AbstractController
{
    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function response($data, $headers = []) : JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithErrors($message, $headers = [])
    {
        return $this->response(['message' => $message], $headers);
    }


    /**
     * @param $message
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithSuccess($message, $headers = [])
    {
       return $this->response(['message' => $message], $headers);
    }


    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param mixed $exception
     *
     * @return JsonResponse
     */
    public function respondWithExceptionError($exception)
    {
        if (is_string($exception)) {
            return $this->setStatusCode(422)->respondWithErrors($exception);
        }

        if ($exception instanceof ValidationFailedException) {
            return $this->respondWithValidationErrors($exception->getViolations(), 'Problema na validação dos dados.');
        }

        if ($exception instanceof HandlerFailedException && isset($exception->getNestedExceptions()[0])) {
            if ($exception->getNestedExceptions()[0] instanceof ValidationFailedException) {
                return $this->respondWithValidationErrors($exception->getNestedExceptions()[0]->getViolations(), 'Problema na validação dos dados.');
            }
        }

        return $this->setStatusCode(422)->respondWithErrors($exception->getMessage());

    }

    /**
     * Returns a 400 Bad Request
     *
     * @param ConstraintViolationListInterface $errors
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondWithValidationErrors(ConstraintViolationListInterface $errors, $message = 'Campos obrigatórios não preenchidos')
    {
        $response['message'] = $message;
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $response['errors'][$error->getPropertyPath()] = $error->getMessage();
        }

        return $this->setStatusCode(400)->response($response);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->response($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return mixed
     * this method allows us to accept JSON payloads in POST requests
     * since Symfony 4 doesn’t handle that automatically:
     */
    protected function parseJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * @param $statusCode
     * @return int
     */
    protected function getStatusCodeError($statusCode)
    {
        return $statusCode === 0 ? 400 : $statusCode;
    }
}