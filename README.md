# Install TYPO3 with composer

## Create project
### Stable
```bash
composer create-project bit/typo3-composer-install
```

### Current master
```bash
composer create-project bit/typo3-composer-install:dev-master
```

## Install TYPO3

### Using [TYPO3 console](https://github.com/TYPO3-Console/typo3_console)
From root folder (folder which contains composer.json)
```bash
vendor/bin/typo3cms install:setup --non-interactive --database-user-name="database user name" --database-user-password="database user password" --database-name="database name" --admin-user-name="admin" --admin-password="password" --site-name="Site name"
```
Check out the typo3 console documentation for more examples:
https://docs.typo3.org/typo3cms/extensions/typo3_console/Introduction/

### Using TYPO3 console wrapper
```bash
bin/console typo3:install database-name database-username database-password site-name 
```

## Activate often used system extensions
```bash
bin/console typo3:activate
```

This activates:
* belog
* beuser
* context_help
* fluid_styled_content
* info
* info_pagetsconfig
* lowlevel
* reports
* rsaauth
* rte_ckeditor
* setup
* t3editor
* tstemplate

# Links
* [TYPO3 composer install](https://typo3.com/blog/how-to-install-typo3-using-composer-in-less-than-5-minutes/)
* [TYPO3 console extension](https://github.com/TYPO3-Console/typo3_console)
* [TYPO3 distribution](https://github.com/helhum/TYPO3-Distribution)
