<?php

namespace WidgetTest\DbTest;

class Member extends Record
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
        return $this->db->find('post', array('member_id' => $this->data['id']));
    }

    public function group()
    {
        return $this->db->find('member_group', array('id' => $this->data['group_id']));
    }

    public function posts()
    {
        return $this->db->findAll('post', array('member_id' => $this->data['id']));
    }
}