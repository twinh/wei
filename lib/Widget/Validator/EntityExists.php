<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is existing Doctrine ORM entity
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EntityManager $entityManager The doctrine orm entity manager widget
 * @method \Doctrine\ORM\EntityManager entityManager() Returns the doctrine orm entity object
 */
class EntityExists extends AbstractValidator
{
    protected $notFoundMessage = '%name% not exists';
   
    protected $negativeMessage = '%name% already exists';

    protected $entityClass;
   
    protected $field;
   
    protected $entity;
   
    protected $criteria;
   
    public function __invoke($input, $entityClass = null, $filed = null)
    {
        $entityClass && $this->storeOption('entityClass', $entityClass);
        $filed && $this->storeOption('field', $filed);
        
        return $this->isValid($input);
    }
    
    protected function validate($input)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->entityManager();

        /* @var $repo \Doctrine\ORM\EntityRepository */
        $repo = $em->getRepository($this->entityClass);

        if ($this->field) {
            $this->entity = $repo->findOneBy(array(
                $this->field => $input
            ));
        } elseif ($this->criteria) {
            $this->entity = $repo->findOneBy($this->criteria);
        } else {
            $this->entity = $repo->find((string)$input);
        }
       
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
