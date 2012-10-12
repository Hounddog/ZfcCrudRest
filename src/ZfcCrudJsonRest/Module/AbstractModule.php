<?php

namespace ZfcCrudJsonRest\Module;

use ZfcBase\Module\AbstractModule as ZfcBaseAbstractModule;

use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;


abstract class AbstractModule extends ZfcBaseAbstractModule
 implements Feature\BootstrapListenerInterface

{
	public function onBootstrap(Event $e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager()->getSharedManager();
        $sm  = $app->getServiceManager();

        $em->attach($this->getNamespace(), MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {
            $strategy = $sm->get('ViewJsonStrategy');
            $view     = $sm->get('ViewManager')->getView();
            $strategy->attach($view->getEventManager());
        });
    }
}