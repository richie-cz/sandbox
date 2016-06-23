<?php

namespace App\FrontModule\Presenters;

use App\Presenters\BasePresenter;

class FrontBasePresenter extends BasePresenter
{
    
    /** @var BaseFormFactory */
    protected $formFactory;

    public function setBaseFormFactory(BaseFormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }


    protected function startup()
    {
        parent::startup();
        $user = parent::getUser();
        $user->getStorage()->setNamespace('Front');
    }

    protected function beforeRender()
    {
        parent::beforeRender();

        $this->httpRequest = $this->context->getByType('Nette\Http\Request'); // Získáme aktuální HTTP request.
        $this->template->domain = $this->httpRequest->getUrl()->getHost(); // Předá jméno domény do šablony.
        $this->template->member = $this->getUser()->isInRole('member');
    }
}