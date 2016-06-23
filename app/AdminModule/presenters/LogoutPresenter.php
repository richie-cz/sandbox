<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 9. 1. 2016
 * Time: 10:30
 */

namespace App\AdminModule\Presenters;


class LogoutPresenter extends AdminBaseSecurePresenter
{
    public function renderDefault()
    {
        $this->getUser()->logout();
        $this->flashMessage('Byl jste uspesne odhlasen');
        $this->redirect('Login:');
    }
}