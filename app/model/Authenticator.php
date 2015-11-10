<?php

namespace App\Model;

use Nette,
	Nette\Security;


/**
 * Users authenticator.
 */
class Authenticator extends Nette\Object implements Security\IAuthenticator
{
	/** @var Nette\Database\Context */
	private $database;
	
	private $identity = null;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;
	}

	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($username, $password) = $credentials;
		$row = $this->database->table('users')->where('us_email', $username)->fetch();

		if (!$row) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!User::verify($password, $row->us_password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$arr = $row->toArray();
		
		unset($arr['us_password']);
		return $this->identity = new Security\Identity($row->us_ID, NULL, $arr);
	}
	
	public function getIdentity() {
		return $this->identity;
	}

}
