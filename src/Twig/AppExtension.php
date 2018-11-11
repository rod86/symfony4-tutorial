<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use App\Entity\LikeNotification;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getGlobals()
    {
        return [
            'locale' => $this->locale
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter'])
        ];
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('like', function($obj) { return $obj instanceof LikeNotification; })
        ];
    }

    public function priceFilter($number)
    {
        return '$'.number_format($number, 2, '.', ',');
    }
}