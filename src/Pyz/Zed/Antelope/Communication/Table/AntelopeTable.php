<?php

namespace Pyz\Zed\Antelope\Communication\Table;

use Orm\Zed\Antelope\Persistence\Map\PyzAntelopeTableMap;
use Orm\Zed\Antelope\Persistence\PyzAntelope;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\Antelope\Persistence\AntelopeQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AntelopeTable extends AbstractTable
{
    public const ACTIONS = 'actions';

    public const COL_ID_ANTELOPE = 'id_antelope';
    public const COL_NAME = 'name';
    public const COL_COLOR = 'color';

    /**
     * @var \Pyz\Zed\Antelope\Persistence\AntelopeQueryContainerInterface
     */
    protected $antelopeQueryContainer;

    /**
     * @param \Pyz\Zed\Antelope\Persistence\AntelopeQueryContainerInterface $antelopeQueryContainer
     */
    public function __construct(AntelopeQueryContainerInterface $antelopeQueryContainer)
    {
        $this->antelopeQueryContainer = $antelopeQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_ANTELOPE => '#',
            static::COL_NAME => 'Name',
            static::COL_COLOR => 'Color',
            static::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            static::COL_ID_ANTELOPE,
            static::COL_NAME,
            static::COL_COLOR,
        ]);

        $config->setSearchable([
            PyzAntelopeTableMap::COL_NAME,
            PyzAntelopeTableMap::COL_COLOR,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Antelope\Persistence\PyzAntelope[] $antelopeCollection */
        $antelopeCollection = $this->runQuery($query, $config, true);

        if ($antelopeCollection->count() < 1) {
            return [];
        }

        return $this->mapAntelopeCollection($antelopeCollection);
    }

    /**
     * @param \Orm\Zed\Antelope\Persistence\PyzAntelope $antelope
     *
     * @return string
     */
    protected function buildLinks(PyzAntelope $antelope)
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            sprintf('/antelope/view?id-antelope=%d', $antelope->getIdAntelope()),
            'View'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Antelope\Persistence\PyzAntelope[] $antelopeCollection
     *
     * @return array
     */
    protected function mapAntelopeCollection(ObjectCollection $antelopeCollection)
    {
        $antelopeList = [];

        foreach ($antelopeCollection as $antelopeEntity) {
            $antelopeList[] = $this->mapAntelopeRow($antelopeEntity);
        }

        return $antelopeList;
    }

    /**
     * @param \Orm\Zed\Antelope\Persistence\PyzAntelope $antelopeEntity
     *
     * @return array
     */
    protected function mapAntelopeRow(PyzAntelope $antelopeEntity)
    {
        $antelopeRow = $antelopeEntity->toArray();

        $antelopeRow[static::COL_ID_ANTELOPE] = $antelopeEntity->getIdAntelope();
        $antelopeRow[static::COL_NAME] = $antelopeEntity->getName();
        $antelopeRow[static::COL_COLOR] = $antelopeEntity->getColor();

        $antelopeRow[static::ACTIONS] = $this->buildLinks($antelopeEntity);

        return $antelopeRow;
    }

    /**
     * @return \Orm\Zed\Antelope\Persistence\PyzAntelopeQuery
     */
    protected function prepareQuery()
    {
        $query = $this->antelopeQueryContainer->queryAntelopes();

        return $query;
    }
}