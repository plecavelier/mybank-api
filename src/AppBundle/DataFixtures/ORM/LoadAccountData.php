<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Account;

class LoadAccountData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $accountsNumber = 5;

        for ($i = 1; $i <= $accountsNumber; $i++) {
            $account = new Account();
            $account->setName('Compte bancaire n°'. $i);
            $account->setDescription('Description du compte bancaire n°'.$i);
            $account->setNumber(str_shuffle('0123456789'));
            $this->addReference('account-'.$i, $account);
            $manager->persist($account);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}