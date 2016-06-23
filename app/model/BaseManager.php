<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 20. 9. 2015
 * Time: 18:51
 */

namespace App\Model;

use Nette\Database\Context;
use Nette\Object;

/**
 * Základní třída modelu pro všechny modely aplikace.
 * Předává přístup k práci s databází.
 * @package App\Model
 */
abstract class BaseManager extends Object
{
    /** @var Context Instance třídy pro práci s databází. */
    protected $database;
    
    /**
     * Konstruktor s injektovanou třídou pro práci s databází.
     * @param Context $database automaticky injektovaná třída pro práci s databází
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }
    

}