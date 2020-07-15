<?php

namespace  App\DTO;

final class HotelReviewsDTO
{

    /**
     * @param array $data
     * @return array
     */
    public static function fromData(array $data) :array
    {
        foreach ($data as $item) {
            $result[]=[
                'review-count' =>$item["review_count"],
                'average-score' =>$item["score_avg"],
                'date-group' => $item["date_group"],

            ];
        }
        return $result;
    }
}
