<?php

namespace Application;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractForm
 * @package Application
 */
abstract class AbstractForm
{

    protected $app;
    protected $form;
    protected $entity;
    protected $errors = [];

    public function __construct(Application $app, $entity = null)
    {
        $this->entity = $entity;
        $this->app = $app;
        $this->form = $this->buildForm($this->app['form.factory'], $entity);
    }

    public abstract function buildForm($factory);

    public function hasPostData(Request $request)
    {

        if ('POST' == $request->getMethod()) {
            $this->form->handleRequest($request);
            return true;
        }

        return false;

    }

    public function getData()
    {
        $this->form->isValid();

        $data = $this->form->getData();
        $errors = $this->app['validator']->validate($data);

        $this->errors = $errors;

        if (count($this->errors) || !$this->form->isValid()) {
            return false;
        }

        return $data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function createView()
    {
        return $this->form->createView();
    }

}