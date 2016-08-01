# Magento 2 SparkPost module

This module enhances the magento 2 capabilities to send transactional mails
with SparkPost service.

## Installation

Add the module to your composer file.

```json
{
  "require": {
    "opsway/module-sparkpost": "dev-master"
  }
}
```


Install the module with composer.

```bash

    composer update

```

On succeed, install the module via bin/magento console.

```bash

    bin/magento cache:clean

    bin/magento module:install OpsWay_EmailSparPost

    bin/magento setup:upgrade

```