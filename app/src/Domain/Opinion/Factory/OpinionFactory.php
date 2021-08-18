<?php


namespace App\Domain\Opinion\Factory;


use App\Entity\Opinion;
use App\Entity\Product;
use App\Entity\User;

class OpinionFactory
{
    public static function build(User $user, Product $product): Opinion {
        return (new Opinion())->setUser($user)->setProduct($product);
    }
}