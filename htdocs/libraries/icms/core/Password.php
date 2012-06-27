<?php
/**
 * Class to encrypt User Passwords.
 *
 * @category	ICMS
 * @package		Core
 * @since		1.2
 * @author		vaughan montgomery (vaughan@impresscms.org)
 * @author		ImpressCMS Project
 * @copyright	(c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version		SVN: $Id$
 **/
/**
 * Password generation and validation
 *
 * @category	ICMS
 * @package		Core
 * @subpackage  Password
 *
 */
final class icms_core_Password {
	private $pass, $salt, $mainSalt = XOOPS_DB_SALT, $uname;

	/**
	 * Constructor for the Password class
	 */
	public function __construct() {
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $instance
	 * @staticvar    object
	 **/
	static public function getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_core_Password();
		}
		return $instance;
	}

	// ***** Private Functions *****

	/**
	 * This Private Function checks whether a users password has been expired
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $uname      The username of the account to be checked
	 * @return   bool     returns true if password is expired, false if password is not expired.
	 **/
	private function priv_passExpired($uname) {
		if (!isset($uname) || (isset($uname) && $uname == '')) {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}

		$uname = @htmlspecialchars($uname, ENT_QUOTES, _CHARSET);

		$sql = icms::$xoopsDB->query(sprintf("SELECT pass_expired FROM %s WHERE uname = %s",
			icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
		list($pass_expired) = icms::$xoopsDB->fetchRow($sql);

		if ($pass_expired == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This Private Function returns the User Salt key belonging to username.
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $uname      Username to find User Salt key for.
	 * @return   string  returns the Salt key of the user.
     * 
     * To be removed!
	 **/
	private function priv_getUserSalt($uname) {
		if (!isset($uname) || (isset($uname) && $uname == '')) {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}

		$table = new icms_db_legacy_updater_Table('users');
		$uname = @htmlspecialchars($uname, ENT_QUOTES, _CHARSET);

		if ($table->fieldExists('loginname')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT salt FROM %s WHERE loginname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
			list($salt) = icms::$xoopsDB->fetchRow($sql);
		} elseif ($table->fieldExists('login_name')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT salt FROM %s WHERE login_name = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
			list($salt) = icms::$xoopsDB->fetchRow($sql);
		} else {
			$sql = icms::$xoopsDB->query(sprintf("SELECT salt FROM %s WHERE uname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
			list($salt) = icms::$xoopsDB->fetchRow($sql);
		}

		return $salt;
	}

    /**
     * This Private Function returns the User Encryption Type belonging to username.
     * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
     * @since    1.2.3
     * @param    string  $uname      Username to find Enc_type for.
     * @return   string  returns the Encryption type of the user.
     * 
     * To be removed!
     **/
    private function priv_getUserEncType($uname) {
		if (!isset($uname) || (isset($uname) && $uname == '')) {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}

		$table = new icms_db_legacy_updater_Table('users');
		$uname = @htmlspecialchars($uname, ENT_QUOTES, _CHARSET);

        if($table->fieldExists('loginname')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT enc_type FROM %s WHERE loginname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($enc_type) = icms::$xoopsDB->fetchRow($sql);
        } elseif($table->fieldExists('login_name')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT enc_type FROM %s WHERE login_name = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($enc_type) = icms::$xoopsDB->fetchRow($sql);
        } else {
            $sql = icms::$xoopsDB->query(sprintf("SELECT enc_type FROM %s WHERE uname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($enc_type) = icms::$xoopsDB->fetchRow($sql);
        }

		return (int) $enc_type;
    }

    /**
     * This Private Function returns the User Password Hash belonging to username.
     * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
     * @since    1.3.3
     * @param    string  $uname      Username to find hash for.
     * @return   string  returns the Password hash of the user.
     **/
    private function priv_getUserHash($uname) {
		if (!isset($uname) || (isset($uname) && $uname == '')) {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}

		$table = new icms_db_legacy_updater_Table('users');
		$uname = @htmlspecialchars($uname, ENT_QUOTES, _CHARSET);

        if($table->fieldExists('loginname')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT pass FROM %s WHERE loginname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($pass) = icms::$xoopsDB->fetchRow($sql);
        } elseif($table->fieldExists('login_name')) {
			$sql = icms::$xoopsDB->query(sprintf("SELECT pass FROM %s WHERE login_name = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($pass) = icms::$xoopsDB->fetchRow($sql);
        } else {
            $sql = icms::$xoopsDB->query(sprintf("SELECT pass FROM %s WHERE uname = %s",
				icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname)));
            list($pass) = icms::$xoopsDB->fetchRow($sql);
        }

		return $pass;
    }

    /**
	 * This Private Function is used to Encrypt User Passwords
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $pass       plaintext password to be encrypted
	 * @param    string  $salt       unique user salt key used in encryption process
	 * @param    int     $enc_type   encryption type to use (this is required & only used when passwords are expired)
	 * @param    int     $reset      set to 1 if we have determined that the user password has been expired
	 *                               use in conjunction only with $enc_type above.
	 * @return   Hash of users password.
     * 
     * To be removed!, use priv_encryptPassword() instead
	 **/
	private function priv_encryptPass($pass, $salt, $enc_type) {
		if ($enc_type == 0) {
			return md5($pass);
		} else {
			$pass = $salt . md5($pass) . $this->mainSalt;

            $type = array();
            $type['encType'] = array(
                                        1 => 'sha256',
                                        2 => 'sha384',
                                        3 => 'sha512',
                                        4 => 'ripemd128',
                                        5 => 'ripemd160',
                                        6 => 'whirlpool',
                                        7 => 'haval128,4',
                                        8 => 'haval160,4',
                                        9 => 'haval192,4',
                                        10 => 'haval224,4',
                                        11 => 'haval256,4',
                                        12 => 'haval128,5',
                                        13 => 'haval160,5',
                                        14 => 'haval192,5',
                                        15 => 'haval224,5',
                                        16 => 'haval256,5',
                                    );
            
            return hash($type['encType'][$enc_type], $pass);
        }
    }

	/**
	 * This Private Function is used to Encrypt User Passwords
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.3.3
	 * @param    string  $pass       plaintext password to be encrypted
	 * @param    string  $salt       unique user salt key used in encryption process
	 * @param    int     $enc_type   encryption type to use.
	 * @return   Hash of users password.
	 **/
	private function priv_encryptPassword($pass, $salt, $enc_type, $iterations) {
		if ($enc_type == 20) {
			return '$' . $enc_type . '$0$' . md5($pass); // this should never be used. should be removed???
		} else {
            $hash = '$' . $enc_type . '$' . $iterations . '$' . $salt . '-' . self::priv_rehash(
                                        self::priv_rehash($salt, $iterations) . 
                                        self::priv_rehash($pass, $iterations) . 
                                        self::priv_rehash($this->mainSalt, $iterations),
                                        $iterations, $enc_type);
            
            return $hash;
		}
	}

	/**
	 * This Private Function rehashes (stretches) the Password Hash
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.3.3
	 * @param    string     $hash           hash to be re-hashed (stretched)
	 * @param    int        $iterations     Number of times to re-hash
	 * @return   Hash of users password.
	 **/
    private function priv_rehash($hash, $iterations, $enc_type = 21) {
        $type['encType'] = array(
                                    21 => 'sha256',
                                    22 => 'sha384',
                                    23 => 'sha512',
                                    24 => 'ripemd128',
                                    25 => 'ripemd160',
                                    26 => 'whirlpool',
                                    27 => 'haval128,4',
                                    28 => 'haval160,4',
                                    29 => 'haval192,4',
                                    30 => 'haval224,4',
                                    31 => 'haval256,4',
                                    32 => 'haval128,5',
                                    33 => 'haval160,5',
                                    34 => 'haval192,5',
                                    35 => 'haval224,5',
                                    36 => 'haval256,5',
                                    37 => 'ripemd256',
                                    38 => 'ripemd320',
                                    39 => 'snefru256',
                                    40 => 'gost'
            
                                );

        for ($i = 0; $i < $iterations; ++$i) {
            $hashed = hash($type['encType'][$enc_type], $hash . $hash);
        }
        
        return $hashed;
    }
    
	/**
	 * This Private Function verifies if the password is correct
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.3.3
	 * @param    string     $pass       Password to be verified
	 * @param    string     $uname      Username of password to be verified
	 * @return   mixed      returns password HASH if correct, returns false if incorrect
	 **/
    private function priv_verifyPassword($pass, $uname) {
        $userSalt = self::priv_getUserSalt($uname); // to be removed!
        $userHash = self::priv_getUserHash($uname);
        
        if(preg_match_all("/(\\$)(\\d+)(\\$)(\\d+)(\\$)((?:[a-z0-9_]*))(-)((?:[a-z0-9_]*))/is", $userHash, $matches)) {
            $encType = (int) $matches[2][0];
            $iterations = (int) $matches[4][0];
            $userSalt = $matches[6][0];
            
            if (self::priv_encryptPassword($pass, $userSalt, $encType, $iterations) == $userHash) {
                return $userHash;
            }
        } else { // to be removed!
            $encType = self::priv_getUserEncType($uname);
            
            if (self::priv_encryptPass($pass, $userSalt, $encType) == $userHash) {
                return $userHash;
            }
        }
        
        return false;
    }

    // ***** Public Functions *****

	/**
	 * This Function creates a unique random Salt Key for use with password encryptions
	 * It can also be used to generate a random AlphaNumeric key sequence of any given length.
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $slength    The length of the key to produce
	 * @return   string  returns the generated random key.
	 **/
	static public function createSalt($slength=64) {
		$salt = '';
		$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$microtime = function_exists('microtime') ? microtime() : time();
		srand((double)$microtime * 1000000);
		for ($i=0; $i<=$slength; $i++)
		$salt.= substr($base, rand() % strlen($base), 1);
        
		return $salt;
	}

	/**
	 * This Public Function checks whether a users password has been expired
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $uname      The username of the account to be checked
	 * @return   bool     returns true if password is expired, false if password is not expired.
	 **/
	public function passExpired($uname = '') {
		return self::priv_passExpired($uname);
	}

	/**
	 * This Public Function returns the User Salt key belonging to username.
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $uname      Username to find User Salt key for.
	 * @return   string  returns the Salt key of the user.
     * 
     * To be removed!
	 **/
	public function getUserSalt($uname = '') {
		return self::priv_getUserSalt($uname);
	}

    /**
     * This Public Function returns the User Encryption Type belonging to username.
     * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
     * @since    1.1
     * @param    string  $uname      Username to find Encryption Type for.
     * @return   string  returns the Encryption Type of the user.
     * 
     * to be removed!
     **/
    public function getUserEncType($uname = '')
    {
        return self::priv_getUserEncType($uname);
    }

	/**
	 * This Public Function is used to Encrypt User Passwords
	 * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
	 * @since    1.1
	 * @param    string  $pass       plaintext password to be encrypted
	 * @return   Hash of users password.
	 **/
	public function encryptPass($pass) {
        global $icmsConfigUser;
        
        $salt = self::createSalt();
        $iterations = 500;
        $enc_type = (int) $icmsConfigUser['enc_type'];
        
        return self::priv_encryptPassword($pass, $salt, $enc_type, $iterations);
	}
    
    /**
     * This Public Function verifies if the users password is correct.
     * @copyright (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
     * @since    1.3.3
     * @param    string  $uname      Username to verify.
     * @param    string  $pass       Password to verify.
     * @return   mixed      returns Hash if correct, returns false if incorrect.
     **/
    public function verifyPass($pass = '', $uname = '') {
        if (!isset($pass) || !isset($uname)) {
            return false;
        }
        
        return self::priv_verifyPassword($pass, $uname);
    }
}