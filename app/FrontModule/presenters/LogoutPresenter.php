<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 28. 9. 2015
 * Time: 13:29
 */

namespace App\FrontModule\Presenters;

class LogoutPresenter extends FrontBasePresenter
{
    public function renderDefault()
    {
        $this->getUser()->logout();
        $this->flashMessage('Byl jste uspesne odhlasen');
        $this->redirect(':Front:Homepage:');
    }
}