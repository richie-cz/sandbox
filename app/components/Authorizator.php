<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 10. 6. 2016
 * Time: 17:16
 */

namespace App\Components;

use App\Model\RoleManager;
use App\Model\ResourceManager;
use App\Model\AclManager;
use Nette\Security\IAuthorizator;
use Nette\Security\Permission;
use Nette\Object;
use Nette\Security\IUserStorage;


class Authorizator extends Object
{
    public $permission;

    protected $roleManager;

    protected $resourceManager;

    protected $aclManager;

    protected $userStorage;

    public $identity;

    /**
     * Konstruktor s injektovanou třídou pro práci s databází.
     * @param Context $database automaticky injektovaná třída pro práci s databází
     */
    public function __construct(RoleManager $roleManager, ResourceManager $resourceManager, AclManager $aclManager, IUserStorage $userStorage)
    {
        $this->roleManager = $roleManager;
        $this->resourceManager = $resourceManager;
        $this->aclManager = $aclManager;
        $this->userStorage = $userStorage;
    }



    /**
     * @return \Nette\Security\IAuthorizator
     */
    public function create()
    {
        $identity = $this->userStorage->getIdentity();
        //dump($identity);exit;

        $permission = new Permission();

        $namespace = 'Admin';

        if(!$identity)
            return $permission;

        # ROLES
        $arrayRolesId = array();
        foreach($this->roleManager->getRolesByUser($identity->id) as $role) {
            $arrayRolesId[] = $role[RoleManager::COLUMN_ID];
            $permission->addRole($role[RoleManager::COLUMN_TITLE]);
        }

        # RESOURCES
        $arrayResourcesId = array();
        $resources = $this->resourceManager->getResources();

        foreach($resources as $resource) {
            $arrayResourcesId[] = $resource[ResourceManager::COLUMN_ID];
            $permission->addResource($resource[ResourceManager::MODULE].':'.ucfirst($resource[ResourceManager::COLUMN_TITLE]));
        }

        # ACL
        $roleResourcePrivileges = $this->aclManager->getAcl1($arrayRolesId, $arrayResourcesId);
        foreach ($roleResourcePrivileges as $acl) {
            $permission->allow($acl['role'], $resource[ResourceManager::MODULE].':'.ucfirst($acl['resource']), $acl['privilege']);
        }

        return $permission;

    }


}