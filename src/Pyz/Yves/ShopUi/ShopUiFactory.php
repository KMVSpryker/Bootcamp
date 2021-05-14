<?php

namespace Pyz\Yves\ShopUi;

use SprykerShop\Yves\ShopUi\ShopUiFactory as SprykerShopUiFactory;
use Pyz\Yves\ShopUi\Twig\Assets\AssetsUrlProvider;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiConfig getConfig()
 */
class ShopUiFactory extends SprykerShopUiFactory
{
    /**
     * @return \SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface
     */
    public function createAssetsUrlProvider(): AssetsUrlProviderInterface
    {
        return new AssetsUrlProvider(
            $this->getConfig(),
            $this->getTwigClient()
        );
    }
}