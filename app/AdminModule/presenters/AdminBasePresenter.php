<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 22. 9. 2015
 * Time: 19:03
 */

namespace App\AdminModule\Presenters;

use App\Presenters\BasePresenter;
use App\Forms\BaseFormFactory;

abstract class AdminBasePresenter extends BasePresenter
{
    protected $user;
    

    protected $presenterName;    

    /** @var BaseFormFactory */
    protected $formFactory;

    public function setBaseFormFactory(BaseFormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }


    protected function startup()
    {
        parent::startup();
        //dump($this->u);exit;
        $this->user = parent::getUser();
        $this->user->getStorage()->setNamespace('Admin');
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        
        $this->httpRequest = $this->context->getByType('Nette\Http\Request'); // Získáme aktuální HTTP request.
        $this->template->domain = $this->httpRequest->getUrl()->getHost(); // Předá jméno domény do šablony.
        $this->template->admin = $this->getUser()->isInRole('admin');
        $this->setLayout('adminLayout');
    }
    
    
}