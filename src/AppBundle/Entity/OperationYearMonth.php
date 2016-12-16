<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * OperationMonth
 *
 * @ApiResource(collectionOperations={
 *     "get"={"route_name"="operation_year_month_get"}
 * }, itemOperations={ })
 */
class OperationYearMonth
{

    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    public function __construct($year, $month) {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return OperationMonth
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return OperationMonth
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }
}

