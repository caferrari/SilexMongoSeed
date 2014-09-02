<?php

namespace Site\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

use Application\Document\User;
use Common\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @param \Silex\ControllerCollection $controllers
     * @return \Silex\ControllerCollection
     */
    protected function mount(ControllerCollection $controllers)
    {
        $this->app->get('/', [$this, 'indexAction']);
        $this->app->match('/register', [$this, 'registerAction']);
        $this->app->match('/login', [$this, 'loginAction']);
        $this->app->get('/logout', [$this, 'logoutAction']);
        return $controllers;
    }

    public function indexAction(Application $app, Request $request)
    {
        return $this->render('index/index.twig');
    }

    public function logoutAction(Application $app)
    {
        $app['session']->remove('user');
        return $app->redirect('/');
    }

    public function loginAction(Application $app, Request $request)
    {
        $form = $this->getForm('login');
        if ($form->hasPostData($request)) {
            if ($data = $form->getData()) {
                $repo = $this->getRepository('user');
                if ($user = $repo->authenticate($data)) {
                    $app['session']->set('user', $user);
                    $this->setFlashMessage('success', 'Welcome back!');

                    // para onde vamos depois de logar?
                    $goto = $app['session']->get('redirectTo');
                    return $app->redirect($goto ?: '/');
                }
                $app['session']->remove('user');
                $this->setFlashMessage('danger', 'Invalid credentials');
            }
        }

        return $this->render('account/login.twig', [
            'form' => $form->createView()
        ]);
    }

    public function registerAction(Application $app, Request $request)
    {
        $form = $this->getForm('register', new User);

        if ($form->hasPostData($request)) {
            if ($data = $form->getData()) {
                $repo = $this->getRepository('user');
                $repo->insert($data);

                $this->setFlashMessage('success', 'Welcome to our system');

                $goto = $app['session']->get('redirectTo');
                return $app->redirect($goto ?: '/');
            }
            $this->setFlashMessage('danger', 'Registration error');
        }

        return $this->render('account/register.twig', [
            'form' => $form->createView()
        ]);
    }
}
