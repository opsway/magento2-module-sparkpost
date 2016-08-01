<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailSparkPost\Controller\Adminhtml\Spark;

/**
 * Class Send - Sends the message for testing
 * @package OpsWay\EmailSparkPost\Controller\Adminhtml\Amazon
 */
class Send extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Mail\Message
     */
    protected $message;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Mail\Message $message
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Mail\Message $message
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->message = $message;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $email = $this->_objectManager->get('OpsWay\EmailSparkPost\Helper\Data')->getTestEmail();
        $fromEmail = $this->_objectManager->get('OpsWay\EmailSparkPost\Helper\Data')->getFromEmail();

        $receiverInfo = [
            'name' => 'Reciver Name',
            'email' => $email
        ];

        $senderInfo = [
            'name' => 'Sender Name',
            'email' => $fromEmail,
        ];

        $result = $this->_objectManager->get('OpsWay\EmailSparkPost\Helper\Email')->sendMail(
            $senderInfo,
            $receiverInfo
        );

        return $this->resultJsonFactory->create()->setData($result);
    }
}
