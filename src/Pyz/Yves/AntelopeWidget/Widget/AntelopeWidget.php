<?php

namespace Pyz\Yves\AntelopeWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\AntelopeWidget\AntelopeWidgetFactory getFactory()
 */
class AntelopeWidget extends AbstractWidget
{
    /**
     * @param string $antelopeName
     */
    public function __construct(string $antelopeName)
    {
        $this->addParameter('antelope', $this->findAntelope($antelopeName));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AntelopeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AntelopeWidget/views/antelope/antelope.twig';
    }

    /**
     * @param string $antelopeName
     *
     * @return array
     */
    protected function findAntelope(string $antelopeName): array
    {
        return $this->getFactory()
            ->getAntelopeClient()
            ->getAntelopeByName($antelopeName);
    }
}