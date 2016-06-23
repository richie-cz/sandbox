<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 6. 1. 2016
 * Time: 17:47
 */

namespace App\FrontModule\Model;

use App\Model\BaseManager;

class UserManager extends BaseManager
{

    /**
     * Hlavni tabulka modelu a jeji klice
     */
    const
        TABLE_USER = 'user',
        COLUMN_ID = 'user_id',
        COLUMN_NAME = 'user_name',
        COLUMN_FIRST_NAME = 'first_name',
        COLUMN_LAST_NAME = 'last_name',
        COLUMN_PASSWORD = 'pass',
        COLUMN_HIDDEN = 'hidden';

    /**
     * Spojovaci tabulky
     */
    const
        TABLE_USER_ROLE = 'user_role';

    /**
     * Staticke klice hlavni tabulky
     * @param bool $asArray
     * @return array|string
     */
    protected function getStaticColumns($asArray = true)
    {
        $staticColumns = [
            self::COLUMN_ID,
            self::COLUMN_NAME,
            self::COLUMN_FIRST_NAME,
            self::COLUMN_LAST_NAME,
            self::COLUMN_HIDDEN
        ];
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    protected function setTables()
    {
        $this->mainTable = self::TABLE_USER;
        $this->middleTables = array(self::TABLE_USER_ROLE);
        $this->ultimateTables = array(RoleManager::TABLE_ROLE);
    }

    protected function getRelations($asArray = true)
    {
        $staticColumns[self::TABLE_USER_ROLE][RoleManager::TABLE_ROLE] = [
            RoleManager::COLUMN_ID,
            RoleManager::COLUMN_NAME,
        ];
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    /**
     * Vypis uzivatelu
     * @param array $filter
     * @return \Nette\Database\Table\Selection
     */
    public function getUsers($filter = array()){
        $users = $this->database->table(self::TABLE_USER);
        if(!empty($filter))
            $users = $this->filterTable($filter, $users, $this);

        return $users;
    }

    public function getUsersCount(){
        return $this->database->table(self::TABLE_USER)->count('admin_user_id');
    }

    public function deleteUsers($id = array()){
        $this->database->table(self::TABLE_USER)
            ->where(self::COLUMN_ID, $id)
            ->delete();
    }

    public function getUser($id){
        return $this->database->table(self::TABLE_USER)
            ->where(self::COLUMN_ID, $id)
            ->limit(1)
            ->fetch();
    }

    public function showUser($id){
        $value = $this->database->table(self::TABLE_USER)
            ->where(self::COLUMN_ID, $id)
            ->fetchPairs('admin_user_id', 'hidden');

        $this->database->query('UPDATE '.self::TABLE_USER.' SET ? WHERE '.self::COLUMN_ID.' = ?', array(self::COLUMN_HIDDEN => ($value[$id]) == 0 ? 1 : 0), $id);

        return ($value[$id] == 0 ? 1 : 0);
    }

    public function getUsersByRole($roleId){
        return $this->database->table(self::TABLE_USER_ROLE)
            ->where(self::TABLE_USER.'.role_id = ?', $roleId);
    }


}