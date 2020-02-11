<?php

namespace App\DataFixtures;

use App\Entity\Disease;
use Doctrine\Common\Persistence\ObjectManager;

class DiseaseFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        ini_set("memory_limit", -1);
        $this->createMany(Disease::class, 10000, function(Disease $disease, $count) {
            $disease->setName($this->faker->sentence(1));
        });

        $manager->flush();
    }
}
