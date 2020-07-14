<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Services\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class HotelController extends AbstractController
{
    private $validatorService;
    public function __construct(ValidatorService $validatorService)
    {
        $this->validatorService = $validatorService;
    }

    /**
     * @return Response
     *
     */

    public function index(Request $request): JsonResponse
    {
        $violations = $this->validatorService->validate($request->query->all());
        if (count($violations) > 0) {
            return new JsonResponse(['status' => 'fail',
                'validations' => $violations], Response::HTTP_BAD_REQUEST);

        }

        $result = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->getHotelsReviews($request->get('hotel_id'), $request->get('start_date'), $request->get('end_date'));


        return  new JsonResponse($result) ;
    }
}
