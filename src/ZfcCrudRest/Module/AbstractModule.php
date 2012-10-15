<?php

namespace ZfcCrudRest\Module;

use ZfcBase\Module\AbstractModule as ZfcBaseAbstractModule;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;


abstract class AbstractModule extends ZfcBaseAbstractModule
{

    public function init(ModuleManager $moduleManager)
    {
        parent::init($moduleManager);

        $em = $moduleManager->getEventManager()->getSharedManager();

        $em->attach($this->getNamespace(), MvcEvent::EVENT_DISPATCH, function($e) {
            $app = $e->getApplication();
            $sm  = $app->getServiceManager();

            $strategy = $sm->get('ViewJsonStrategy');
            $view     = $sm->get('ViewManager')->getView();
            $strategy->attach($view->getEventManager());
        });
    }
}