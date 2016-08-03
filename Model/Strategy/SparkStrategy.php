<?php
/**
 * Copyright © 2016 OpsWay. All rights reserved.
 */

namespace OpsWay\EmailSparkPost\Model\Strategy;

use OpsWay\EmailSparkPost\Helper\Data as Helper;
use \Zend_Http_Client as Http;
use \Zend_Uri as Uri;

class SparkStrategy implements StrategyInterface
{
    /**
     * @var \OpsWay\EmailAmazonSES\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Mail\Message
     */
    protected $mail;

    protected $host = 'https://api.sparkpost.com/api/v1/transmissions';

    protected $config;

    /**
     * @param \OpsWay\EmailSparkPost\Helper\Data $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->config = $this->helper->getSparkSettings();
        $this->host = Uri::factory($this->host);
    }

    /**
     * @return string
     * @throws \Zend_Http_Client_Exception
     */
    public function send()
    {
        if ($this->isEnabled()) {
            $date = gmdate('D, d M Y H:i:s O');

            $client = new Http($this->host);
            $client->setMethod(Http::POST);
            $client->setHeaders([
                'Date' => $date,
                'Authorization' => $this->getConfig()['apiKey'],
            ]);
            $client->resetParameters();
            $client->setHeaders('Content-type', 'application/json');
            $client->setRawData($this->generateParams());
            $response = $client->request(Http::POST);

            return $response->getRawBody();
        }
    }

    /**
     * get module configs
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helper->isEnabled();
    }

    /**
     * @param \Magento\Framework\Mail\MessageInterface $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @param $message
     * @return null
     */
    protected function extractHtml($message)
    {
        $body = $message->getBody();

        if ($body->type === 'text/html') {
            return $body->getRawContent();
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getMailType()
    {
        $body = $this->mail->getBody();

        if (is_string($body)) {
            return 'Text';
        }

        return explode('/', $body->type)[1];
    }

    /**
     * @param $message
     * @return null
     */
    protected function extractText($message)
    {
        $body = $message->getBody();

        if (is_string($body)) {
            return $body;
        }

        if ($body->type === 'text/plain') {
            return $body->getContent();
        }

        return null;
    }

    /**
     * @return array
     */
    protected function generateParams()
    {
        $result = [];

        $contentParams = [
            'from' => $this->mail->getFrom(),
            'html' => $this->extractHtml($this->mail),
            'subject' => $this->mail->getSubject()
        ];

        $result['content'] = $contentParams;

        $recipients = $this->mail->getRecipients();
        foreach ($recipients as $recipient) {
            $recipientsParams[] = [ 'address' => $recipient ];
        }

        $result['recipients'] = $recipientsParams;

        return json_encode($result);
    }
}
