<?php

namespace App\DataFixtures;

use App\Entity\Disease;
use App\Entity\Drug;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DrugFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        ini_set("memory_limit", -1);
        $this->createMany(Drug::class, 30000, function(Drug $drug, $count) {
            $drug->setName($this->faker->sentence(1));
            $diseases = $this->getRandomReferences(Disease::class, 3);
            foreach ($diseases as $disease) {
                $drug->addDisease($disease);
            }
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DiseaseFixtures::class,
        ];
    }
}
