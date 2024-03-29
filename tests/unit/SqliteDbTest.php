<?php

namespace WeiTest;

/**
 * @internal
 */
final class SqliteDbTest extends DbTest
{
    protected function setUp(): void
    {
        $this->db = $this->wei->get('sqlite:db');

        try {
            $this->db->connect();
        } catch (\PDOException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        parent::setUp();
    }

    protected function createTable()
    {
        $db = $this->db;
        $db->query('CREATE TABLE prefix_member_group (
        id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_member (
        id INTEGER NOT NULL,
        group_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        address VARCHAR(256) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post (
        id INTEGER NOT NULL,
        member_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_tag (
        id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post_tag (
        post_id INTEGER NOT NULL,
        tag_id INTEGER NOT NULL)');
    }
}
