<?php

namespace lookyman\U2fNette\Tests\Mock;

use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

class UserStorage implements IUserStorage
{

	public function getIdentity() {}
	public function getLogoutReason() {}
	public function isAuthenticated() {}
	public function setAuthenticated($state) {}
	public function setExpiration($time, $flags = 0) {}
	public function setIdentity(IIdentity $identity = NULL) {}

}
