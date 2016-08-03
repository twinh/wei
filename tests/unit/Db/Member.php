<?php

namespace WeiTest\Db;

class Member extends Record
{
    protected $table;

    protected $fullTable;

    protected $scopes;

    protected $loadTimes;

    protected $eventResult;

    protected $data = array(
        'group_id' => 0
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

    public function beforeCreate()
    {
        $this->eventResult .= 'beforeCreate->';
    }

    public function afterCreate()
    {
        $this->eventResult .= 'afterCreate->';
    }

    public function beforeSave()
    {
        $this->eventResult .= 'beforeSave->';
    }

    public function afterSave()
    {
        $this->eventResult .= 'afterSave';
    }

    public function beforeDestroy()
    {
        $this->eventResult .= 'beforeDestroy->';
    }

    public function afterDestroy()
    {
        $this->eventResult .= 'afterDestroy';
    }

    public function getEventResult()
    {
        return $this->eventResult;
    }
}
