<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 24. 1. 2016
 * Time: 18:23
 */

namespace App\Model;

class AclManager extends BaseManager
{
    const
        TABLE_ACL = 'role_resource_privilege',
        COLUMN_ID = 'role_resource_privilege_id',
        COLUMN_ROLE_ID = RoleManager::COLUMN_ID,
        COLUMN_RESOURCE_ID = ResourceManager::COLUMN_ID,
        COLUMN_PRIVILEGE_ID = PrivilegeManager::COLUMN_ID,
        COLUMN_HIDDEN = 'hidden';

    protected function getStaticColumns($asArray = true){
        $staticColumns = [
            self::COLUMN_ID,
            self::COLUMN_ROLE_ID,
            self::COLUMN_PRIVILEGE_ID,
            self::COLUMN_RESOURCE_ID
        ];
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }
    
    public function getAcl1($rolesId, $resourcesId){
        return $this->database->table(self::TABLE_ACL)
            ->select(RoleManager::TABLE_ROLE.'.'.RoleManager::COLUMN_TITLE.' role ,'.ResourceManager::TABLE_RESOURCE.'.'.ResourceManager::COLUMN_TITLE.' resource ,'.PrivilegeManager::TABLE_PRIVILEGE.'.'.PrivilegeManager::COLUMN_TITLE.' privilege, '.ResourceManager::MODULE.' module')
            ->where(RoleManager::TABLE_ROLE.'.'.RoleManager::COLUMN_ID.' IN (?)', $rolesId)
            ->where(PrivilegeManager::TABLE_PRIVILEGE.'.'.PrivilegeManager::COLUMN_ID)
            ->where(ResourceManager::TABLE_RESOURCE.'.'.ResourceManager::COLUMN_ID.' IN (?)', $resourcesId);
    }

    public function getAllAcl(){
        return $this->database->table(self::TABLE_ACL);
    }

    public function getAclByResource($resourceId)
    {
        return $this->database->table(self::TABLE_ACL)
            ->where(ResourceManager::COLUMN_ID, $resourceId);
    }
}
