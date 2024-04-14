<?php
namespace App\BO\csv\Repositories\Interfaces;

interface CSVRepositoryInterface
{
    public function getProductBySKU($sku);
}