<?php

namespace GetCandy\Plugins\LegacyPassword\Hashing;

use Illuminate\Hashing\BcryptHasher;

class LegacyHasher extends BcryptHasher
{
    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (!is_array($hashedValue) && strlen($hashedValue) === 0) {
            return false;
        }
        if (is_array($hashedValue)) {
            return $this->checkMd5($value, $hashedValue);
        } else {
            return password_verify($value, $hashedValue);
        }
    }

    protected function checkMd5($value, $hash)
    {
        return md5($value . $hash['salt']) == $hash['password'];
    }
}
