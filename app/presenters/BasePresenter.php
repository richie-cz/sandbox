<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use App\Forms\BaseFormFactory;
use Nette\Http\Request;
use Instante;

/**
 * Základní presenter pro všechny ostatní presentery aplikace.
 * @package App\Presenters
 */
abstract class BasePresenter extends Presenter
{
    /** Zpráva typu informace. */
    const MSG_INFO = 'info';
    /** Zpráva typu úspěch. */
    const MSG_SUCCESS = 'success';
    /** Zpráva typy chyba. */
    const MSG_ERROR = 'danger';

    /** @var null|string Adresa presenteru pro logování uživatele. */
    protected $loginPresenter = null;

    /** @var Request HTTP request na stránku. */
    protected $httpRequest;

    /** @var BaseFormFactory Továrnička na formuláře. */
    protected $formFactory;

    /** Volá se před vykreslením každého presenteru a předává společné proměné do celkového layoutu webu. */
    protected function beforeRender()
    {
        parent::beforeRender();
        
        $this->template->formPath = __DIR__ . '/../templates/components/form.latte'; // Předá cestu ke globální šabloně formulářů do šablony.
        $this->template->formPathDefault = __DIR__ . '/../templates/components/form-default.latte'; // Předá cestu ke globální šabloně formulářů do šablony.
        $this->template->formPathInline = __DIR__ . '/../templates/components/form-inline.latte'; // Předá cestu ke globální šabloně formulářů do šablony.
        $this->template->formPathHorizontal = __DIR__ . '/../templates/components/form-horizontal.latte'; // Předá cestu ke globální šabloně formulářů do šablony.

        if ($this->hasFlashSession())
            $this->redrawControl('flashMessages');
    }

    /**
     * Speciální setter pro injectování továrničky na formuláře se společným nastavením.
     * @param BaseFormFactory $baseFormFactory automaticky injektovaná továrnička na formuláře se společným nastavením
     */
    public function injectFormFactory(BaseFormFactory $baseFormFactory)
    {
        $this->formFactory = $baseFormFactory;
    }

}
