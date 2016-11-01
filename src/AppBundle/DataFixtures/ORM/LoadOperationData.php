<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Operation;

class LoadOperationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $accountsNumber = 5;
        $tagsNumber = 20;
        $operationsNumberByDay = 10;
        $startDate = new \DateTime('2010-03-17');
        $endDate = new \DateTime('2011-04-30');

        for ($timestamp = $startDate->getTimestamp(); $timestamp < $endDate->getTimestamp(); $timestamp += 86400) {
            for ($i = 1; $i <= $operationsNumberByDay; $i++) {
                $operation = new Operation();
                $date = new \DateTime();
                $date->setTimestamp($timestamp);
                $dateString = $date->format('d/m/Y');
                $operation->setDate($date);
                $operation->setName('Opération n°'.$i.' du '.$dateString);
                $operation->setDescription('Description de l\'opération n°'.$i.' du '.$dateString);
                $operation->setAmount(rand(-10000, 10000));
                $operation->setAccount($this->getReference('account-'.rand(1, $accountsNumber)));
                if (rand(0, 1) == 1) {
                    $operation->setTag($this->getReference('tag-'.rand(1, $tagsNumber)));
                }
                $manager->persist($operation);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}