<?php
/**
 * Mail Transport
 * Copyright © 2016 yukoff. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SendGrid\SendGridSmtp\Model;

class Transport extends \Zend_Mail_Transport_Smtp implements \Magento\Framework\Mail\TransportInterface
{
    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    protected $_message;


    /**
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @param \SendGrid\SendGridSmtp\Helper\Data $dataHelper
     * @throws \Zend_Mail_Exception
     */
    public function __construct(\Magento\Framework\Mail\MessageInterface $message, \SendGrid\SendGridSmtp\Helper\Data $dataHelper)
    {
        if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
        }

        // set reply-to path
        $setReturnPath = $dataHelper->getConfigSetReturnPath();
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $message->getFrom();
                break;
            case 2:
                $returnPathEmail = $dataHelper->getConfigReturnPathEmail();
                break;
            default:
                $returnPathEmail = null;
                break;
        }
        
        if ($returnPathEmail !== null && $dataHelper->getConfigSetReturnPath()) {
            $message->setReturnPath($returnPathEmail);
        }

        if ($message->getReplyTo() === NULL && $dataHelper->getConfigSetReplyTo()) {
            $message->setReplyTo($returnPathEmail);
        }
       
        // set config
        $smtpConf = [
           'auth' => strtolower($dataHelper->getConfigAuth()),
           'ssl' => $dataHelper->getConfigSsl(),
           'username' => $dataHelper->getConfigUsername(),
           'password' => $dataHelper->getConfigPassword()
        ];
        
        
        $smtpHost = $dataHelper->getConfigSmtpHost();
        parent::__construct($smtpHost, $smtpConf);
        $this->_message = $message;
    }

    /**
     * Send mail using this transport
     *
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage()
    {
        try {
            parent::send($this->_message);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }
}
