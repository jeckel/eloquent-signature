<?php
namespace Jeckel\EloquentSignature;

trait HasSignature
{
    /**
     * List of properties to include in the signature calculation
     * @var array
     */
    protected static $signatureProperties = [];

    /**
     * Salt to include in the signature calculation
     * @var string
     */
    protected static $signatureSalt = '';

    /**
     * Boot the Traits : register events
     */
    public static function bootHasSignature()
    {
        static::retrieved(function(Signable $model) {
            if ($model->shouldThrowExceptionOnRetrieve() && !$model->checkSignatureIsValid()) {
                throw new \Exception('Integrity check violation');
            }
        });

        static::saving(function(Signable $model) {
            $model->{$model->getSignatureFieldName()} = $model->generateSignature();
        });
    }

    /**
     * Define fields to use in the signature calculation
     * @param array $properties
     */
    protected static function setSignatureProperties(array $properties)
    {
        self::$signatureProperties = $properties;
    }

    /**
     * Define salt to include in the signature calculation
     * @param $salt
     */
    protected static function setSignatureSalt($salt)
    {
        self::$signatureSalt = $salt;
    }

    /**
     * Retrieve the field name where the signature will be stored
     * @return string
     */
    public function getSignatureFieldName()
    {
        return property_exists($this, 'signature_field_name') ? $this->signatureFieldName : 'signature';
    }

    /**
     * Check if the current model should throw an exception if the signature is invalid when retrieved
     * @return bool
     */
    public function shouldThrowExceptionOnRetrieve()
    {
        return (property_exists($this, 'throwExceptionOnRetrieve') && $this->throwExceptionOnRetrieve === true);
    }

    /**
     * @return string
     */
    public function generateSignature()
    {
        $properties = array_intersect_key($this->toArray(), array_flip(static::$signatureProperties));
        ksort($properties);
        return sha1(
            implode(
                static::$signatureSalt,
                $properties
            )
        );
    }

    /**
     * Check if signature is still valid
     * @return bool
     */
    public function checkSignatureIsValid()
    {
        return $this->{$this->getSignatureFieldName()} === $this->generateSignature();
    }
}
