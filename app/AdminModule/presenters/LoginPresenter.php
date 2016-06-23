<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 9. 1. 2016
 * Time: 10:18
 */

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms\UserForms;
use Nette\Utils\ArrayHash;

class LoginPresenter extends AdminBasePresenter
{
    /** @var UserForms Továrnička na uživatelské formuláře. */
    private $userFormsFactory;

    /** @var array Společné instrukce pro přihlašovací a registrační formuláře. */
    private $instructions;

    /**
     * Konstruktor s injektovanou továrničkou na uživatelské formuláře.
     * @param UserForms $userForms automaticky injektovaná třída továrničky na uživatelské formuláře
     */
    public function __construct(UserForms $userForms)
    {
        parent::__construct();
        $this->userFormsFactory = $userForms;
    }

    /** Volá se před každou akcí presenteru a inicializuje společné proměnné. */
    public function startup()
    {
        parent::startup();
        $this->instructions = array(
            'message' => null,
            'redirection' => 'Homepage:'
        );
    }

    /**
     * Vrací komponentu registračního formuláře z továrničky.
     * @return Form registrační formulář
     */
    protected function createComponentLoginForm()
    {
        $this->instructions['message'] = 'Byl jste úspěšně přihlášen.';
        $this->instructions['msg_type'] = self::MSG_SUCCESS;
        return $this->userFormsFactory->createLoginForm(ArrayHash::from($this->instructions), null);
    }

    public function renderDefault(){
        if ($this->getUser()->isLoggedIn()) {
            $this->flashMessage('Byl jste úspěšně přihlášen.');
            $this->redirect($this->instructions['redirection']);
        }
    }

}