<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 28. 9. 2015
 * Time: 13:28
 */

namespace App\FrontModule\Presenters;

use App\FrontModule\Forms\UserForms;
use Nette\Utils\ArrayHash;
use App\Model\ResourceManager;
use App\Model\AclManager;
use App\Model\RoleManager;
use App\Model\PrivilegeManager;

class LoginPresenter extends FrontBasePresenter
{
    public $frontAuthenticator;

    public $userFormsFactory;

    public $resourceManager;

    public $aclManager;

    public $roleManager;

    public $privilegeManager;

    /** @var array Společné instrukce pro přihlašovací a registrační formuláře. */
    public $instructions;

    public function __construct(UserForms $userForms, ResourceManager $resourceManager, AclManager $aclManager, RoleManager $roleManager, PrivilegeManager $privilegeManager)
    {
        parent::__construct($resourceManager, $aclManager, $roleManager, $privilegeManager);
        $this->userFormsFactory = $userForms;
    }

    /** Volá se před každou akcí presenteru a inicializuje společné proměnné. */
    public function startup()
    {
        parent::startup();
        $this->instructions = array(
            'message' => null,
            'redirection' => ':Front:Homepage:'
        );
    }

    public function renderDefault()
    {

    }

    protected function createComponentLoginForm()
    {
        $this->instructions['message'] = 'Byl jste úspěšně přihlášen.';
        $this->instructions['msg_type'] = self::MSG_SUCCESS;
        return $this->userFormsFactory->createLoginForm(ArrayHash::from($this->instructions), null);
    }

}