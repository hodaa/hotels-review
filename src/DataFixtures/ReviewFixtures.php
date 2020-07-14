<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\HotelFixture;

class ReviewFixtures extends Fixture
{
    protected $faker;
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        for ($i=0; $i< 100000; $i++) {
            $item = new Review();
            $item->setHotel($this->getReference(HotelFixture::HOTEL_REFERENCE.$this->faker->numberBetween(0, 9)));
            $item->setScore($this->faker->numberBetween(10, 100));
            $item->setComment($this->faker->sentence(10));
            $item->setCreatedAt($this->faker->dateTimeBetween('-2 years', 'now'));
            $manager->persist($item);
            if ($i % 50 == 0) {
                $manager->flush();
                $manager->clear();
            }
        }
    }

}
