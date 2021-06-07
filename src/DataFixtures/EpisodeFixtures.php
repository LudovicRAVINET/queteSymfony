<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODES = [
        'Episode 1',
        'Episode 2',
        'Episode 3',
        'Episode 4',
        'Episode 5',
        'Episode 6',
        'Episode 7',
        'Episode 8',
        'Episode 9',
        'Episode 10',
        'Episode 11', 
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::EPISODES as $key => $episodeTitle) { 
            $episode = new Episode();     
            $episode->setTitle($episodeTitle);    
            $episode->setNumber($key+1);    
            $episode->setSynopsis("synopsis de l'épisode $key");    
            $episode->setSeason($this->getReference('season_3'));    
            $manager->persist($episode);
            $this->addReference('episode_' . $key, $episode);
        }  
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
