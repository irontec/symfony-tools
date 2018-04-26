<?php

namespace SymfonyTools\Services;

class GetEntities
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $_doctrine;

    /**
     * @var string
     */
    protected $_entityNamespace;

    /**
     * Limite de la paginación por defecto
     *
     * @var integer
     */
    protected $_limit = 10;

    public function __construct($doctrine, $entityNamespace)
    {
        $this->_doctrine = $doctrine;
        $this->_entityNamespace = $entityNamespace;
    }

    /**
     * Listas las entidades resultantes de la combinación de los parametros.
     *
     * @param string $entity Nombre de la entidad
     * @param array $where array de array con los criterios de busqueda:
     * array(
        array('field' => 'name', 'value' => 'Irontec', 'method' => '='),
        array('field' => 'desc', 'value' => '%ome!!%', 'method' => 'like')
       )
     * @param array $order array de clave valor:
     * array('name' => 'ASC', 'description' => 'ASC')
     * @param integer |boolean $limit
     * @param integer $page
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function fetchList(
        $entity,
        array $where = array(),
        array $order = array(),
        $limit = false,
        $page = 0
    )
    {

        $offset = $this->_prepareOffset($page, $limit);

        $repository = $this->_doctrine->getRepository(
            $this->_entityNamespace . ':' . $entity
        );

        $cQB = $repository->createQueryBuilder($entity);

        if (!empty($where)) {
            $cQB = $this->_createWhere($cQB, $entity, $where);
        }

        if (!empty($order)) {
            foreach ($order as $key => $val) {
                $cQB->orderBy($entity . '.' . $key, $val);
            }
        }

        $cQB->setFirstResult($offset);
        $cQB->setMaxResults($limit);

        return $cQB->getQuery()->execute();

    }

    /**
     * Metodo custom para crear los where de las busquedas
     *
     * @param \Doctrine\ORM\QueryBuilder $cQB
     * @param string $entity
     * @param array $conditions
     * @return array
     */
    protected function _createWhere($cQB, $entity, $conditions)
    {

        $where = array();
        $params = array();

        foreach ($conditions as $condition) {

            $field = $condition['field'];
            $value = $condition['value'];

            if (!isset($condition['method'])) {
                $where[] = sprintf(
                    '%s.%s = :%s',
                    $entity,
                    $field,
                    $field
                );
            } else {

                $method = $condition['method'];

                $where[] = sprintf(
                    '%s.%s %s :%s',
                    $entity,
                    $field,
                    $method,
                    $field
                );

            }

            $params[$field] = $value;

        }

        $where = implode(' AND ', $where);

        $cQB->add(
            'where',
            $where
        );

        $cQB->setParameters($params);

        return $cQB;

    }

    /**
     * Crea el Offset de la paginación (setFirstResult)
     *
     * @param integer $page
     * @param integer|boolean $limit
     * @return number
     */
    protected function _prepareOffset(
        $page = 0,
        $limit = false
    )
    {
        if ($limit === false) {
            $limit = $this->_limit;
        }

        if (isset($page) && $page > 0) {
            return ($page - 1) * $limit;
        }
        return 0;
    }

}
