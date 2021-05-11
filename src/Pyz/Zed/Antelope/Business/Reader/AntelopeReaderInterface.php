<?php

namespace Pyz\Zed\Antelope\Business\Reader;

use Generated\Shared\Transfer\AntelopeTransfer;

interface AntelopeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AntelopeTransfer $antelopeTransfer
     *
     * @return \Generated\Shared\Transfer\AntelopeTransfer|null
     */
    public function getAntelope(AntelopeTransfer $antelopeTransfer): ?AntelopeTransfer;
}