<?php

namespace lookyman\U2fNette\Tests\User;

use Nette\Caching\Storages\MemoryStorage;
use lookyman\U2f\Server\Helpers;
use lookyman\U2f\Server\Registration;
use lookyman\U2f\Server\RegistrationCollection;
use lookyman\U2fNette\User\CacheRegistrationRepository;

class CacheRegistrationRepositoryTest extends \PHPUnit_Framework_TestCase
{

	public function testGetSet()
	{
		$repository = new CacheRegistrationRepository(new MemoryStorage);

		$result1 = $repository->findRegistrations(1);
		$this->assertInstanceOf(RegistrationCollection::class, $result1);
		$this->assertCount(0, $result1);

		$this->assertSame($repository, $repository->saveRegistration(1, new Registration($this->getPublicKey(), 'a', 'b')));
		$this->assertSame($repository, $repository->saveRegistration(2, new Registration($this->getPublicKey(), 'c', 'd')));
		$this->assertSame($repository, $repository->saveRegistration(2, (new Registration($this->getPublicKey(), 'e', 'f'))->setCounter(1)));

		$result2 = $repository->findRegistrations(1);
		$this->assertInstanceOf(RegistrationCollection::class, $result2);
		$this->assertCount(1, $result2);
		list($first) = iterator_to_array($result2);
		// @todo

		$result3 = $repository->findRegistrations(2);
		$this->assertInstanceOf(RegistrationCollection::class, $result3);
		$this->assertCount(2, $result3);
		// @todo
	}

	private function getPublicKey()
	{
		return "\x04" . str_repeat("\0", Helpers::PUBLIC_KEY_LENGTH - 1);
	}

}
