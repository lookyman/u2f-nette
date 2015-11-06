<?php

namespace lookyman\U2fNette\Dialog;

use Nette\Application\UI\Form;
use Nette\Utils\Json;
use lookyman\U2f\Server\Helpers;
use lookyman\U2f\Server\RegisterResponse;

class RegisterDialog extends AbstractDialog
{

	public function formSuccess(Form $form)
	{
		try {
			$session = $this->getSession();
			list($id, $registerRequest) = $session->registrationData;
			$session->remove();

			if (!$this->getUser()->isLoggedIn()) {
				throw new \Exception('User not logged in.');

			} elseif ($id !== $this->getUser()->getId()) {
				throw new \Exception(sprintf('User id mismatch. %s stored in session, but %s is logged in.', $id, $this->getUser()->getId()));
			}

			$data = Json::decode($form->getValues()->data);
			if (isset($data->errorCode)) {
				throw new \Exception(sprintf('User error %s.', $data->errorCode));
			}

			$this->setRegistration($this->getServer()->register(
				$registerRequest,
				new RegisterResponse(
					Helpers::urlSafeBase64Decode($data->registrationData),
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
		$this->template->setFile($this->getTemplateFile() ?: __DIR__ . '/templates/RegisterDialog.latte');
		$this->template->user = $this->getUser();

		if ($this->getUser()->isLoggedIn()) {
			$session = $this->getSession();
			$session->registrationData = [
				$this->getUser()->getId(),
				$this->getServer()->createRegisterRequest($this->getRegistrationRepository()->findRegistrations($this->getUser()->getId())),
			];
			$this->template->registerRequest = $session->registrationData[1];
		}

		$this->template->render();
	}

}
