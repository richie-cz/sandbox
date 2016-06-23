<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 20. 1. 2016
 * Time: 17:50
 */

namespace App\Model;

class PrivilegeManager extends BaseManager
{
    const
        TABLE_PRIVILEGE = 'privilege',
        COLUMN_ID = 'privilege_id',
        COLUMN_TITLE = 'title',
        COLUMN_NAME = 'name',
        COLUMN_HIDDEN = 'hidden';

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
        $this->mainTable = self::TABLE_PRIVILEGE;
    }

    protected function getRelations($asArray = true)
    {
        $staticColumns = array();
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    public function getPrivileges($filter = array()){
        $privileges = $this->database->table(self::TABLE_PRIVILEGE);
        return $privileges;
    }

    public function getPrivilege($privilegeId){
        return $this->database->table(self::TABLE_PRIVILEGE)
            ->where(self::COLUMN_ID, $privilegeId)
            ->fetch();
    }
    
}