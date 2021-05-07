<?php

namespace Pyz\Yves\PersonalizedProduct\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @param $limit
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction($limit)
    {
                $searchResults = $this->getClient()->getPersonalizedProducts($limit);

                return $this->view(
                        $searchResults,
                        [],
            '@PersonalizedProduct/views/index/index.twig'
                );

    }
}