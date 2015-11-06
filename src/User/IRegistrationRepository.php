<?php

namespace lookyman\U2fNette\User;

use lookyman\U2f\Server\Registration;

interface IRegistrationRepository
{

	/**
	 * @param int $id
	 * @return \lookyman\U2f\Server\RegistrationCollection
	 */
	function findRegistrations($id);

	/**
	 * @param int $id
	 * @return self
	 */
	function saveRegistration($id, Registration $registration);

	/**
	 * @param int $id
	 * @return self
	 */
	function clearRegistrations($id);

}
