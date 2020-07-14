<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HotelFixture extends Fixture
{
    protected $faker;

    const HOTEL_REFERENCE = 'hotel';

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i=0; $i<10; $i++) {
            $item = new Hotel();
            $item->setName($this->faker->streetName);
            $manager->persist($item);
            $this->addReference(self::HOTEL_REFERENCE.$i, $item);
        }


        $manager->flush();
    }
}
