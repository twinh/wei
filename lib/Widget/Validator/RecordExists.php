<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input is existing table record
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method      \Doctrine\DBAL\Connection db() The Doctrine DBAL connection object
 */
class RecordExists extends AbstractValidator
{
    protected $notFoundMessage = '%name% not exists';
   
    protected $negativeMessage = '%name% already exists';
    
    protected $table;
    
    protected $field = 'id';
    
    /**
     * The data fetch from database
     * 
     * @var array
     */
    protected $data = array();
    
    public function __invoke($input = null, $table = null, $field = 'id')
    {
        $table && $this->storeOption('table', $table);
        $field && $this->storeOption('field', $field);

        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $db = $this->db();
        
        $this->data = $db->createQueryBuilder()
            ->select('*')
            ->from($this->table, 't')
            ->where('t.' . $this->field . ' = :value')
            ->setParameter('value', $input)
            ->execute()
            ->fetch();
        
        if (empty($this->data)) {
            $this->addError('notFound');
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns the data fetch from database
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}