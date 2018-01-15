# eloquent-signature

Add a signature integrity check in your Eloquent model

# Installation

To get started, install secure-eloquent via Composer:

```php
composer require jeckel/eloquent-signature
```

Next add the `HasSignature` trait and the `Signable` interface to your model

```php
<?php
namespace App;

use Jeckel\EloquentSignature\HasSignature;
use Jeckel\EloquentSignature\Signable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Signable
{
    use HasSignature;
    
    /**
     * Required 
     */
    protected static $signatureProperties = ['login', 'email', 'password'];
    
    /**
     * Required   
     */
    protected static $signatureSalt = 'MySalt';
    
    /**
     * Optional (default: 'signature')  
     */
    protected $signatureFieldName = 'signature';
    
    /**
     * Optional (default: false) 
     */
    protected $throwExceptionOnRetrieve = false;
}
```

Field description :

- `signatureProperties`: fields which will be include in the signature check, if any of this field is updated without updating the model will cause an integrity check error
- `signatureSalt`: String to include in the signature calculation
- `signatureFieldName`: name of the field in your model where the calculated signature will be stored
- `throwExceptionOnRetrieve`: if set to true, all find queries will throw an exception if the signature retrieved is invalid. Using this options is more secure but need some implementation on your side to handle this exception.


Methods :

- `checkSignatureIsValid`: allow you to check the signature when you need in your code.
