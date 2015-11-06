<?php

namespace lookyman\U2fNette\Tests\DI;

use Nette\DI\Compiler;
use Nette\DI\Config\Helpers;
use Nette\DI\ContainerLoader;
use lookyman\U2fNette\DI\U2fExtension;
use lookyman\U2f\Entropy\IEntropyProvider;
use lookyman\U2f\Entropy\OpenSslEntropyProvider;
use lookyman\U2f\Server\Config;
use lookyman\U2f\Server\Server;
use lookyman\U2fNette\Dialog\DialogFactory;
use lookyman\U2fNette\User\CacheRegistrationRepository;
use lookyman\U2fNette\User\IRegistrationRepository;

class U2fExtensionTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider servicesDataProvider
	 */
	public function testServices($config)
	{
		$container = $this->createContainer(Helpers::merge($this->getDefaultConfig(), $config));

		$this->assertInstanceOf(OpenSslEntropyProvider::class, $container->getByType(IEntropyProvider::class));
		$this->assertInstanceOf(Config::class, $container->getByType(Config::class));
		$this->assertInstanceOf(Server::class, $container->getByType(Server::class));
		$this->assertInstanceOf(CacheRegistrationRepository::class, $container->getByType(IRegistrationRepository::class));
		$this->assertInstanceOf(DialogFactory::class, $container->getByType(DialogFactory::class));
	}

	/**
	 * @return array
	 */
	public function servicesDataProvider()
	{
		return [
			[['u2f' => []]],
			[['u2f' => ['appId' => U2fExtension::APP_ID_DETECT]]],
			[['u2f' => ['appId' => 'a']]],
			[['u2f' => ['attestDir' => TEMP_DIR]]],
		];
	}

	/**
	 * @param array $config
	 * @return \Nette\DI\Container
	 */
	private function createContainer(array $config)
	{
		$loader = new ContainerLoader(TEMP_DIR, TRUE);
		$class = $loader->load($config, function (Compiler $compiler) use ($config) {
			$compiler->addExtension('u2f', new U2fExtension);
			$compiler->addConfig($config);
		});
		$container = new $class;
		$container->initialize();
		return $container;
	}

	/**
	 * @return array
	 */
	private function getDefaultConfig()
	{
		return [
			'services' => [
				'Nette\Caching\Storages\MemoryStorage',
				'Nette\Http\Session',
				'Nette\Security\User',
				'lookyman\U2fNette\Tests\Mock\Request',
				'lookyman\U2fNette\Tests\Mock\Response',
				'lookyman\U2fNette\Tests\Mock\UserStorage',
			],
		];
	}

}
