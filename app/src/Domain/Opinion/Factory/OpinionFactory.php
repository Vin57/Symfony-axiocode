<?php


namespace App\Domain\Opinion\Factory;

use App\Domain\Opinion\Entity\Opinion;
use App\Domain\Product\Entity\Product;
use App\Domain\User\Entity\User;

class OpinionFactory
{
    public static function build(User $user, Product $product): Opinion {
        return (new Opinion())->setUser($user)->setProduct($product);
    }
}