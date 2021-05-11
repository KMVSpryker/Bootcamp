<?php

namespace Pyz\Zed\Antelope\Business\Reader;

use Generated\Shared\Transfer\AntelopeTransfer;
use Pyz\Zed\Antelope\Persistence\AntelopeRepositoryInterface;

class AntelopeReader implements AntelopeReaderInterface
{
    /**
     * @var \Pyz\Zed\Antelope\Persistence\AntelopeRepositoryInterface
     */
    protected $antelopeRepository;

    /**
     * @param \Pyz\Zed\Antelope\Persistence\AntelopeRepositoryInterface $antelopeRepository
     */
    public function __construct(AntelopeRepositoryInterface $antelopeRepository)
    {
        $this->antelopeRepository = $antelopeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\AntelopeTransfer $antelopeTransfer
     *
     * @return \Generated\Shared\Transfer\AntelopeTransfer|null
     */
    public function getAntelope(AntelopeTransfer $antelopeTransfer): ?AntelopeTransfer
    {
        return $this->antelopeRepository->findAntelopeById($antelopeTransfer->getIdAntelope());
    }
}
