[![Stories in Ready](https://badge.waffle.io/yukoff/magento2-sendgrid-smtp.png?label=ready&title=Ready)](https://waffle.io/yukoff/magento2-sendgrid-smtp)
# SendGrid SMTP Plugin for Magento2
Configure Magento to use SendGrid's SMTP server to send all transactional emails.

*Note:* You need a valid SendGrid account.

## 1. Install SendGrid SMTP Plugin
### Using Composer
```
composer require yukoff/magento2-sendgrid-smtp
```

### Using Source Code
```
composer config repositories.yukoff-sendgridsmtp git git@github.com:yukoff/magento2-sendgrid-smtp.git
composer require yukoff/magento2-sendgrid-smtp
```

### Manual Installation
Install SendGrid SMTP plugin for Magento2
 * Download the extension
 * Unzip the file
 * Create a folder {MAGENTO_ROOT}/app/code/SendGrid/SendGridSmtp
 * Copy the content from the unzipped folder

## 2. Enable SendGrid SMTP Plugin
```
php -f bin/magento module:enable --clear-static-content SendGrid_SendGridSmtp
php -f bin/magento setup:upgrade
```

## 3. Config SendGrid SMTP Plugin
Log into your Magetno2 backend, then goto `Store -> System -> SendGrid SMTP` and enter your email credentials, SendGrid category (if used) etc.
