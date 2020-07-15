<?php

namespace App\Tests;

use App\Entity\Hotel;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiTest extends WebTestCase
{
    private KernelBrowser $client;


    public function setUp() :void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->truncateEntities([
            Review::class,
            Hotel::class,
        ]);
    }

    /**
     * @return mixed
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * delete all data from test DB for each  test
     * @param array $entities
     */
    private function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * @return Hotel
     */
    private function createTestHotel()
    {
        $hotel =new Hotel();
        $hotel->setName("Heliton");
        $this->getEntityManager()->persist($hotel);
        $this->getEntityManager()->flush();
        return $hotel;
    }

    private function createTestReviews($hotel, $score, $date)
    {
        $item = new Review();
        $item->setHotel($hotel);
        $item->setScore($score);
        $item->setComment("I like it");
        $item->setCreatedAt($date);
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }


    public function testEmptyData()
    {
        $this->client->request('GET', 'api/v1/hotel-reviews?hotel_id=1&start_date=2019-03-01&end_date=2019-04-01');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testValidation()
    {
        $this->client->request('GET', 'api/v1/hotel-reviews', [], []);
        $result= json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($result["status"], "fail");
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testForDaysAverage()
    {

        $hotel = $this->createTestHotel();
        $this->createTestReviews($hotel, 55, new \DateTime('2020-03-02'));
        $this->createTestReviews($hotel, 35, new \DateTime('2020-03-03'));

        $this->client->request('GET', 'api/v1/hotel-reviews?hotel_id=1&start_date=2020-03-01&end_date=2020-03-20');
        $result= json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(2, $result);
    }
    public function testForWeeksAverage()
    {
        $date = new \DateTime('2020-03-01');
        $hotel = $this->createTestHotel();
        $this->createTestReviews($hotel, 55, $date);
        $this->createTestReviews($hotel, 35, $date);

        $this->client->request('GET', 'api/v1/hotel-reviews?hotel_id=1&start_date=2020-02-01&end_date=2020-04-20');
        $result= json_decode($this->client->getResponse()->getContent(), true);



            $this->assertEquals(2, $result["data"][0]["review-count"]);
        $this->assertEquals(45, $result["data"][0]["average-score"]);
        $this->assertEquals((int)$date->format("W"), $result["data"][0]["date-group"]);
    }


    public function testForMonthAverage()
    {
        $hotel = $this->createTestHotel();
        $this->createTestReviews($hotel, 55, new \DateTime('2019-03-01'));
        $this->createTestReviews($hotel, 35, new \DateTime('2019-03-02'));
        $this->createTestReviews($hotel, 95, new \DateTime('2019-03-03'));
        $this->createTestReviews($hotel, 50, new \DateTime('2019-04-03'));

        $this->client->request('GET', 'api/v1/hotel-reviews?hotel_id=1&start_date=2019-01-01&end_date=2019-12-01');
        $result= json_decode($this->client->getResponse()->getContent(), true);


        $this->assertEquals(3, $result["data"][0]["review-count"]);
        $this->assertEquals(61, $result["data"][0]["average-score"]);
        $this->assertEquals(3, $result["data"][0]["date-group"]);

        $this->assertEquals(1, $result["data"][1]["review-count"]);
        $this->assertEquals(50, $result["data"][1]["average-score"]);
        $this->assertEquals(4, $result["data"][1]["date-group"]);
    }
}
