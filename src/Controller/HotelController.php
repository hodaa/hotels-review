<?php

namespace App\Controller;

use App\DTO\HotelReviewsDTO;
use App\Entity\Hotel;
use App\Services\ValidatorService;
use App\Traits\ResponseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HotelController extends AbstractController
{
    use ResponseHandler;

    private $validatorService;
    public function __construct(ValidatorService $validatorService)
    {
        $this->validatorService = $validatorService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function index(Request $request): JsonResponse
    {
        $violations = $this->validatorService->validate($request->query->all());

        if (count($violations) > 0) {
            return  $this->fail($violations) ;
        }

        $data = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->getHotelsReviews($request->get('hotel_id'), $request->get('start_date'), $request->get('end_date'));

        return  $this->success(HotelReviewsDTO::fromData($data)) ;
    }
}
