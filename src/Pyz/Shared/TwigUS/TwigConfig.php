<?php

namespace Pyz\Shared\TwigUS;

use Spryker\Shared\Twig\TwigConfig as SprykerTwigConfig;

class TwigConfig extends SprykerTwigConfig
{
    /**
     * @return string
     */
    public function getYvesThemeName(): string
    {
        return 'alternative';
    }
}