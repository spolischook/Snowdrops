Referral test task
========================

After install project or clone with git, you may create you own parameters.yml
from app/config/parameters.yml.dist

Than type in console:

``` bash
php composer.phar update
php app/console doctrine:database:create
```

After that, and every time when you need start project again, type:

``` bash
php bin/reload.php
```
this script do for you all necessary actions

Warning!
You may define that some sites not provide HTTP referer, like Gmail.

Tests

coming soon...
