<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 17. 1. 2016
 * Time: 23:18
 */

namespace App\Model;

class RoleManager extends BaseManager
{
    /**
     * Hlavni tabulka modelu a jeji klice
     */
    const
        TABLE_ROLE = 'role',
        COLUMN_NAME = 'role_name',
        COLUMN_ID = 'role_id',
        COLUMN_TITLE = 'role_title',
        COLUMN_PARRENT = 'role_parrent_id',
        COLUMN_HIDDEN = 'hidden';

    /**
     * Spojovaci tabulky
     */
    const
        TABLE_ADMIN_USER_ROLE = 'admin_user_role',
        TABLE_USER_ROLE = 'front_user_role',
        TABLE_ROLE_RESOURCE_PRIVILEGE = 'role_resource_privilege';

    /**
     * Staticke klice hlavni tabulky
     * @param bool $asArray
     * @return array|string
     */
    protected static function getStaticColumns($asArray = true){
        $staticColumns = [
            self::COLUMN_ID,
            self::COLUMN_TITLE,
            self::COLUMN_NAME,
            self::COLUMN_HIDDEN
        ];
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    /**
     * Predani tabulek pro filtr do BaseManageru
     */
    protected function setTables(){
        $this->mainTable = self::TABLE_ROLE;
        $this->middleTables = array(self::TABLE_USER_ROLE, self::TABLE_ROLE_RESOURCE_PRIVILEGE);
        $this->ultimateTables = array(ResourceManager::TABLE_RESOURCE);
    }

    protected function getRelations($asArray = true)
    {
        $staticColumns = array();
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    /**
     * @param $roleId
     * @return bool|mixed|\Nette\Database\Table\IRow
     */
    public function getRole($roleId){
        return $this->database->table(self::TABLE_ROLE)
            ->where(self::COLUMN_ID, $roleId)
            ->fetch();
    }

    /**
     * Metoda slouzi pro prirazeni roli prihlasovanemu uzivateli
     * Vyuziva se pro ACL
     * @param $userId
     * @return \Nette\Database\Table\Selection
     */
    public function getRolesByUser($userId = null, $module = null){

        $role = $this->database->table(self::TABLE_ROLE)->group(self::COLUMN_ID);
        if($module == 'Admin' && $userId) {
            //dump($userId);exit;
            $role->where(':' . self::TABLE_ADMIN_USER_ROLE . '.' . \App\AdminModule\Model\UserManager::COLUMN_ID, $userId);
        }
        if($module == 'Front' && $userId)
           $role->where(':front_user_role.user_id', $userId);

        $role->order(self::COLUMN_PARRENT.' IS NULL DESC, '.self::COLUMN_PARRENT.' DESC');

        //exit;
        return $role;
    }

    public function getRolesByResource($resourceId){
        return $this->database->table(self::TABLE_ROLE)
            ->group(self::COLUMN_ID)
            ->where(':'.self::TABLE_ROLE_RESOURCE_PRIVILEGE.'.'.ResourceManager::COLUMN_ID, $resourceId);
    }

    /**
     * Vypis roli
     * @return \Nette\Database\Table\Selection
     */
    public function getRoles($filter = array()){
        $roles = $this->database->table(self::TABLE_ROLE);
        return $roles;
    }
    
}