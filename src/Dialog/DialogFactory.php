<?php

namespace lookyman\U2fNette\Dialog;

use Nette\Http\Session;
use Nette\Security\User;
use lookyman\U2f\Server\Server;
use lookyman\U2fNette\User\IRegistrationRepository;

class DialogFactory
{

	/** @var Server */
	private $server;

	/** @var Session */
	private $session;

	/** @var User */
	private $user;

	/** @var IRegistrationRepository */
	private $registrationRepository;

	public function __construct(Server $server, Session $session, User $user, IRegistrationRepository $registrationRepository)
	{
		$this->server = $server;
		$this->session = $session;
		$this->user = $user;
		$this->registrationRepository = $registrationRepository;
	}

	/**
	 * @return RegisterDialog
	 */
	public function createRegisterDialog()
	{
		return new RegisterDialog($this->server, $this->session, $this->user, $this->registrationRepository);
	}

	/**
	 * @param int $id
	 * @return LoginDialog
	 */
	public function createLoginDialog($id)
	{
		return new LoginDialog($this->server, $this->session, $this->user, $this->registrationRepository, $id);
	}

}
