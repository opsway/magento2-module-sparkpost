<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailSparkPost\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use OpsWay\EmailSparkPost\Model\Strategy\SparkStrategy;

class SendEmailObserver implements ObserverInterface
{
    /**
     * @var \OpsWay\EmailSparkPost\Model\Strategy\StrategyInterface
     */
    protected $strategy;

    /**
     * @param \OpsWay\EmailSparkPost\Model\Strategy\SparkStrategy $strategy
     */
    public function __construct(SparkStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        $targetClass = $observer->getData('context');
        $this->strategy->setMail($targetClass->getMessage());

        if ($targetClass->getStrategy() == null && $this->strategy->isEnabled()) {
            $targetClass->setStrategy($this->strategy);
        }
    }
}
