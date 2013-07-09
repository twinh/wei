<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is existing table record
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @method      \Doctrine\DBAL\Connection db() The Doctrine DBAL connection object
 */
class RecordExists extends AbstractValidator
{
    /**
     * The message added when query return empty result
     *
     * @var string
     */
    protected $notFoundMessage = '%name% not exists';

    /**
     * The message for negative mode
     *
     * @var string
     */
    protected $negativeMessage = '%name% already exists';

    /**
     * The name of table
     *
     * @var string
     */
    protected $table;

    /**
     * The field to search
     *
     * @var string
     */
    protected $field = 'id';

    /**
     * The data fetch from database
     *
     * @var array
     */
    protected $data = array();

    /**
     * Check if the input is existing table record
     *
     * @param string $input
     * @param string $table
     * @param string $field
     * @return bool
     */
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
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        $this->data = $this->db
            ->createQueryBuilder()
            ->from($this->table)
            ->where($this->field . ' = ?', $input)
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
