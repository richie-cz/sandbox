<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 6. 1. 2016
 * Time: 17:47
 */

namespace App\AdminModule\Model;

use App\Model\BaseManager;

class UserManager extends BaseManager
{

    /**
     * Hlavni tabulka modelu a jeji klice
     */
    const
        TABLE_USER = 'admin_user',
        MAIN_TABLE = 'admin_user',
        COLUMN_ID = 'admin_user_id',
        COLUMN_NAME = 'user_name',
        COLUMN_FIRST_NAME = 'first_name',
        COLUMN_LAST_NAME = 'last_name',
        COLUMN_PASSWORD = 'pass',
        COLUMN_HIDDEN = 'hidden';

    /**
     * Spojovaci tabulky
     */
    const
        TABLE_USER_ROLE = 'admin_user_role';

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

    public function saveUser($userForm){
        $user = $userForm->getValues();
        //$user = $userForm;
        unset($user['password_repeat']);
        //$user['pass'] = Passwords::hash($user['pass']);
        unset($user['roles']);
        //unset($this->getStaticColumns(true)[self::COLUMN_HIDDEN]);
        //dump($this->getStaticColumns(true));exit;

        $key = array_search(self::COLUMN_HIDDEN, $this->getStaticColumns(true));
        unset($this->getStaticColumns(true)[$key]);
//$a = $this->getStaticColumns(true);
        if(isset($user[self::COLUMN_ID]) && $user[self::COLUMN_ID]){
            $this->database->table(self::TABLE_USER)
                ->where(self::COLUMN_ID, $user[self::COLUMN_ID])
                ->update(array_combine($this->getStaticColumns(true), (array)$user));
            return array('update',$user[self::COLUMN_ID]);
        }
        # Vlozeni noveho uzivatele
        else {
            return array('insert',$this->database->table(self::TABLE_USER)->insert($user)[self::COLUMN_ID]);
        }
    }

    public function saveUserRoles($user, $userId){

        $insert = array();

        foreach($user['roles'] as $role)
            $insert[] = array('admin_user_id'=>$userId[1],'admin_role_id'=>$role);


        if($userId[0] == 'update')
            $this->database->table(self::TABLE_USER_ROLE)->where(array('admin_user_id'=>$userId[1]))
                ->delete();

        $this->database->table(self::TABLE_USER_ROLE)->insert($insert);
    }

    public function saveResourcesRoles($resource){

        dump($resource);
        exit;
    }
}