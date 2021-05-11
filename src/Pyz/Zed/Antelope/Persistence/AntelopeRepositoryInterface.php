<?php

namespace Pyz\Zed\Antelope\Persistence;

use Generated\Shared\Transfer\AntelopeTransfer;

interface AntelopeRepositoryInterface
{
    /**
     * @param int $idAntelope
     *
     * @return \Generated\Shared\Transfer\AntelopeTransfer|null
     */
    public function findAntelopeById(int $idAntelope): ?AntelopeTransfer;
}