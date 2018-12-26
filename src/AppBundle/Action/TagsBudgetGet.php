<?php

namespace AppBundle\Action;

use AppBundle\Manager\TagManager;
use Symfony\Component\HttpFoundation\Request;

class TagsBudgetGet
{
    private $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function __invoke(Request $request, $data)
    {
        return $this->tagManager->getBudgets($request->query->getInt('year'));
    }
}