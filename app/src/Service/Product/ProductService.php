<?php


namespace App\Service\Product;


use App\Entity\Product;
use App\Form\SearchProductData;
use Doctrine\ORM\Query;

interface ProductServiceInterface
{
    public function list (SearchProductData $searchProductData): Query;

    /**
     * @param Product $product
     * @return Product|null
     * <ul>
     *  <li>Return the given {@see Product} entity as newly persisted entity.</li>
     *  <li>Otherwise, if a problem occurs, return null.</li>
     * </ul>
     */
    public function new (Product $product): ?Product;

    /**
     * @param Product $product
     * @return bool True on update success, otherwise false.
     */
    public function update (Product $product): bool;
}