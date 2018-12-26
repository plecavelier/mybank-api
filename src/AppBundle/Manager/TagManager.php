<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class TagManager
{
    private $em;
    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getBudgets(int $year): array {
        $startDate = new \DateTime();
        $startDate->setDate($year, 1, 1);
        $maxDate = new \DateTime();
        $maxDate->modify('+1 day');
        $endDate = new \DateTime();
        $endDate->setDate($year + 1, 1, 1);
        $endDate = min($endDate, $maxDate);
        $factor = null;
        if ($endDate > $startDate) {
            $diff = $endDate->diff($startDate)->format("%a");
            $days = $startDate->format('L') === '1' ? 366 : 365;
            $factor = $diff / $days;
        }


        $yearDays = $startDate->format('%L') === 1 ? 366 : 365;

        /** @var Tag[] $tags */
        $tags = $this->em->getRepository('AppBundle:Tag')->findAll();
        $totals = $this->em->getRepository('AppBundle:Operation')->sumAmountByTag($startDate, $endDate);
        $budgets = $this->em->getRepository('AppBundle:TagBudget')->findBudgetByTag($year);
        foreach ($tags as $tag) {
            $tag->setTotalAmount(isset($totals[$tag->getId()]) ? $totals[$tag->getId()] : 0);
            $tag->setBudgetAmount(isset($budgets[$tag->getId()]) ? $budgets[$tag->getId()] : null);
            if ($factor) {
                $tag->setGap(($tag->getTotalAmount() * $factor) - $tag->getBudgetAmount());
            }
        }

        usort($tags, function(Tag $tag1, Tag $tag2) {
            if ($tag1->getGap() !== null && $tag2->getGap() !== null) {
                return $tag1->getGap() - $tag2->getGap();
            }
        });

        $tags = array_filter($tags, function(Tag $tag) {
            return $tag->getBudgetAmount() !== null || $tag->getTotalAmount() !== 0 || !$tag->isDisabled();
        });

        return $tags;
    }
}