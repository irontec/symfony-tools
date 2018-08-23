<?php

namespace SymfonyTools\Services;

/**
 * Clase que ayuda con a limpiar y asignar variables a la entidad antes de enviarla a MySQL
 *
 * @author ddniel16
 */
class Setter
{

    /**
     * Limpia los campos autorizados en la request
     *
     * @param array $fields
     * @param object $entity
     * @throws \Exception
     * @return array
     */
    public function cleanFields(array $fields = array(), $entity = null):array  {
        $result = array();

        $request = \SymfonyTools\Request\Request::getRequest();
        $properties = array();
        $nullableRegex = "/(nullable)[\s]*(=)[\s]*(true|false)/";

        if(!is_null($entity)){
            $reflect = new \ReflectionClass($entity);
            $reflectedProperties   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED| \ReflectionProperty::IS_PRIVATE);

            foreach ($reflectedProperties as $prop) {
                if(preg_match($nullableRegex, $prop->getDocComment(),$match)){
                    if(strtolower($match[3]) === "true"){
                        $properties[$prop->getName()] = true;
                    }else{
                        $properties[$prop->getName()] = false;
                    }
                }else{
                    continue;
                }
            }
        }

        foreach ($fields as $field) {
            $val = trim($request->get($field, ''));
            $inProperties = in_array($field,$properties);

            if ($val === '' && $inProperties === true) {
                if($properties[$field] === true){
                    $result[$field] = null;
                }else{
                    continue;
                }
            }else{
                $result[$field] = trim($val);
            }
        }

        if (empty($result)) {
            \SymfonyTools\Services\Exception::error('Empty content', 400);
        }

        return $result;
    }

    /**
     * Valida que los parametros obligatorios esten y no esten en vacios
     *
     * @param array $fields
     * @param array $data
     * @throws \Exception
     */
    public function requireFields(
        array $fields = array(),
        array $data = array()
    )
    {

        $fieldsError = array();
        foreach ($fields as $field) {

            if (!isset($data[$field])) {
                $fieldsError[] = $field;
                continue;
            }

            $value = trim($data[$field]);
            if ($value === '') {
                $fieldsError[] = $field;
                continue;
            }

        }

        if (!empty($fieldsError)) {
            $msg = sprintf(
                'The parameters are missing: %s',
                implode(', ', $fieldsError)
            );
            \SymfonyTools\Services\Exception::error($msg, 409);
        }

    }

    /**
     * Hace un set de los datos a la entidad
     *
     * @param object $entity
     * @param array $fields
     * @return object
     */
    public function setEntity($entity, array $fields = array())
    {

        foreach ($fields as $key => $val) {
            $setter = 'set' . ucwords($key);
            $entity->$setter($val);
        }

        return $entity;

    }

}
