<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 24. 9. 2015
 * Time: 21:11
 */

namespace App\AdminModule\Presenters;

use Nette\Application\BadRequestException;


class AdminBaseSecurePresenter extends AdminBasePresenter
{
    /** @var null|string Adresa presenteru pro logování uživatele. */
    protected $loginPresenter = 'Login:';

    /**
     * Volá se na začátku každé akce a kontroluje uživatelská oprávnění k této akci.
     * @throws BadRequestException Jestliže je uživatel přihlášen, ale nemá oprávnění k této akci.
     */
    protected function startup()
    {
        parent::startup();
        
        $this->httpRequest = $this->context->getByType('Nette\Http\Request');

        if(!$this->user->isLoggedIn()) {
            $this->flashMessage('Nejste prihlasen', self::MSG_ERROR);
            $this->redirect($this->loginPresenter);
        }
        
        /*
           if (!$this->user->isAllowed($this->getName(), $this->getAction())) {
                $this->flashMessage('Nemáš dostatečná oprávnění.', self::MSG_ERROR);
                if (!$this->user->isAllowed($this->getName(), 'default'))
                    $this->redirect('Homepage:');
                else
                    $this->redirect(str_replace('Admin:', '', $this->getName()) . ':');
            }
        */


    }

}
