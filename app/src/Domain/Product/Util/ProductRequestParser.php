<?php

namespace App\Domain\Product\Util;

use Symfony\Component\HttpFoundation\Request;

class ProductRequestParser
{
    public function parse(Request $request) {
        if ($category_id = intval($request->get('category'))) {
            // Api Bundle require that object entity is formatted as : object => ['id' => 123]
            $request->request->set('category', ['id' => $category_id]);
        }
    }
}