<?php
namespace Pyz\Yves\Application\Module;

use ProjectA\Yves\Library\Silex\Application;
use ProjectA\Yves\Library\Silex\Provider\CookieServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\StorageServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\ExceptionServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\TwigServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\YvesLoggingServiceProvider;
use ProjectA\Yves\Library\Silex\Routing\SilexRouter;
use ProjectA\Yves\Cart\Module\ControllerProvider as CartProvider;
use ProjectA\Yves\Catalog\Module\ControllerProvider as CatalogProvider;
use ProjectA\Yves\Setup\Module\ControllerProvider as SetupProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\ServiceProviderInterface;
use Silex\ControllerProviderInterface;
use SilexRouting\Provider\RoutingServiceProvider;
use Symfony\Component\Routing\RouterInterface;

class Bootstrap extends \ProjectA\Yves\Library\Silex\Bootstrap
{
    /**
     * @return ServiceProviderInterface[]
     */
    protected function getServiceProviders()
    {
        return [
            new ExceptionServiceProvider(),
            new YvesLoggingServiceProvider(),
            new CookieServiceProvider(),
            new SessionServiceProvider(),
            new UrlGeneratorServiceProvider(),
            new ServiceControllerServiceProvider(),
            new RoutingServiceProvider(),
            new TwigServiceProvider(),
            new StorageServiceProvider()
        ];
    }

    /**
     * @return ControllerProviderInterface[]
     */
    protected function getControllerProviders()
    {
        return [
            new CartProvider(),
            new CatalogProvider(),
            new SetupProvider(),
        ];
    }

    /**
     * @param Application $app
     * @return RouterInterface[]
     */
    protected function getRouters(Application $app)
    {
        return [
            new SilexRouter($app)
        ];
    }
}
