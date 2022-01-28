<?php
declare(strict_types=1);

namespace App\Models;

class ProfitsSummary
{
    #region Class Variables

    /**
     * @var float Average purchase cost
     */
    public float $averageCost;

    /**
     * @var float Average price sold at
     */
    public float $averagePrice;

    /**
     * @var int Total amount purchased
     */
    public int $totalQuantity;

    /**
     * @var float Average profit margin
     */
    public float $averageProfitMargin;

    /**
     * @var float Total profit
     */
    public float $totalProfit;

    /**
     * @var float Conversion amount
     */
    private float $conversionAmount;

    #endregion
    #region Public Methods

    /**
     * @param array $profitResults Profits result array.
     */
    public function __construct(array $profitResults, float $conversionAmount)
    {
        $totalCosts = 0;
        $totalPrice = 0;
        $totalQuantity = 0;
        $totalProfitMargin = 0;
        $totalProfit = 0;

        $this->conversionAmount = $conversionAmount;

        foreach ($profitResults as $profitResult)
        {
            $totalCosts += $profitResult->cost;
            $totalPrice += $profitResult->price;
            $totalQuantity += $profitResult->quantity;
            $totalProfitMargin += $profitResult->getProfitMargin();
            $totalProfit += $profitResult->getProfit();
        }

        $this->averageCost = $totalCosts / count($profitResults);
        $this->averagePrice = $totalPrice / count($profitResults);
        $this->averageProfitMargin = $totalProfitMargin / count($profitResults);
        $this->totalQuantity = $totalQuantity;
        $this->totalProfit = $totalProfit;
    }

    /**
     * Returns total profit dollar amount.
     *
     * @param string $currencyCode
     * @return float
     */
    public function getTotalProfit(string $currencyCode = 'USD'): float
    {
        if ($currencyCode === 'USD')
        {
            return $this->totalProfit;
        }
        else
        {
            return $this->totalProfit * $this->conversionAmount;
        }
    }

    #endregion
}