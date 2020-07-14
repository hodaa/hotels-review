<?php

namespace App\Services;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidatorService
{

    public function validate(array $data) :array
    {
        $validationMessages = [];
        if (!isset($data['hotel_id'])) {
            return  ["status"=>"fail","message"=>"hotel_id is required "];
        }
        if (!isset($data['start_date'])) {
            return  ["status"=>"fail","message"=>"start_date is required "];
        }
        if (!isset($data['end_date'])) {
            return  ["status"=>"fail","message"=>"end_date is required "];
        }

        $constraints = new Collection([
            'hotel_id' => [new NotBlank(['message' => "hotel_id should not be blank"]),new Required()],
            'start_date' => [new NotBlank(),new Date(['message' => "start_date is not a valid date"])],
            'end_date' => [
                new NotBlank(['message' => "end_date should not be blank"]),
                new Date(['message' => "end_date is not a valid date"]),
            ],

        ]);
        $validator = Validation::createValidator();

        $violations = $validator->validate($data, $constraints);

        if (!empty($violations)) {
            foreach ($violations as $validation) {
                $validationMessages[] = $validation->getMessage();
            }
        }


        return $validationMessages;
    }
}
