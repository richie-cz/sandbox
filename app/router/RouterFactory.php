<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{


	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{

		$router = new RouteList();
		/*--------------------------------------------------------------------------------------------------------------
			ADMIN ROUTER
		--------------------------------------------------------------------------------------------------------------*/
		$adminRouter = new RouteList('Admin');

		$adminRouter[] = new Route('admin/<presenter>/[<action>][/<id>]', array(

            'presenter' => array(
                Route::VALUE => 'Homepage',
                Route::FILTER_TABLE => array(
					'prihlaseni' => 'Login',
					'odhlaseni' => 'Logout',
				),
                Route::FILTER_STRICT => true,
            ),

			'action' => array(
				Route::VALUE => 'default',
				Route::FILTER_TABLE => array(
					'zobrazit' => 'show',
					'upravit' => 'edit',
					'smazat' => 'delete'
				)
			),
            'id' => NULL,
            ));
		$router[] = $adminRouter;

    /*--------------------------------------------------------------------------------------------------------------
      FRONT ROUTER
    --------------------------------------------------------------------------------------------------------------*/
		$frontRouter = new RouteList('Front');

		$frontRouter[] = new Route('<presenter>[/<action>]', array(
			'presenter' => array(
				Route::VALUE => 'Homepage',
				Route::FILTER_TABLE => array(
					// řetězec v URL => akce presenteru
					'prihlaseni' => 'Login',
					'odhlaseni' => 'Logout',
					'uzivatel' => 'User',
				),

				Route::FILTER_STRICT => true
			),
			'action' => 'default',
		));


		$router[] = $frontRouter;

		return $router;
	}

}
