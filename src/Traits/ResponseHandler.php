<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponseHandler
{
    /**
     * @param $data
     * @return JsonResponse
     */
    public function success($data)
    {
        $result= [
            'status' => 'success',
            'data' => $data,

        ];
        return  new JsonResponse($result, Response::HTTP_OK) ;
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    public function fail($errors)
    {
        $result= [
            'status' => 'fail',
            'validations' => $errors,

            ];
        return  new JsonResponse($result, Response::HTTP_BAD_REQUEST) ;
    }
}
