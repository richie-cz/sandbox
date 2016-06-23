<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 23. 9. 2015
 * Time: 18:45
 */

namespace App\FrontModule\Forms;

use App\Components\CredentialsAuthenticator;
use App\FrontModule\Components\FrontAuthenticator;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\Utils\ArrayHash;
use App\FrontModule\Model\UserManager;
use App\Forms\BaseFormFactory;

/**
 * Class UserFormsFactory
 * @package App\Forms
 */
class UserForms extends Object
{
    /** @var User Uživatel. */
    private $user;

    /** @var BaseFormFactory Továrnička na formuláře. */
    private $formFactory;

    public $authenticator;

    protected $userManager;


    /**
     * Konstruktor s injektovanou třidou uživatele.
     * @param User $user automaticky injektovaná třída uživatele
     */
    public function __construct(User $user, BaseFormFactory $baseFormFactory, UserManager $userManager, FrontAuthenticator $authenticator)
    {
        $this->user = $user;
        $this->formFactory = $baseFormFactory;
        $this->authenticator = $authenticator;
        $this->userManager = $userManager;
    }

    /**
     * Vrací formulář se společným základem.
     * @param null|Form $form formulář, který se má rozšířit o společné prky, nebo null, pokud se má vytvořit nový formulář
     * @return Form formulář se společným základem
     */
    private function createBasicForm(Form $form = null)
    {
        $form = $form ? $form : $this->formFactory->create();
        $form->addText('user_name', '')
            ->setAttribute('class', 'form-control')
            ->setAttribute('placeholder',"Uživatelské jméno")
            ->setRequired();
        return $form;
    }

    /**
     * @param null $instructions
     * @param Form|null $form
     * @param string $module
     * @return Form
     */
    public function createLoginForm($instructions = null, Form $form = null)
    {
        #echo $module;
        #exit;
        $form = $this->createBasicForm($form);
        $form->addPassword('pass', '')
            ->setAttribute('class', 'form-control')
            ->setAttribute('placeholder',"Heslo")
            ->setRequired();

        $form->addSubmit('submit', 'Přihlásit')
            ->setAttribute('class', 'btn btn-default');

        $form->onSuccess[] = function (Form $form) use ($instructions) {
            $this->authenticator->login($form, $instructions);
            #$this->authenticator->adminLogin($form, $instructions);


        };
        return $form;
    }


    /**
     * @param null $instructions
     * @param Form|null $form
     * @param string $module
     * @return Form
     */
    public function createAdminRegisterForm($instructions = null, Form $form = null, $module = 'Admin')
    {
        $form = $this->createBasicForm($form);
        $form->addText('first_name', 'Jméno')->setRequired();
        $form->addText('last_name', 'Prijmeni')->setRequired();
        $form->addPassword('pass', 'Heslo');
        $form->addPassword('password_repeat', 'Heslo znovu')
            ->addRule(Form::EQUAL, 'Hesla nesouhlasí.', $form['pass']);
        $form->addSubmit('register', 'Registrovat');
        $form->onSuccess[] = function (Form $form) use ($instructions) {
            //$this->adminAuthenticator->login($form, $instructions, true);
            $this->userManager->saveUser($form);
        };
        return $form;
    }
}