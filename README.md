# Install TYPO3 with composer

From root folder (folder which contains composer.json)
```bash
vendor/bin/typo3cms install:setup --non-interactive --database-user-name="database user name" --database-user-password="database user password" --database-name="database name" --admin-user-name="admin" --admin-password="password" --site-name="Site name"

```

**Optional arguments**

```bash
--database-host-name="localhost" 
--database-port="3306"
```

### Activate extensions
```bash
vendor/bin/typo3cms extension:activate EXT_KEY
```

# Links
* [TYPO3 composer install](https://typo3.com/blog/how-to-install-typo3-using-composer-in-less-than-5-minutes/)
* [TYPO3 console extension](https://github.com/TYPO3-Console/typo3_console)
