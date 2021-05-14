<?php

namespace Pyz\Yves\AntelopeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class AntelopeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_ANTELOPE = 'CLIENT_ANTELOPE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addAntelopeClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAntelopeClient(Container $container)
    {
        $container[self::CLIENT_ANTELOPE] = function (Container $container) {
            return $container->getLocator()->antelope()->client();
        };

        return $container;
    }
}
