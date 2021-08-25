<?php

namespace App\Domain\Product\Util;

use Symfony\Component\HttpFoundation\Request;

interface RequestParserInterface
{
    public function parse(Request $request);
}