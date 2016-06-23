<?php

namespace App\Forms;

use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Object;
use Instante;
use Nette\Forms\Controls;

class BaseFormFactory extends Object
{
    /**
     * Vytváří a vrací formulář se společným nastavením.
     * @return Form formulář se společným nastavením
     */
    public function create()
    {
        $form = new Form();

        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;

        $renderer->wrappers['pair']['container'] = 'div class=form-group';

        $renderer->wrappers['pair']['.error'] = 'has-error';

        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';

        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

        $form->onError[] = [$this, 'formError'];
        return $form;
    }

    public function renderBootstrap($form){
        foreach ($form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
                $usedPrimary = TRUE;
            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
                $control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
            }
        }
    }

    /**
     * Převádí výpis chyb validace formuláře na zprávy na stránce.
     * @param Form $form formulář ze kterého chyby pochází
     */
    public function formError($form)
    {
        $errors = $form->getErrors();
        $presenter = $form->getPresenter();
        if ($presenter) foreach ($errors as $error) $presenter->flashMessage($error, BasePresenter::MSG_ERROR);
    }
}