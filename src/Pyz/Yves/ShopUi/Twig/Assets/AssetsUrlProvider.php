<?php

namespace Pyz\Yves\ShopUi\Twig\Assets;

use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProvider as SprykerAssetsUrlProvider;

class AssetsUrlProvider extends SprykerAssetsUrlProvider
{
    protected const PLACEHOLDER_CODEBUCKET = '%codeBucket%';

    /**
     * @return string
     */
    public function getAssetsUrl(): string
    {
        $yvesAssetsUrl = parent::getAssetsUrl();

        $yvesAssetsUrl = strtr($yvesAssetsUrl, [
            static::PLACEHOLDER_CODEBUCKET => getenv('APPLICATION_STORE'),//never do this at home!
        ]);

        return $yvesAssetsUrl;
    }
}