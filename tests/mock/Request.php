<?php

namespace lookyman\U2fNette\Tests\Mock;

use Nette\Http\IRequest;
use Nette\Http\UrlScript;

class Request implements IRequest
{

	public function getCookie($key, $default = NULL) {}
	public function getCookies() {}
	public function getFile($key) {}
	public function getFiles() {}
	public function getHeader($header, $default = NULL) {}
	public function getHeaders() {}
	public function getMethod() {}
	public function getPost($key = NULL, $default = NULL) {}
	public function getQuery($key = NULL, $default = NULL) {}
	public function getRawBody() {}
	public function getRemoteAddress() {}
	public function getRemoteHost() {}
	public function getUrl() { return new UrlScript; }
	public function isAjax() {}
	public function isMethod($method) {}
	public function isSecured() {}

}
