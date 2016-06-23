<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 23. 1. 2016
 * Time: 17:15
 */

namespace App\FrontModule\Components;


use Nette\Database\Context;
use Nette\Object;
use Nette\Security;
use App\Model\RoleManager;

class FrontAuthenticator extends Object
{
    protected $database;

    private $user;

    const
        TABLE_NAME = 'user',
        COLUMN_ID = 'user_id',
        COLUMN_NAME = 'user_name',
        COLUMN_FIRST_NAME = 'first_name',
        COLUMN_LAST_NAME = 'last_name',
        COLUMN_PASSWORD_HASH = 'pass',
        COLUMN_ROLE = 'role';

    protected $roleManager;

    /**
     * @param Security\User $user
     */
    public function __construct(Security\User $user, Context $database, RoleManager $roleManager)
    {
        //parent::__construct();
        $this->user = $user;
        $this->database = $database;
        $this->roleManager = $roleManager;
    }


    /**
     * @param $email
     * @param $password
     * @throws Security\AuthenticationException
     */
    public function login($form, $instructions, $register = false)
    {
        $presenter = $form->getPresenter();

        $username = $form->getValues()->user_name;
        $password = $form->getValues()->pass;

        $row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();


        if (!$row) {
            throw new Security\AuthenticationException('Bad EMAIL', Security\IAuthenticator::IDENTITY_NOT_FOUND);
        } elseif (!Security\Passwords::verify($password, $row->pass)) {
            throw new Security\AuthenticationException('Bad PASSWORD', Security\IAuthenticator::INVALID_CREDENTIAL);
        } elseif (Security\Passwords::needsRehash($row->pass)) {
            $row->update(array(
                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
            ));
        } else {
            $this->user->getStorage()->setNamespace('Front');
            $this->user->setExpiration('30 minutes', TRUE);
            $arr = $row->toArray();
            unset($arr[self::COLUMN_PASSWORD_HASH]);
            $this->user->login(new Security\Identity($row[self::COLUMN_ID], $this->roleManager->getRolesByUser($row[self::COLUMN_ID], 'Front')->fetchPairs('role_id', 'role_title'), $arr));
            if (isset($instructions->message))
                $presenter->flashMessage(
                    $instructions->message,
                    // Pokud instrukce obsahují typ zprávy, tak ho použij, jinak použij výchozí informativní typ.
                    isset($instructions->msg_type) ? $instructions->msg_type : BasePresenter::MSG_INFO
                );
            // Pokud instrukce obsahují přesměrování, tak ho proveď na příslušném presenteru.
            if (isset($instructions->redirection))
                $presenter->redirect($instructions->redirection);
        }
    }

    public function register($form)
    {
        try {
            // Pokusí se vložit nového uživatele do databáze.
            $this->database->table(self::TABLE_NAME)->insert(array(
                self::COLUMN_NAME => $form->getValues()->user_name,
                self::COLUMN_FIRST_NAME => $form->getValues()->first_name,
                self::COLUMN_LAST_NAME => $form->getValues()->last_name,
                self::COLUMN_PASSWORD_HASH => Passwords::hash($form->getValues()->pass),
            ));
        } catch (UniqueConstraintViolationException $e) {
            // Vyhodí výjimku, pokud uživatel s daným jménem již existuje.
            throw new DuplicateNameException;
        }
    }

}