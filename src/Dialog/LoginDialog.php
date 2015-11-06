<?php

namespace lookyman\U2fNette\Dialog;

use Nette\Application\UI\Form;
use Nette\Http\Session;
use Nette\Security\User;
use Nette\Utils\Json;
use lookyman\U2f\Server\Helpers;
use lookyman\U2f\Server\Server;
use lookyman\U2f\Server\SignResponse;
use lookyman\U2fNette\User\IRegistrationRepository;

class LoginDialog extends AbstractDialog
{

	/** @var int */
	private $id;

	/**
	 * @param int $id
	 */
	public function __construct(Server $server, Session $session, User $user, IRegistrationRepository $registrationRepository, $id)
	{
		parent::__construct($server, $session, $user, $registrationRepository);
		$this->id = $id;
	}

	public function formSuccess(Form $form)
	{
		try {
			$session = $this->getSession();
			list($id, $signRequests) = $session->authenticationData;
			$session->remove();

			if ($this->getUser()->isLoggedIn()) {
				throw new \Exception('User is already logged in.');

			} elseif ($id !== $this->id) {
				throw new \Exception(sprintf('User id mismatch. %s stored in session, but %s is expected.', $id, $this->id));
			}

			$data = Json::decode($form->getValues()->data);
			if (isset($data->errorCode)) {
				throw new \Exception(sprintf('User error %s.', $data->errorCode));
			}

			$this->setRegistration($this->getServer()->authenticate(
				$signRequests,
				$this->getRegistrationRepository()->findRegistrations($this->id),
				new SignResponse(
					Helpers::urlSafeBase64Decode($data->keyHandle),
					Helpers::urlSafeBase64Decode($data->signatureData),
					Helpers::urlSafeBase64Decode($data->clientData)
				)
			));
			$this->getRegistrationRepository()->saveRegistration($id, $this->getRegistration());

		} catch (\Exception $e) {
			$this->setRegistration();
			$this->onError($e);
		}

		$this->onResponse($this);
	}

	public function render()
	{
		$this->template->setFile($this->getTemplateFile() ?: __DIR__ . '/templates/LoginDialog.latte');
		$this->template->user = $this->getUser();

		if (!$this->getUser()->isLoggedIn()) {
			$session = $this->getSession();
			$session->authenticationData = [
				$this->id,
				$this->getServer()->createSignRequests($this->getRegistrationRepository()->findRegistrations($this->id)),
			];
			$this->template->signRequests = $session->authenticationData[1];
		}

		$this->template->render();
	}

}
