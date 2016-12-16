<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * OperationChartData
 *
 * @ApiResource(collectionOperations={
 *     "get"={
 *         "route_name"="operation_chart_data_get",
 *         "filters"={"operation_chart_data.filter"}
 *     }
 * }, itemOperations={ })
 */
class OperationChartData
{

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $amount;

    public function __construct($date, $amount) {
        $this->date = $date;
        $this->amount = $amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return OperationChartData
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return OperationChartData
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
}

