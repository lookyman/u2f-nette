# u2f-nette


Requirements
------

lookyman/u2f-nette requires PHP 5.5 or higher with OpenSSL 1.0.0 or higher enabled.

- [Nette Framework](https://github.com/nette/nette)
- [jQuery](https://jquery.com/)
- [U2F javascript library](https://demo.yubico.com/js/u2f-api.js)


Installation
------

```sh
composer require lookyman/u2f-nette
```

Register the extension in your `config.neon`:

```yml
extensions:
	u2f: lookyman\U2fNette\DI\U2fExtension
```


Usage
------

Example of implementation can be found in [lookyman/u2f-nette-example](https://github.com/lookyman/u2f-nette-example).
