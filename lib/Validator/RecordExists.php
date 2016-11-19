<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is existing table record
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class RecordExists extends BaseValidator
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
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        $this->data = $this->db->find($this->table, array($this->field => $input));

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
