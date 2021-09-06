<?php

namespace Wei;

use Symfony\Component\Console\Output\OutputInterface;
use Wei\Migration\BaseMigration;

/**
 * Migration
 *
 * @mixin \DbMixin
 * @mixin \SchemaMixin
 * @mixin \ClassMapMixin
 */
class Migration extends Base
{
    /**
     * @var array
     */
    protected $paths = [
        'src',
        'plugins/*/src',
    ];

    /**
     * @var string
     */
    protected $defaultPath = 'src/Migration';

    /**
     * @var string
     */
    protected $defaultNamespace = 'App\Migration';

    /**
     * @var string
     */
    protected $table = 'migrations';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->prepareTable();
    }

    /**
     * Output the migration status table
     */
    public function status()
    {
        $status = $this->getStatus();
        if (!$status) {
            $this->writeln('No migrations found.');

            return;
        }

        $this->writeln(' Ran?    Name ');
        $this->writeln('--------------');

        foreach ($status as $row) {
            if ($row['migrated']) {
                $mark = '<info>Y</info>';
            } else {
                $mark = '<error>N</error>';
            }
            $this->writeln(' ' . $mark . '       ' . $row['id']);
        }
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        $data = [];
        $migratedIds = $this->getMigratedIds();
        $classes = $this->getMigrationClasses();

        foreach ($classes as $id => $class) {
            $data[] = [
                'id' => $id,
                'migrated' => in_array($id, $migratedIds, true),
            ];
        }

        return $data;
    }

    /**
     * @param OutputInterface $output
     * @return $this
     * @svc
     */
    protected function setOutput(OutputInterface $output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @svc
     */
    protected function migrate()
    {
        $classes = $this->getMigrationClasses();

        $migrations = $this->db->init($this->table)
            ->desc('id')
            ->indexBy('id')
            ->fetchAll();

        foreach ($migrations as $id => $migration) {
            if (isset($classes[$id])) {
                unset($classes[$id]);
            }
        }

        if (!$classes) {
            $this->writeln('<info>Nothing to migrate.</info>');
            return;
        }

        foreach ($classes as $id => $class) {
            $migration = $this->instance($classes[$id]);
            $migration->up();

            $this->db->insert($this->table, [
                'id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $this->writeln('<info>Migrated: </info>' . $id);
        }
    }

    protected function getMigrationClasses()
    {
        $classMap = $this->classMap->generate($this->paths, '/Migration/*.php', 'Migration', false);

        return $classMap;
    }

    /**
     * Rollback the last migration or to the specified target migration ID
     *
     * @param array $options
     * @svc
     */
    protected function rollback($options = [])
    {
        $classes = $this->getMigrationClasses();
        $migrationIds = $this->getMigratedIds();

        if (isset($options['target'])) {
            $index = array_search($options['target'], $migrationIds, true);
            if (false === $index) {
                $this->writeln(sprintf('<error>Target "%s" not found</error>', $options['target']));
                return;
            }

            // Return migrations included target
            $migrationIds = array_slice($migrationIds, 0, $index + 1, true);
        } else {
            $migrationIds = [current($migrationIds)];
        }

        if (!$migrationIds) {
            $this->writeln('<info>Nothing to rollback.</info>');
            return;
        }

        foreach ($migrationIds as $id) {
            if (isset($classes[$id])) {
                $migration = $this->instance($classes[$id]);
                $migration->down();
            } else {
                $this->writeln(sprintf('<error>Missing migration "%s"</error>', $id));
            }

            $this->db->delete($this->table, ['id' => $id]);
            $this->writeln('<info>Rolled back: </info>' . $id);
        }
    }

    /**
     * Rollback all migrations
     *
     * @svc
     */
    protected function reset()
    {
        $migrationIds = $this->getMigratedIds();
        $this->rollback(['target' => $migrationIds ? end($migrationIds) : null]);
    }

    /**
     * @param array $options
     * @throws \ReflectionException
     * @throws \Exception
     * @svc
     */
    protected function create($options)
    {
        $class = 'V' . date('YmdHis') . $options['name'];
        $path = $options['path'] ?? $this->defaultPath;
        $namespace = $options['namespace'] ?? $this->defaultNamespace;

        if (!$path) {
            $this->writeln('<error>Path should not be empty</error>');
            return;
        }

        $content = $this->generateContent(compact('namespace', 'class'));

        $this->makeDir($path);
        $file = $path . '/' . $class . '.php';
        file_put_contents($file, $content);
        $this->writeln(sprintf('<info>Created the file: %s</info>', $file));
    }

    protected function generateContent($vars)
    {
        extract($vars);

        ob_start();
        require __DIR__ . '/Migration/stubs/migration.php';
        return ob_get_clean();
    }

    protected function getMigratedIds()
    {
        $migrations = $this->db($this->table)
            ->desc('id')
            ->indexBy('id')
            ->fetchAll();

        return array_keys($migrations);
    }

    /**
     * Create a instance from migration class
     *
     * @param string $class
     * @return BaseMigration
     */
    protected function instance($class)
    {
        $object = new $class([
            'wei' => $this->wei,
        ]);

        return $object;
    }

    /**
     * Check if table exists, if not exists, create table
     */
    protected function prepareTable()
    {
        if (!$this->schema->hasTable($this->table)) {
            $this->schema->table($this->table)
                ->string('id', 128)
                ->timestamp('created_at')
                ->exec();
        }
    }

    protected function writeln($output)
    {
        if ($this->output) {
            $this->output->writeln($output);
        }
    }

    protected function makeDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
    }
}
