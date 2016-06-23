<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 20. 1. 2016
 * Time: 17:51
 */

namespace App\Model;

class ResourceManager extends BaseManager
{
    const
        TABLE_RESOURCE = 'resource',
        COLUMN_ID = 'resource_id',
        COLUMN_TITLE = 'title',
        COLUMN_UCTITLE = 'title1',
        COLUMN_NAME = 'name',
        COLUMN_TRANSLATE = 'translate',
        MODULE = 'module',
        COLUMN_HIDDEN = 'hidden';

    /**
     * Spojovaci tabulky
     */
    const TABLE_ROLE_RESOURCE_PRIVILEGE = 'admin_role_resource_privilege';

    public function getStaticColumns($asArray = true){
        $staticColumns = [
            self::COLUMN_ID,
            self::COLUMN_TITLE,
            self::COLUMN_NAME,
            self::COLUMN_TRANSLATE,
            self::MODULE,
            self::COLUMN_HIDDEN
        ];
        return $asArray ? $staticColumns : join(', ', $staticColumns);
    }

    /**
     * Predani tabulek pro filtr do BaseManageru
     */
    protected function setTables(){
        $this->mainTable = self::TABLE_RESOURCE;
        $this->middleTables = array(self::TABLE_ROLE_RESOURCE_PRIVILEGE);
        $this->ultimateTables = array();
    }

    public function getResources($filter = array()){
        $resource = $this->database->table(self::TABLE_RESOURCE)
            ->select(self::TABLE_RESOURCE.'.* ,CONCAT(UCASE(MID('.ResourceManager::COLUMN_TITLE.',1,1)),MID('.ResourceManager::COLUMN_TITLE.',2)) AS title1');
      
        $resource->order(self::COLUMN_ID . ' DESC');

        return $resource;
    }

    public function getResource($resourceId){
        return $this->database->table(self::TABLE_RESOURCE)
            ->select(self::TABLE_RESOURCE.'.* ,CONCAT(UCASE(MID('.ResourceManager::COLUMN_TITLE.',1,1)),MID('.ResourceManager::COLUMN_TITLE.',2)) AS title1')
            ->where(array(self::COLUMN_ID => $resourceId))
            ->fetch();
    }

    public function getResourceByTitle($presenter){

        return $this->database->table(self::TABLE_RESOURCE)
            ->where(self::COLUMN_TITLE, $presenter)
            ->fetch();
    }

    public function getResourceByTranslate($presenter){

        return $this->database->table(self::TABLE_RESOURCE)
            ->where(self::COLUMN_TRANSLATE, $presenter)
            ->fetch();
    }

    public function getResourcesByRole($roleId){
        return $this->database->query('
            SELECT *
            FROM '.self::TABLE_RESOURCE.'
            WHERE '.RoleManager::COLUMN_ID.' = ?
            ', $roleId)->fetchPairs(AclManager::COLUMN_ID, self::COLUMN_ID);
    }
    
    public function getResources1($roles){
        return $this->database->table(self::TABLE_RESOURCE)
            ->group('resource_id')
            ->where(':role_resource_privilege.role_id IN (?)', $roles);
    }
}