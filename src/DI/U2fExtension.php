<?php

namespace lookyman\U2fNette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\ServiceCreationException;
use Nette\Http\IRequest;
use Nette\Utils\Validators;
use lookyman\U2f\Entropy\IEntropyProvider;
use lookyman\U2f\Entropy\OpenSslEntropyProvider;
use lookyman\U2f\Server\Config;
use lookyman\U2f\Server\Server;
use lookyman\U2fNette\Dialog\DialogFactory;
use lookyman\U2fNette\User\CacheRegistrationRepository;
use lookyman\U2fNette\User\IRegistrationRepository;

class U2fExtension extends CompilerExtension
{

	/** string */
	const APP_ID_DETECT = 'detect';

	/** @var array */
	public $defaults = [
		'appId' => self::APP_ID_DETECT,
		'attestDir' => NULL,
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('entropyProvider'))
			->setClass(IEntropyProvider::class)
			->setFactory(OpenSslEntropyProvider::class);

		Validators::assertField($config, 'appId', 'string');
		Validators::assertField($config, 'attestDir', 'string|null');

		$builder->addDefinition($this->prefix('config'))
			->setClass(Config::class, $config);

		$builder->addDefinition($this->prefix('server'))
			->setClass(Server::class);

		$builder->addDefinition($this->prefix('registrationRepository'))
			->setClass(IRegistrationRepository::class)
			->setFactory(CacheRegistrationRepository::class);

		$builder->addDefinition($this->prefix('dialogFactory'))
			->setClass(DialogFactory::class);
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		if ($config['appId'] === self::APP_ID_DETECT) {
			if ($httpRequest = $builder->getByType(IRequest::class)) {
				$config['appId'] = $builder->literal(sprintf("rtrim(\$this->getService('%s')->getUrl()->getBaseUrl(), '/')", $httpRequest));
				$builder->getDefinition($this->prefix('config'))
					->setArguments($config);

			} else {
				throw new ServiceCreationException(sprintf('Automatic appId detect requires service of type %s, none found.', IRequest::class));
			}
		}

	}

}
