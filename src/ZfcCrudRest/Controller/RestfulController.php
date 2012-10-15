<?php

namespace ZfcCrudRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;
use Zend\View\Model\JsonModel;
use ZfcCrud\Mapper\AbstractDBMapper as DBMapper;
use ZfcCrud\Service\AbstractRestService as Service;

class RestfulController extends ZendAbstractRestfulController
{
    protected $service;

    protected $mapper;

    public function getList()
    {
        $data = $this->getMapper()->findAll(
            new \Zend\Stdlib\Hydrator\ClassMethods
        );
        return $data;
    }

    public function get($id)
    {
        $entity = $this->getMapper()->findById($id);
        $data = $this->getMapper()->entityToArray($entity);
        return $data;
    }

    public function create($data)
    {
        $entity = $this->service->create($data);
        $entity = $this->getMapper->insert($entity);
        $data = $this->getMapper()->entityToArray($entity);

        return $data;
        // 
        /*$this->getResponse()->setHeader(
            'Location',
            '/' . lcfirst($this->_packageName) . $this->entityName . '/'
            . $dto->id,
            true
        );*/
        //$this->getResponse()->setHttpResponseCode(
        //return array('data' => 'create');
    }

    public function update($id, $data)
    {
        $entity = $this->service->update($id, $data);
        $entity = $this->getMapper->update($entity);
        $data = $this->getMapper()->entityToArray($entity);
        return $data;
    }


    public function delete($id)
    {
        $entity = $this->getMapper->findById($id);
        $this->getMapper->remove($entity);
        
        echo 'deleted';
        exit;
        $this->getResponse()->setHttpResponseCode(204);
        //return array('data' => 'delete');
    }

    public function setService(Service $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setMapper(DBMapper $mapper) 
    {
        $this->mapper();
    }

    public function getMapper()
    {
        if (!$this->mapper instanceof DBMapper) {
            $this->mapper = $this->sm->get('crud_db_mapper');
        }
        return $this->mapper;
    }
}