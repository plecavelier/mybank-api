<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tagsNumber = 20;
        $icons = array(null, 'euro', 'glass', 'music', 'film', 'home');
        $colors = array(null, '#576E99', '#206CFF', '#0000CC', '#5229A3', '#854F61');

        for ($i = 1; $i <= $tagsNumber; $i++) {
            $tag = new Tag();
            $tag->setName('Catégorie n°'. $i);
            $tag->setDescription('Description de la catégorie n°'.$i);
            $tag->setIcon($icons[array_rand($icons)]);
            $tag->setColor($colors[array_rand($colors)]);
            $this->addReference('tag-'.$i, $tag);
            $manager->persist($tag);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}