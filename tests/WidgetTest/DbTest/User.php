<?php

namespace WidgetTest\DbTest;

class User extends Record
{
    protected $table;

    protected $fullTable;

    protected $scopes;

    // default value
    protected $data = array(
        'age' => 0,
    );

    public function post()
    {
        return $this->db->find('post', array('user_id' => $this->data['id']));
    }

    public function group()
    {
        return $this->db->find('user_group', array('id' => $this->data['group_id']));
    }

    public function posts()
    {
        return $this->db->findAll('post', array('user_id' => $this->data['id']));
    }
}