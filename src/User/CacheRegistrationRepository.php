<?php

namespace lookyman\U2fNette\User;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use lookyman\U2f\Server\Registration;
use lookyman\U2f\Server\RegistrationCollection;

class CacheRegistrationRepository implements IRegistrationRepository
{

	/** @var Cache */
	private $cache;

	public function __construct(IStorage $storage)
	{
		$this->cache = new Cache($storage, 'lookyman.U2f');
	}

	public function findRegistrations($id)
	{
		return $this->cache->load((int) $id, function () {
			return new RegistrationCollection;
		});
	}

	public function saveRegistration($id, Registration $registration)
	{
		$registrations = $this->findRegistrations((int) $id);
		$registrations->add($registration);
		$this->cache->save((int) $id, $registrations);
		return $this;
	}

	public function clearRegistrations($id)
	{
		$this->cache->save((int) $id, new RegistrationCollection);
		return $this;
	}

}
