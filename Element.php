<?php

namespace attitude\Elements;

use \attitude\Elements\DependencyContainer;

use \attitude\Elements\Service_Interface;
use \attitude\Elements\Singleton_Prototype;
use \attitude\Elements\Request_Element;

abstract class Handler_Element extends Singleton_Prototype implements Handler_Interface
{
    private $request = null;
    private $service = null;

    protected function __construct()
    {
        return $this->setService(DependencyContainer::get(get_called_class().'.service'));
    }

    private function setService(Service_Interface $dependency)
    {
        $this->service = $dependency;

        return $this;
    }

    public function setRequest(Request_Element $dependency)
    {
        $this->request = $dependency;

        return $this;
    }

    private function isAuthorized()
    {
        try
        {
            $auth = DependencyContainer::get('Auth');
        } catch(HTTPException $e) {
            return false;
        }

        if (!is_object($auth) || !method_exists($auth, 'isAuthorized')) {
            return false;
        }

        return $auth->isAuthorized();
    }

    public function handle()
    {
        $methods_to_try = array();

        $request = $this->request->getRequestURIArray();

        if (!empty($request) && isset($request[1])) {
            if ($this->isAuthorized()) {
                $methods_to_try[] = 'Auth'.$this->request->getRequestMethod().ucfirst($request[1]);
            }

            $methods_to_try[] = $this->request->getRequestMethod().ucfirst($request[1]);
        }

        if ($this->isAuthorized()) {
            $methods_to_try[] = 'Auth'.$this->request->getRequestMethod().'Index';
        }

        $methods_to_try[] = $this->request->getRequestMethod().'Index';

        foreach ($methods_to_try as &$method) {
            if (is_callable(array($this->service, $method))) {
                return $this->service->$method($this->request);
            }
        }

        throw new HTTPException(405);
    }
}
