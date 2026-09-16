<?php

namespace App\Utils;

/**
 * WordPress Password Hasher
 *
 * This is a proper implementation of WordPress password hashing
 * compatible with WordPress 6.8+ bcrypt hashes
 *
 * Based on WordPress source code:
 * https://github.com/WordPress/WordPress/blob/master/wp-includes/class-wp-hasher.php
 */
class WordPressHasher
{
    /**
     * WordPress phpass hasher - For legacy passwords
     * This is the old hashing method used by WordPress before bcrypt
     */
    private static $itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * Check if password matches WordPress hash
     * Supports both phpass ($P$, $H$) and bcrypt ($2y$, $wp$) formats
     *
     * @param string $password Plain text password
     * @param string $hash WordPress password hash
     * @return bool
     */
    public static function check($password, $hash)
    {
        // Handle WordPress bcrypt format ($wp$2y$10$...)
        if (strpos($hash, '$wp$') === 0) {
            return self::checkBcryptPassword($password, $hash);
        }

        // Handle standard bcrypt format ($2y$, $2a$, $2b$)
        if (strpos($hash, '$2y$') === 0 || strpos($hash, '$2a$') === 0 || strpos($hash, '$2b$') === 0) {
            return password_verify($password, $hash);
        }

        // Handle phpass format ($P$, $H$)
        if (strpos($hash, '$P$') === 0 || strpos($hash, '$H$') === 0) {
            return self::checkPhpassPassword($password, $hash);
        }

        return false;
    }

    /**
     * Check WordPress bcrypt password
     * WordPress 6.8+ uses bcrypt with $wp$ prefix
     *
     * WordPress 6.8 implementation (from wp-includes/pluggable.php):
     * - WordPress stores: $wp$2y$10$... (63 chars)
     * - Standard bcrypt: $2y$10$... (60 chars)
     * - WordPress uses HMAC SHA384 to hash password first, then verifies against bcrypt
     * - Process: password -> HMAC-SHA384('wp-sha384', password) -> base64 -> verify
     *
     * @param string $password Plain text password
     * @param string $hash WordPress bcrypt hash ($wp$2y$10$...)
     * @return bool
     */
    private static function checkBcryptPassword($password, $hash)
    {
        if (strlen($hash) < 4 || substr($hash, 0, 4) !== '$wp$') {
            return false;
        }

        // WordPress hash: $wp$2y$10$... (63 chars)
        // Remove "$wp" (3 chars): $2y$10$... (60 chars)
        $bcryptHash = substr($hash, 3);

        // WordPress 6.8+ method: Hash password with HMAC SHA384 first
        // This matches the actual WordPress implementation from wp_check_password()
        $passwordToVerify = base64_encode(hash_hmac('sha384', $password, 'wp-sha384', true));

        return password_verify($passwordToVerify, $bcryptHash);
    }

    /**
     * Check phpass password (legacy WordPress)
     * WordPress uses MD5-based phpass before bcrypt
     *
     * @param string $password Plain text password
     * @param string $hash WordPress phpass hash ($P$...)
     * @return bool
     */
    private static function checkPhpassPassword($password, $hash)
    {
        // phpass format: $P$B or $H$9 (where letter = iteration count)
        if (strlen($hash) < 34) {
            return false;
        }

        // Get iteration count from hash[3]
        $itoa64 = self::$itoa64;
        $count_log2 = strpos($itoa64, $hash[3]);

        if ($count_log2 < 7 || $count_log2 > 30) {
            return false;
        }

        $count = 1 << $count_log2;
        $salt = substr($hash, 4, 8);

        // Hash the password
        $hash1 = md5($salt . $password, true);
        for ($i = 0; $i < $count; $i++) {
            $hash1 = md5($hash1 . $password, true);
        }

        // Encode hash
        $out = substr($hash, 0, 12);
        $i = 0;
        do {
            $value = ord($hash1[$i++]);
            $out .= $itoa64[$value & 0x3f];
            if ($i < 16) {
                $value |= ord($hash1[$i]) << 8;
            }
            $out .= $itoa64[($value >> 6) & 0x3f];
            if ($i++ >= 16) {
                break;
            }
            if ($i < 16) {
                $value |= ord($hash1[$i]) << 16;
            }
            $out .= $itoa64[($value >> 12) & 0x3f];
            if ($i++ >= 16) {
                break;
            }
        } while ($i < 16);

        return $out === $hash;
    }

    /**
     * Hash a password using WordPress method
     * Uses bcrypt like modern WordPress
     *
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hash($password)
    {
        // Use standard Laravel bcrypt which is compatible
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
}
