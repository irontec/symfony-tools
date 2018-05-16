<?php

namespace SymfonyTools\Services;

class DoctrineCommon
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $_doctrine;

    public function __construct($doctrine)
    {
        $this->_doctrine = $doctrine;
    }

    /**
     * @param string $entityName psr4 de la entidad a eliminar
     * @param int $id identificador de la entidad
     * @throws \Exception
     */
    public function deleteEntityById(
        $entityName,
        $id
    )
    {

        $entity = $this->_doctrine->getRepository($entityName)->find($id);

        $this->deleteEntity($entity);

    }

    /**
     * @param object $entity
     * @throws \Exception
     */
    public function deleteEntity($entity)
    {

        try {

            $em = $this->_doctrine->getManager();
            $em->remove($entity);
            $em->flush();

        } catch (\Exception $e) {
            \SymfonyTools\Services\Exception::error($e->getMessage(), $e->getCode());
        }

    }

}
