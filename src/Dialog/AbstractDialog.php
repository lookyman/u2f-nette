<?php

namespace lookyman\U2fNette\Dialog;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\Session;
use Nette\Security\User;
use lookyman\U2f\Server\Registration;
use lookyman\U2f\Server\Server;
use lookyman\U2fNette\User\IRegistrationRepository;

abstract class AbstractDialog extends Control
{

	/** string */
	const SESSION_SECTION = 'lookyman.U2f';

	/** @var callable[] */
	public $onResponse;

	/** @var callable[] */
	public $onError;

	/** @var Registration|NULL */
	private $registration;

	/** @var IRegistrationRepository */
	private $registrationRepository;

	/** @var Server */
	private $server;

	/** @var Session */
	private $session;

	/** @var string */
	private $templateFile;

	/** @var User */
	private $user;

	public function __construct(Server $server, Session $session, User $user, IRegistrationRepository $registrationRepository)
	{
		parent::__construct();
		$this->server = $server;
		$this->session = $session;
		$this->user = $user;
		$this->registrationRepository = $registrationRepository;
	}

	abstract public function formSuccess(Form $form);

	/**
	 * @return Registration|NULL
	 */
	public function getRegistration()
	{
		return $this->registration;
	}

	/**
	 *
	 * @param string $templateFile
	 * @return self
	 */
	public function setTemplateFile($templateFile = NULL)
	{
		$this->templateFile = $templateFile;
		return $this;
	}

	/**
	 * @return Form
	 */
	protected function createComponentForm()
	{
		$form = new Form;
		$form->addProtection();
		$form->addHidden('data')->setRequired();
		$form->onSuccess[] = [$this, 'formSuccess'];
		return $form;
	}

	/**
	 * @return IRegistrationRepository
	 */
	protected function getRegistrationRepository()
	{
		return $this->registrationRepository;
	}

	/**
	 * @return Server
	 */
	protected function getServer()
	{
		return $this->server;
	}

	/**
	 * @return \Nette\Http\SessionSection
	 */
	protected function getSession()
	{
		return $this->session->getSection(self::SESSION_SECTION);
	}

	/**
	 * @return string
	 */
	protected function getTemplateFile()
	{
		return $this->templateFile;
	}

	/**
	 * @return User
	 */
	protected function getUser()
	{
		return $this->user;
	}

	/**
	 * @return self
	 */
	protected function setRegistration(Registration $registration = NULL)
	{
		$this->registration = $registration;
		return $this;
	}

}
