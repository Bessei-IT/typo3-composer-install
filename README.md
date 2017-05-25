# Install TYPO3 with composer

From root folder (folder which contains composer.json)
```bash
vendor/bin/typo3cms install:setup --non-interactive --database-user-name="database user name" --database-user-password="database user password" --database-name="database name" --admin-user-name="admin" --admin-password="password" --site-name="Site name"
```

### Optional arguments

```bash
--database-host-name="localhost" 
--database-port="3306"
```