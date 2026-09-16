<?php

namespace App\Utils;

use Illuminate\Support\Facades\Hash;

class WordPressPassword
{
    /**
     * Verify a WordPress password hash
     * WordPress uses $wp$ prefix for bcrypt passwords (WordPress 6.8+)
     * Older WordPress uses phpass format ($P$)
     * 
     * @param string $password The plain text password
     * @param string $hash The WordPress password hash
     * @return bool
     */
    public static function verify($password, $hash)
    {
        if (empty($hash) || empty($password)) {
            return false;
        }

        // Use WordPressPasswordChecker which handles all WordPress password formats
        return \App\Utils\WordPressPasswordChecker::check($password, $hash);
    }
    
    /**
     * Check if a hash is WordPress format
     * 
     * @param string $hash
     * @return bool
     */
    public static function isWordPressFormat($hash)
    {
        return strpos($hash, '$wp$') === 0 || 
               strpos($hash, '$P$') === 0 || 
               strpos($hash, '$H$') === 0;
    }
}

