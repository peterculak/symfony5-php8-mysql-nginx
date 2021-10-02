<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function load(ObjectManager $manager)
    {
        $row = 0;
        if (($handle = fopen(__DIR__ . '/../../data/attributes.csv', "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($row) {
                    $this->em->getConnection()->insert('attribute', [
                        'id' => (int)$data[0],
                        'name' => (string)$data[1],
                    ]);
                }
                $row++;
            }
            fclose($handle);
        }

        $row = 0;
        if (($handle = fopen(__DIR__ . '/../../data/securities.csv', "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($row) {
                    $this->em->getConnection()->insert('security', [
                        'id' => (int)$data[0],
                        'symbol' => (string)$data[1],
                    ]);
                }
                $row++;
            }
            fclose($handle);
        }

        $row = 0;
        if (($handle = fopen(__DIR__ . '/../../data/facts.csv', "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($row) {
                    $this->em->getConnection()->insert('fact', [
                        'security_id' => (int)$data[0],
                        'attribute_id' => (int)$data[1],
                        'value' => (float)$data[2],
                    ]);
                }
                $row++;
            }
            fclose($handle);
        }

        $manager->flush();
    }
}
