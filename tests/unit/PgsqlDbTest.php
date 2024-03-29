<?php

namespace WeiTest;

/**
 * @internal
 */
final class PgsqlDbTest extends DbTest
{
    protected function setUp(): void
    {
        $this->db = $this->wei->get('pgsql:db');

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
        id SERIAL NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_member (
        id SERIAL NOT NULL,
        group_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        address VARCHAR(256) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post (
        id SERIAL NOT NULL,
        member_id INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_tag (
        id SERIAL NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY(id))');

        $db->query('CREATE TABLE prefix_post_tag (
        post_id SERIAL NOT NULL,
        tag_id INTEGER NOT NULL)');
    }

    protected function dropTable()
    {
        $db = $this->db;
        $db->query('DROP TABLE IF EXISTS prefix_member_group');
        $db->query('DROP TABLE IF EXISTS prefix_member');
        $db->query('DROP TABLE IF EXISTS prefix_post');
        $db->query('DROP TABLE IF EXISTS prefix_tag');
        $db->query('DROP TABLE IF EXISTS prefix_post_tag');
    }
}
