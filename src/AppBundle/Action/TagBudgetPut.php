<?php

namespace AppBundle\Action;

use AppBundle\Entity\Tag;
use AppBundle\Entity\TagBudget;
use Symfony\Component\HttpFoundation\Request;

class TagBudgetPut
{

    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @param Tag $data
     * @return Tag
     */
    public function __invoke(Request $request, $data)
    {
        $budgetFound = null;
        foreach ($data->getBudgets() as $budget) {
            if ($budget->getYear() === $data->getBudgetYear()) {
                $budgetFound = $budget;
            }
        }
        if ($budgetFound) {
            $budgetFound->setAmount($data->getBudgetAmount());
        } else {
            $newBudget = new TagBudget();
            $newBudget->setYear($data->getBudgetYear());
            $newBudget->setAmount($data->getBudgetAmount());
            $data->addBudget($newBudget);
        }
        return $data;
    }
}