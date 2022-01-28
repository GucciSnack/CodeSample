<?php
declare(strict_types=1);

namespace App\Models;

class ProfitResult
{
    #region Class Variables

    /**
     * @var string Product SKU
     */
    public string $sku;

    /**
     * @var float Product overhead cost
     */
    public float $cost;

    /**
     * @var float Price sold at
     */
    public float $price;

    /**
     * @var int Quantity sold
     */
    public int $quantity;

    /**
     * @var float Conversion amount
     */
    private float $conversionAmount;

    #endregion
    #region Public Members

    public function __construct(string $sku, float $cost, float $price, int $quantity, float $conversionAmount)
    {
        $this->sku = $sku;
        $this->cost = $cost;
        $this->price = $price;
        $this->quantity = $quantity;

        $this->conversionAmount = $conversionAmount;
    }

    /**
     * Returns profit margin.
     *
     * @return float
     */
    public function getProfitMargin(): float
    {
        try
        {
            $totalRevenue = $this->getProfit();
            $totalExpense = $this->cost * $this->quantity;
            $netProfit = $totalRevenue - $totalExpense;
            $netProfitMargin = $netProfit / $totalRevenue;
            return $netProfitMargin;
        }
        catch(\DivisionByZeroError $e)
        {
            return 0;
        }
    }

    /**
     * Returns profit dollar amount.
     *
     * @param string $currencyCode
     * @return float
     */
    public function getProfit(string $currencyCode = 'USD'): float
    {
        $revenue = $this->price - $this->cost;
        $usdProfit = $revenue * $this->quantity;

        if ($currencyCode === 'USD')
        {
            return $usdProfit;
        }
        else
        {
            return $usdProfit * $this->conversionAmount;
        }
    }

    #endregion
}