<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('susbtr_dotpad', [$this, 'susbtrDotpad']),
        ];
    }

    /**
     * @param string $str A string.
     * @param int $maxLength Limit {@see $str} to a given length.
     * If {@see $str} is oversize, add "..." to specify that the
     * string has been cut.
     * @return string A string sliced to {@see $maxLength},
     * with optional '...' at the end of the string, depending
     * whether or not given {@see $str} was exceeded.
     */
    public function susbtrDotpad(string $str, int $maxLength) :string
    {
        if ($maxLength <= 3) {
            return "";
        }
        $len = strlen($str);
        $str = mb_substr($str ,0 , $maxLength);
        if (strlen($str) > $maxLength) {
            mb_substr($str, 0, $maxLength - 3);
            $str .= "...";
        }
        return $str;
    }
}
