<?php
namespace SendGrid\SendGridSmtp\Controller\Adminhtml\Test;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    
    /**
     * @var \SendGrid\SendGridSmtp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \SendGrid\SendGridSmtp\Helper\Data $dataHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \SendGrid\SendGridSmtp\Helper\Data $dataHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_dataHelper = $dataHelper;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute() {

        $request = $this->getRequest();
        $store_id = $request->getParam('store', null);
        
        
        $name = 'SendGrid SMTP Plugin Test';
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // if default view
        // see https://github.com/magento/magento2/issues/3019
        if(!$request->getParam('store', false)){
            if(empty($username) || empty($password)){
                $this->getResponse()->setBody(__('Please enter a valid username/password'));
                return;
            }
        }

        // if password mask (6 stars)
        $password = ($password == '******') ? $this->_dataHelper->getConfigPassword($store_id) : $password;
        
        $to = $request->getPost('email') ? $request->getPost('email') : $username;

        // SMTP server configuration
        $smtpHost = $request->getPost('smtphost');

        $smtpConf = array(
            'auth' => strtolower($request->getPost('auth')),
            'ssl' => $request->getPost('ssl'),
            'username' => $username,
            'password' => $password
        );

        // SendGrid category
        $category = $request->getPost('category');

        $transport = new \Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);

        $from = trim($request->getPost('from_email'));
        $from = \Zend_Validate::is($from, 'EmailAddress') ? $from : $username;

        //Create email
        $mail = new \Zend_Mail();
        $mail->setFrom($from, $name);
        $mail->addTo($to, $to);
        $mail->setSubject('Hello from SendGrid');
        $mail->setBodyText('Thank you for choosing SendGrid extension.');

        if ($category) {
            $mail->addHeader(
                'X-SMTPAPI',
                $this->_jsonHelper->jsonEncode([ 'category' => $category ])
            );
        }

        $result = __('Sent... Please check your email') . ' ' . $to;
        
        try {
            //only way to prevent zend from giving a error
            if (!$mail->send($transport) instanceof \Zend_Mail){}
        } catch (\Exception $e) {
            $result = __($e->getMessage());
        }
        
        $this->getResponse()->setBody($this->makeClickableLinks($result));
    }
    
    /**
     * Make link clickable
     * @param string $s
     * @return string
     */
    public function makeClickableLinks($s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SendGrid_SendGridSmtp');
    }

}