<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 10. 6. 2016
 * Time: 17:16
 */

namespace App\Components;

use Arachne\Security\Authorization\Permission;
use App\Model\RoleManager;
use App\Model\ResourceManager;
use App\Model\PrivilegeManager;
use App\Model\AclManager;

use Nette\Object;

class Authorizator extends Object
{



    protected $roleManager;

    protected $resourceManager;

    protected $privilegeManager;

    protected $aclManager;




    public function __construct(PrivilegeManager $privilegeManager, RoleManager $roleManager, ResourceManager $resourceManager, AclManager $aclManager)
    {
        //parent::__construct();
        $this->roleManager = $roleManager;
        $this->resourceManager = $resourceManager;
        $this->aclManager = $aclManager;
        $this->privilegeManager = $privilegeManager;
    }

    /**
     * @return \Nette\Security\IAuthorizator
     */
    public function create()
    {


        #$identity = $this->userStorage->getIdentity();

        //dump($identity);exit;

        $permission = new Permission();
        dump($permission->identity());exit;

        #if(!$identity)
        #    return $permission;

        # ROLES
        $arrayRolesId = array();
        foreach($this->roleManager->getRolesByUser() as $role) {
            $arrayRolesId[] = $role[RoleManager::COLUMN_ID];
            $permission->addRole($role[RoleManager::COLUMN_TITLE]);
        }
        //dump($arrayRolesId); exit;

        # RESOURCES
        $namespace = 'Admin:';
        $arrayResourcesId = array();
        $resources = $this->resourceManager->getResources();

        foreach($resources as $resource) {
            //dump($resource);
            $arrayResourcesId[] = $resource[ResourceManager::COLUMN_ID];
            $permission->addResource($resource[ResourceManager::MODULE].':'.ucfirst($resource[ResourceManager::COLUMN_TITLE]));


        }
        //exit;

        # ACL
        $roleResourcePrivileges = $this->aclManager->getAcl1($arrayRolesId, $arrayResourcesId);
        //dump($roleResourcePrivileges);exit;
        foreach ($roleResourcePrivileges as $acl) {
            //dump($acl);
            $permission->allow($acl['role'], $resource[ResourceManager::MODULE].':'.ucfirst($acl['resource']), $acl['privilege']);
        }
        //exit;

        //$permission->allow('admin', Permission::ALL, Permission::ALL);

        return $permission;


    }


}