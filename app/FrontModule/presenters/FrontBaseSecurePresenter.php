<?php
namespace App\FrontModule\Presenters;

abstract class FrontBaseSecurePresenter extends FrontBasePresenter
{
    /** @var null|string Adresa presenteru pro logování uživatele. */
    protected $loginPresenter = ':Front:Login:';

    /**
     * Volá se na začátku každé akce a kontroluje uživatelská oprávnění k této akci.
     * @throws BadRequestException Jestliže je uživatel přihlášen, ale nemá oprávnění k této akci.
     */
    protected function startup()
    {
        parent::startup();
        
        if (!$this->user->isAllowed($this->getName(), $this->getAction())) {
            $this->flashMessage('Nemáš dostatečná oprávnění.', self::MSG_ERROR);
            if (!$this->user->isAllowed($this->getName(), 'default'))
                $this->redirect('Homepage:');
            else
                $this->redirect(str_replace('Front:', '', $this->getName()) . ':');
        }

    }
}