<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtentionExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }
                                                        //  ? est optionnel avec une valeur par default
    public function pluralize(int $count, string $singular, ?string $plurial = null )
    {
        $plurial ??=  $singular . 's';
        
        if ($count > 1) {
           return $count.' '. $plurial ;
        }else{
            return $count.' '. $singular;
        }
    }
}
