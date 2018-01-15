<?php
namespace Jeckel\EloquentSignature;

interface Signable
{
    /**
     * Retrieve the field name where the signature will be stored
     * @return string
     */
    public function getSignatureFieldName();

    /**
     * Check if the current model should throw an exception if the signature is invalid when retrieved
     * @return bool
     */
    public function shouldThrowExceptionOnRetrieve();

    /**
     * @return string
     */
    public function generateSignature();

    /**
     * @return bool
     */
    public function checkSignatureIsValid();
}
