<?php
/**
 * Copyright © 2016 yukoff. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SendGrid\SendGridSmtp\Model\Config\Source;

class Authtype implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ssl', 'label' => 'SSL (SendGrid)'],
            ['value' => 'tls', 'label' => 'TLS (SendGrid)']
        ];
    }
}
