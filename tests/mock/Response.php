<?php

namespace lookyman\U2fNette\Tests\Mock;

use Nette\Http\IResponse;

class Response implements IResponse
{

	public function addHeader($name, $value) {}
	public function deleteCookie($name, $path = NULL, $domain = NULL, $secure = NULL) {}
	public function getCode() {}
	public function getHeader($header, $default = NULL) {}
	public function getHeaders() {}
	public function isSent() {}
	public function redirect($url, $code = self::S302_FOUND) {}
	public function setCode($code) {}
	public function setContentType($type, $charset = NULL) {}
	public function setCookie($name, $value, $expire, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL) {}
	public function setExpiration($seconds) {}
	public function setHeader($name, $value) {}

}
