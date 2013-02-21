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
 * @property \Widget\EntityManager $entityManager The doctrine orm entity manager widget
 * @method \Doctrine\ORM\EntityManager entityManager() Returns the doctrine orm entity object
 * @todo        Adds property like dql, queryBuilder, etc
 */
class EntityExists extends AbstractValidator
{
    protected $notFoundMessage = '%name% not exists';
    
    protected $notMessage = '%name% already exists';

    protected $entityClass;
    
    protected $entity;
    
    public function __invoke($input, $entityClass = null)
    {
        $entityClass && $this->entityClass = $entityClass;
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $this->entity = $this->entityManager()->find($this->entityClass, $input);
        
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
