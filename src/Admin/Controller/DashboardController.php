<?php

namespace Admin\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

use Application\Document\User;

use Application\AbstractController;

class DashboardController extends AbstractController
{

    /**
     * @param \Silex\ControllerCollection $controllers
     * @return \Silex\ControllerCollection
     */
    protected function mount(ControllerCollection $controllers)
    {
        $controllers->get('/', [$this, 'indexAction']);
        return $controllers;
    }

    public function indexAction(Application $app, Request $request)
    {
        return $this->render('dashboard/index.twig');
    }
}