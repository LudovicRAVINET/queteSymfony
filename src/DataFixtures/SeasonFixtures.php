<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<8; $i++) {
            $season = new Season();     
            $season->setNumber($i);    
            $season->setYear('200' . $i);    
            $season->setDescription('Voici la description de la saison ' . $i);    
            $season->setProgram($this->getReference('program_1'));
            $this->addReference('season_' . $i, $season);
            $manager->persist($season);
        }  
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}
