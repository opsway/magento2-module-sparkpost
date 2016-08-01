<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailSparkPost\Model\Strategy;

interface StrategyInterface
{
    public function getConfig();

    public function send();
}
