<?php

namespace ZfcCrudJsonRest\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcCrud\Mapper\DbMapperInterface as DBMapper;
use Doctrine\ORM\QueryBuilder;

class Restful implements ServiceManagerAwareInterface
{
    protected $sm;

    protected $mapper;

    protected $count;

    public function __construct(DBMapper $mapper) {
        $this->mapper = $mapper;
    }

    public function create(array $data) 
    {   
        $entityClassName = $this->mapper->getEntityClassName();
        $entity = new $entityClassName;
        $entity = $this->bind($data, $entity);
        return $entity;
    }

    public function update($id, $data)
    {
        $entity = $this->mapper->findById($id);
        $entity = $this->bind($data, $entity);
        return $entity;
    }

    public function bind($data, $entity) 
    {
        foreach($data as $element => $value)
        {
            $func = 'set' . ucfirst($element);
            $entity->$func($value);
        }
        return $entity;
    }

    public function delete($id) 
    {
        $entity = $this->getById($id);
        $em = $this->sm->get('doctrine.entitymanager.orm_default');
        $em->remove($entity);
        $em->flush();
    }

    public function setCount($count) {
        $this->count = $count;
    }

    protected function getQueryBuilder($start = 0, $count = 100) 
    {
        $em = $this->sm->get('doctrine.entitymanager.orm_default');
        $qb = $em->createQueryBuilder();
        $qb->select('entity')->from($this->getEntity(), 'entity');

        $qb->setFirstResult($start);
        $qb->setMaxResults($count);

        return $qb;
    }

    public function setEntity($entity)
    {
        $this->entity;
    }

    public function setServiceManager(ServiceManager $sm) {
        $this->sm = $sm;
    }
}