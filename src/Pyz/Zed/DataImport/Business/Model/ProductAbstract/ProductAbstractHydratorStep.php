<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

/**
 */
class ProductAbstractHydratorStep implements DataImportStepInterface
{
    const BULK_SIZE = 100;

    const PRODUCT_ABSTRACT_TRANSFER = 'PRODUCT_ABSTRACT_TRANSFER';
    const PRODUCT_ABSTRACT_LOCALIZED_TRANSFER = 'PRODUCT_ABSTRACT_LOCALIZED_TRANSFER';
    const KEY_ABSTRACT_SKU = 'abstract_sku';
    const KEY_COLOR_CODE = 'color_code';
    const KEY_ID_TAX_SET = 'idTaxSet';
    const KEY_ATTRIBUTES = 'attributes';
    const KEY_NAME = 'name';
    const KEY_URL = 'url';
    const KEY_DESCRIPTION = 'description';
    const KEY_META_TITLE = 'meta_title';
    const KEY_META_DESCRIPTION = 'meta_description';
    const KEY_META_KEYWORDS = 'meta_keywords';
    const KEY_TAX_SET_NAME = 'tax_set_name';
    const KEY_CATEGORY_KEY = 'category_key';
    const KEY_CATEGORY_KEYS = 'categoryKeys';
    const KEY_CATEGORY_PRODUCT_ORDER = 'category_product_order';
    const KEY_LOCALES = 'locales';
    const KEY_NEW_FROM = 'new_from';
    const KEY_NEW_TO = 'new_to';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        //TODO this needs to go to writer or somewhere else
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->importProductAbstract($dataSet);

        // TODO this needs to go to writer or somewhere else
//        $this->productRepository->addProductAbstract($productAbstractEntity);
        $this->importProductAbstractLocalizedAttributes($dataSet);
        // TODO move the these like the ProductAbstract and Localized Attribute
//        $this->importProductCategories($dataSet, $productAbstractEntity);
//        $this->importProductUrls($dataSet, $productAbstractEntity);
//
//        $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductAbstract(DataSetInterface $dataSet)
    {
        $productAbstractEntityTransfer = new SpyProductAbstractEntityTransfer();
        $productAbstractEntityTransfer->setSku($dataSet[static::KEY_ABSTRACT_SKU]);

        $productAbstractEntityTransfer
            ->setColorCode($dataSet[static::KEY_COLOR_CODE])
            ->setFkTaxSet($dataSet[static::KEY_ID_TAX_SET])
            ->setAttributes(json_encode($dataSet[static::KEY_ATTRIBUTES]))
            ->setNewFrom($dataSet[static::KEY_NEW_FROM])
            ->setNewTo($dataSet[static::KEY_NEW_TO]);

        $dataSet[static::PRODUCT_ABSTRACT_TRANSFER] = $productAbstractEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductAbstractLocalizedAttributes(DataSetInterface $dataSet)
    {
        $localizedAttributeTransfer = [];
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $productAbstractLocalizedAttributesEntityTransfer = new SpyProductAbstractLocalizedAttributesEntityTransfer();
            $productAbstractLocalizedAttributesEntityTransfer
                ->setName($localizedAttributes[static::KEY_NAME])
                ->setDescription($localizedAttributes[static::KEY_DESCRIPTION])
                ->setMetaTitle($localizedAttributes[static::KEY_META_TITLE])
                ->setMetaDescription($localizedAttributes[static::KEY_META_DESCRIPTION])
                ->setMetaKeywords($localizedAttributes[static::KEY_META_KEYWORDS])
                ->setFkLocale($idLocale)
                ->setAttributes(json_encode($localizedAttributes[static::KEY_ATTRIBUTES]));

            $localizedAttributeTransfer[] = [
                'abstract_sku' => $dataSet[static::KEY_ABSTRACT_SKU],
                'localizedAttributeTransfer' => $productAbstractLocalizedAttributesEntityTransfer,
            ];
        }

        $dataSet[static::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = $localizedAttributeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    protected function importProductCategories(DataSetInterface $dataSet, SpyProductAbstract $productAbstractEntity)
    {
        $categoryKeys = $this->getCategoryKeys($dataSet[static::KEY_CATEGORY_KEY]);
        $categoryProductOrder = $this->getCategoryProductOrder($dataSet[static::KEY_CATEGORY_PRODUCT_ORDER]);

        foreach ($categoryKeys as $index => $categoryKey) {
            if (!isset($dataSet[static::KEY_CATEGORY_KEYS][$categoryKey])) {
                throw new DataKeyNotFoundInDataSetException(sprintf(
                    'The category with key "%s" was not found in categoryKeys. Maybe there is a typo. Given Categories: "%s"',
                    $categoryKey,
                    implode(array_values($dataSet[static::KEY_CATEGORY_KEYS]))
                ));
            }
            $productOrder = null;
            if (count($categoryProductOrder) > 0 && isset($categoryProductOrder[$index])) {
                $productOrder = $categoryProductOrder[$index];
            }

            $productCategoryEntity = SpyProductCategoryQuery::create()
                ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->filterByFkCategory($dataSet[static::KEY_CATEGORY_KEYS][$categoryKey])
                ->findOneOrCreate();

            $productCategoryEntity
                ->setProductOrder($productOrder);

            if ($productCategoryEntity->isNew() || $productCategoryEntity->isModified()) {
                $productCategoryEntity->save();

                //TODO move these to writers
//                $this->addPublishEvents(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $productAbstractEntity->getIdProductAbstract());
//                $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
            }
        }
    }

    /**
     * @param string $categoryKeys
     *
     * @return array
     */
    protected function getCategoryKeys($categoryKeys)
    {
        $categoryKeys = explode(',', $categoryKeys);

        return array_map('trim', $categoryKeys);
    }

    /**
     * @param string $categoryProductOrder
     *
     * @return array
     */
    protected function getCategoryProductOrder($categoryProductOrder)
    {
        $categoryProductOrder = explode(',', $categoryProductOrder);

        return array_map('trim', $categoryProductOrder);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return void
     */
    protected function importProductUrls(DataSetInterface $dataSet, SpyProductAbstract $productAbstractEntity)
    {
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $abstractProductUrl = $localizedAttributes[static::KEY_URL];

            $this->cleanupRedirectUrls($abstractProductUrl);

            $urlEntity = SpyUrlQuery::create()
                ->filterByFkLocale($idLocale)
                ->filterByFkResourceProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->findOneOrCreate();

            $urlEntity->setUrl($abstractProductUrl);

            if ($urlEntity->isNew() || $urlEntity->isModified()) {
                $urlEntity->save();
                //TODO move these to writers
//                $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
            }
        }
    }

    /**
     * @param string $abstractProductUrl
     *
     * @return void
     */
    protected function cleanupRedirectUrls($abstractProductUrl)
    {
        SpyUrlQuery::create()
            ->filterByUrl($abstractProductUrl)
            ->filterByFkResourceRedirect(null, Criteria::ISNOTNULL)
            ->delete();
    }
}
