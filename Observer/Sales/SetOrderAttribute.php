<?php
/**
 * @package RubenRomao_OrderAttribute
 * @autor rubenromao@gmail.com
 */
declare(strict_types=1);

namespace RubenRomao\OrderAttribute\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Observer to set the value yes or no
 * to the extension attribute order_attribute.
 */
class SetOrderAttribute implements ObserverInterface
{
    /**
     * Array with attribute values.
     *
     * @var array|string[]
     */
    protected array $orderAttributeValuesYesNo = array(
        "yes",
        "no"
    );

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer
    ): void {
        $order = $observer->getData('order');
        // get random value yes/no from array
        $orderAttributeValue = $this->orderAttributeValuesYesNo[
            array_rand($this->orderAttributeValuesYesNo)
        ];
        $order->setOrderAttribute($orderAttributeValue);
        $order->save();
    }
}
