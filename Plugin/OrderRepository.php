<?php
/**
 * @package RubenRomao_OrderAttribute
 * @autor rubenromao@gmail.com
 */
declare(strict_types=1);

namespace RubenRomao\OrderAttribute\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Add the custom extension attribute 'order_attribute' to the Get and GetList order repository.
 */
class OrderRepository
{
    /**
     * Order Comment field name
     */
    protected const FIELD_NAME = 'order_attribute';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * OrderRepository constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        OrderExtensionFactory $extensionFactory
    ) {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Add "order_attribute" extension attribute
     * to order data object to make it accessible through API data.
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $customAttribute = $order->getData(self::FIELD_NAME);
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->extensionFactory->create();
        $extensionAttributes->getOrderAttribute($customAttribute);
        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    /**
     * Add "order_attribute" extension attribute
     * to order data object to make it accessible through API order list.
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface
    {
        $orders = $searchResult->getItems();
        foreach ($orders as &$order) {
            $customAttribute = $order->getData(self::FIELD_NAME);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ?: $this->extensionFactory->create();
            $extensionAttributes->setOrderAttribute($customAttribute);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
