<?php

namespace WeiTest\Db;

class Member extends Record
{
    protected $table;

    protected $fullTable;

    protected $scopes;

    protected $loadTimes;

    // default value
    protected $data = array(
        'age' => 0,
    );

    public function getPost()
    {
        return $this->db->find('post', array('member_id' => $this->data['id']));
    }

    public function getGroup()
    {
        return $this->db->find('member_group', array('id' => $this->data['group_id']));
    }

    public function getPosts()
    {
        $this->posts = $this->db->findAll('post', array('member_id' => $this->data['id']));
        return $this->posts;
    }

    public function afterLoad()
    {
        $this->loadTimes++;
    }

    public function getLoadTimes()
    {
        return $this->loadTimes;
    }
}
