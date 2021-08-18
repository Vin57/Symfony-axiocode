<?php


namespace App\Domain\Product\Service;


use App\Domain\Product\Form\SearchProductData;
use App\Entity\Product;
use Doctrine\ORM\Query;

interface ProductServiceInterface
{
    /**
     * Proceed to build an executable query.
     * This may be useful for pagination, resulting in not loading
     * all entities but part of them only.
     * @param SearchProductData $searchProductData
     * @return Query
     */
    public function search (SearchProductData $searchProductData): Query;

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