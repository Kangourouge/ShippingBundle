# ShippingBundle

Configuration
-------------

AppKernel
---------

```php
# app/AppKernel.php

<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new KRG\ShippingBundle\KRGShippingBundle()
        // ...
    );
}
```


Configuration
-------------

```yaml
# app/config/config.yml

doctrine:
    dbal:
        mapping_types:
            enum: string
            set: string
        types:
        
    orm:
        resolve_target_entities:
            KRG\ShippingBundle\Entity\ShippingInterface: AppBundle\Entity\Shipping

...
krg_shipping:
    transports:
        dhl:
            url: %dhl_url%
            site_id: %dhl_site_id%
            password: %dhl_password%
            account_number: %dhl_account_number%
        ups:
            access_key: %ups_access_key%
            user_id: %ups_user_id%
            password: %ups_password%
        fedex:
            key: %fedex_key%
            account_number: %fedex_account_number%
            meter_number: %fedex_meter_number%
            password: %fedex_password%   
```

Routing
-------

```yaml
# app/config/routing.yml

krg_shipping:
    resource: '@KRGShippingBundle/Resources/config/routing.yml'
```

Entity
------

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KRG\ShippingBundle\Entity\Shipping as BaseShipping;

/**
 * @ORM\Entity
 * @ORM\Table(name="shipping")
 */
class Shipping extends BaseShipping
{
}
```


Dependencies
------

```json
"gabrielbull/ups-api":               "0.7.*",
"jeremy-dunn/php-fedex-api-wrapper": "1.1.*"
```
