<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        Adds property like dql, queryBuilder, etc
 */
class EntityExists extends AbstractValidator
{
    protected $notFoundMessage = '%name% is not exists';
    
    protected $entityClass;
    
    protected $entity;
    
    public function __invoke($data, $entityClass = null)
    {        
        $entityClass && $this->entityClass = $entityClass;
        
        $this->entity = $this->entityManager()->find($this->entityClass, $data);
        
        if (!$this->entity) {
            $this->addError('notFound');
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns the entity object
     * 
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
