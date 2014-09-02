<?php

namespace Common;

use Psr\Log\InvalidArgumentException;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;

/**
 * Class AbstractController
 * @package Common
 */
abstract class AbstractController implements ControllerProviderInterface
{

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @param Application $app
     * @return mixed
     */
    public final function connect(Application $app)
    {
        $this->dm = $app['dm'];
        $this->app = $app;
        $controllers = $app['controllers_factory'];
        return $this->mount($controllers);
    }

    /**
     * @param \Silex\ControllerCollection $controllers
     * @return \Silex\ControllerCollection
     */
    protected abstract function mount(ControllerCollection $controllers);


    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDm()
    {
        return $this->dm;
    }

    /**
     * @param string $document
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     * @throws \InvalidArgumentException
     */
    protected function getRepository($document = null)
    {
        if (!$document) {
            $fqcn = str_replace('\\Controller\\', '\\Document\\', get_class($this));
        } else {
            $fqcn = '\\Application\\Document\\' . ucfirst($document);
        }

        if (!class_exists($fqcn)) {
            throw new \InvalidArgumentException(sprintf('Repository does not exists: %s', $fqcn));
        }

        return $this->getDm()->getRepository($fqcn);
    }

    /**
     * @param $view
     * @param array $data
     * @return mixed
     */
    protected function render($view, $data = [])
    {
        return $this->app['twig']->render($view, $data);
    }

    /**
     * @param $class
     * @param null $entity
     * @return Common\AbstractForm
     */
    public function getForm($class, $entity = null)
    {

        if (strstr($class, '\\')) {
            $fqcn = $class;
        } else {
            $root = explode('\\', get_called_class())[0];
            $fqcn = $root . '\\Form\\' . ucfirst($class);
        }

        if (!class_exists($fqcn)) {
            throw new InvalidArgumentException(sprintf('Invalid form %s', $fqcn));
        }

        return new $fqcn($this->app, $entity);
    }

    /**
     * @param $type
     * @param $message
     */
    protected function setFlashMessage($type, $message)
    {
        $this->app['session']->getFlashBag()->add($type, $message);
    }

}