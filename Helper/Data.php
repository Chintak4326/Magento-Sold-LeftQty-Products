<?php

namespace ChintakExtensions\LeftQty\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory;

class Data extends AbstractHelper
{
    protected $reportCollectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $reportCollectionFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem
    ) {
        $this->stockItem = $stockItem;
        $this->reportCollectionFactory = $reportCollectionFactory;
        parent::__construct($context);
    }

    public function getSoldQtyByProductId($productId = null)
    {
        $SoldProducts = $this->reportCollectionFactory->create();
        // echo "<pre>";
        // print_r($SoldProducts->getData());
        // echo "</pre>";die;
        $SoldProductsCount = $SoldProducts->addOrderedQty()->addAttributeToFilter('product_id', $productId);

        if(!$SoldProductsCount->count()){
            return 0;
        }

        // print_r($SoldProductsCount->getSelect()->__toString());die;
        $product = $SoldProductsCount->getFirstItem();
        // print_r((int)$product->getData('ordered_qty'));die;
        return (int)$product->getData('ordered_qty');
    }

    public function getStockLeft($product = null){
        return $this->stockItem->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
    }
}