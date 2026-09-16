<?php

namespace App\Utils;

/**
 * WordPress Password Checker
 * 
 * This class implements WordPress's actual password verification logic
 * Based on WordPress source code: wp-includes/pluggable.php and wp-includes/class-phpass.php
 */
class WordPressPasswordChecker
{
    /**
     * Check password against WordPress hash
     * This is WordPress's actual password checking implementation
     * 
     * @param string $password Plain text password
     * @param string $hash WordPress password hash
     * @return bool
     */
    public static function check($password, $hash)
    {
        if (empty($hash) || empty($password)) {
            return false;
        }

        // WordPress 6.8+ uses $wp$ prefix for bcrypt
        if (strpos($hash, '$wp$') === 0) {
            return self::checkWordPressBcrypt($password, $hash);
        }

        // Old WordPress uses phpass format
        if (strpos($hash, '$P$') === 0 || strpos($hash, '$H$') === 0) {
            return self::checkPhpass($password, $hash);
        }

        // Standard bcrypt
        if (strpos($hash, '$2y$') === 0 || strpos($hash, '$2a$') === 0 || strpos($hash, '$2b$') === 0) {
            return password_verify($password, $hash);
        }

        return false;
    }

    /**
     * Check WordPress bcrypt password ($wp$2y$10$...)
     *
     * WordPress 6.8+ implementation:
     * According to wp-includes/pluggable.php wp_check_password():
     * - WordPress stores: $wp$2y$10$... (63 chars)
     * - Standard bcrypt: $2y$10$... (60 chars)
     * - WordPress uses HMAC SHA384 to hash the password first, then verifies against bcrypt
     * - Process: password -> HMAC-SHA384('wp-sha384', password) -> base64 -> verify against bcrypt
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private static function checkWordPressBcrypt($password, $hash)
    {
        if (strlen($hash) < 63) {
            return false;
        }

        // WordPress hash: $wp$2y$10$... (63 chars)
        // Remove "$wp" (first 3 chars): $2y$10$... (60 chars)
        $bcryptHash = substr($hash, 3);

        // Verify the hash length is correct (should be 60 chars for bcrypt)
        if (strlen($bcryptHash) !== 60) {
            return false;
        }

        // Verify the converted hash starts with valid bcrypt format
        if (strpos($bcryptHash, '$2y$') !== 0 && 
            strpos($bcryptHash, '$2a$') !== 0 && 
            strpos($bcryptHash, '$2b$') !== 0) {
            return false;
        }

        // WordPress 6.8+ method: Hash password with HMAC SHA384 first
        // This is the actual WordPress implementation from wp_check_password()
        $passwordToVerify = base64_encode(hash_hmac('sha384', $password, 'wp-sha384', true));

        // Use password_verify with the HMAC-hashed password against the bcrypt hash
        return password_verify($passwordToVerify, $bcryptHash);
    }

    /**
     * Check phpass password (legacy WordPress)
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private static function checkPhpass($password, $hash)
    {
        // Use WordPressHasher for phpass
        return WordPressHasher::check($password, $hash);
    }
}

