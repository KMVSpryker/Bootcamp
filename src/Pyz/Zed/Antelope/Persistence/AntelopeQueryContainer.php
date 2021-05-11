<?php

namespace Pyz\Zed\Antelope\Persistence;

use Orm\Zed\Antelope\Persistence\PyzAntelopeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\Antelope\Persistence\AntelopePersistenceFactory getFactory()
 */
class AntelopeQueryContainer extends AbstractQueryContainer implements AntelopeQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Antelope\Persistence\PyzAntelopeQuery
     */
    public function queryAntelopes(): PyzAntelopeQuery
    {
        return $this->getFactory()->createPyzAntelopeQuery();
    }
}