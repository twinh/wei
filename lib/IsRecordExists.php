<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is existing table record
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsRecordExists extends BaseValidator
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
    protected $data = [];

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
     * Returns the data fetch from database
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
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

        $this->data = $this->db->find($this->table, [$this->field => $input]);

        if (empty($this->data)) {
            $this->addError('notFound');
            return false;
        }

        return true;
    }
}
