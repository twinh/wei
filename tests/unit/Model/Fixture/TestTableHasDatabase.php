<?php

namespace WeiTest\Model\Fixture;

class TestTableHasDatabase extends TestUser
{
    public function getTable(): string
    {
        return $this->db->getDbname() . '.test_users';
    }
}
