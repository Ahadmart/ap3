<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        require_once __DIR__ . '/../vendors/password_compat/password.php';
        $username = strtolower($this->username);
        $user = User::model()->find('LOWER(nama)=?', array($username));
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->nama;
            $this->setState('namaLengkap', $user->nama_lengkap);
            $this->setState('lastLogon', $user->last_logon);
            $this->setState('lastIpaddress', $user->last_ipaddress);
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}
