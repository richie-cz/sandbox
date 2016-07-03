<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 24. 9. 2015
 * Time: 21:11
 */

namespace App\AdminModule\Presenters;



use Arachne\Security\Authorization\Permission;

class AdminBaseSecurePresenter extends AdminBasePresenter

{
    /** @var null|string Adresa presenteru pro logování uživatele. */
    protected $loginPresenter = 'Login:';


    /*
    public function __construct(PrivilegeManager $privilegeManager, RoleManager $roleManager, ResourceManager $resourceManager, AclManager $aclManager, Permission $permission)
    {
        parent::__construct();
        $this->roleManager = $roleManager;
        $this->resourceManager = $resourceManager;
        $this->aclManager = $aclManager;
        $this->privilegeManager = $privilegeManager;
        $this->permission = $permission;
    }*/


    protected function startup()
    {
        parent::startup();
        
        $this->httpRequest = $this->context->getByType('Nette\Http\Request');

        if(!$this->user->isLoggedIn()) {
            $this->flashMessage('Nejste prihlasen', self::MSG_ERROR);
            $this->redirect($this->loginPresenter);
        }

        #$namespace = $this->user->storage->namespace;

        #$permission = new Permission();


        //dump();exit;


        # ROLES
        /*
        $arrayRolesId = array();
        foreach($this->roleManager->getRolesByUser($this->user->getId()) as $role) {
            $arrayRolesId[] = $role[RoleManager::COLUMN_ID];
            $this->permission->addRole($role[RoleManager::COLUMN_TITLE]);
        }

        # RESOURCES
        $arrayResourcesId = array();
        $resources = $this->resourceManager->getResources();

        foreach($resources as $resource) {
            $arrayResourcesId[] = $resource[ResourceManager::COLUMN_ID];
            $this->permission->addResource($resource[ResourceManager::MODULE].':'.ucfirst($resource[ResourceManager::COLUMN_TITLE]));
        }

        # ACL
        $roleResourcePrivileges = $this->aclManager->getAcl1($arrayRolesId, $arrayResourcesId);
        foreach ($roleResourcePrivileges as $acl) {
            $this->permission->allow($acl['role'], $resource[ResourceManager::MODULE].':'.ucfirst($acl['resource']), $acl['privilege']);
        }


        */
        //dump($this->permission);exit;
        //$this->authorization->



        /**
         * Konstruktor s injektovanou třídou pro práci s databází.
         * @param Context $database automaticky injektovaná třída pro práci s databází
         */

        /*
        if (!$this->authorization->isAllowed($this->getName(), $this->getAction())){
            $this->flashMessage('Nemáš dostatečná oprávnění.', self::MSG_ERROR);
            if (!$this->user->isAllowed($this->getName(), 'default'))
                $this->redirect('Homepage:');
            else
                $this->redirect(str_replace('Admin:', '', $this->getName()) . ':');
        }
        */
        //$this->authorization->isA
        

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
