<?php

namespace ZfcCrudRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;
use Zend\View\Model\JsonModel;
use ZfcCrud\Mapper\DbMapperInterface as DBMapper;
use ZfcCrudRest\Service\RestFul as RestService;

class RestfulController extends ZendAbstractRestfulController
{
    protected $service;

    protected $mapper;

    public function getList()
    {
        $data = $this->getMapper()->findAll(
            null, new \Zend\Stdlib\Hydrator\ClassMethods
        );

        return new JsonModel($data);
    }

    public function get($id)
    {
        $entity = $this->getMapper()->findById($id, new \Zend\Stdlib\Hydrator\ClassMethods);

        return new JsonModel($entity);
    }

    public function create($data)
    {
        $entity = $this->service->create($data);
        $entity = $this->getMapper()->create($entity);
        $id = $entity->getId();

        $entity = $this->getMapper()->findById($id, new \Zend\Stdlib\Hydrator\ClassMethods);

        return new JsonModel($entity);
    }

    public function update($id, $data)
    {
        $entity = $this->service->update($id, $data);
        $entity = $this->getMapper()->update($entity);
        $entity = $this->getMapper()->findById($id, new \Zend\Stdlib\Hydrator\ClassMethods);
        return new JsonModel($entity);
    }


    public function delete($id)
    {
        $entity = $this->getMapper()->findById($id);
        $this->getMapper()->delete($entity);
        return new JsonModel(array('deleted'));
    }

    public function setService(RestService $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setMapper(DbMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getMapper()
    {
        if (!$this->mapper) {
            $this->mapper = $this->sm->get('crud_db_mapper');
        }
        return $this->mapper;
    }
}