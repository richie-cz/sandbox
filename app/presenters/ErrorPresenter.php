<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\BadRequestException;
use Tracy\ILogger;


class ErrorPresenter extends BasePresenter
{
	/** @var ILogger */
	private $logger;


	public function __construct(ILogger $logger)
	{
		parent::__construct();
		$this->logger = $logger;
	}


	/**
	 * @param  Exception
	 * @return void
	 */
	public function renderDefault($exception)
	{
		$serverError = false;
		// Pokud jde o chybu v dotazu.
		if ($exception instanceof BadRequestException) {
			// Zapisuje zprávu do access.log.
			$this->logger->log("HTTP code {$exception->getCode()}: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');
		} else { // Jinak je to chyba serveru.
			$this->setView('500'); // Načítá template 500.latte.
			$this->logger->log($exception, ILogger::EXCEPTION); // Loguje výjimku.
			$serverError = true;
		}

		// Pokud jde o AJAXový dotaz, pošle chybu v payloadu, což je asynchroní odpoveď na AJAX dotaz.
		if ($this->isAjax()) {
			$this->payload->error = true;
			$this->terminate();
		} elseif (!$serverError) { // Jinak pokud to není chyba serveru.
			$this->redirect(':Front:Homepage:', 'chyba'); // Přesměruj na vlastní chybovou stránku.
		}
		// Jinak se vykreslí defaultní chyba serveru (500).
	}

}
