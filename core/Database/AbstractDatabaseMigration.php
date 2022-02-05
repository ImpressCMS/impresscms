<?php

namespace ImpressCMS\Core\Database;

use Phoenix\Database\Adapter\AdapterInterface;
use Phoenix\Migration\AbstractMigration;
use Psr\Container\ContainerInterface;

/**
 * Extends abstract phoenix migration with some needed ImpressCMS functionality
 */
abstract class AbstractDatabaseMigration extends AbstractMigration
{

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * Migration constructor
	 *
	 * @param AdapterInterface $adapter
	 * @param ContainerInterface $container
	 */
	public function __construct(AdapterInterface $adapter, ContainerInterface $container)
	{
		$this->container = $container;

		parent::__construct($adapter);
	}

	/**
	 * Prefix a table
	 *
	 * @param string $table Table to prefix
	 *
	 * @return string
	 */
	protected function prefix(string $table): string {
		return $this->getDatabaseConnection()->prefix($table);
	}

	/**
	 * Gets native database connection instance
	 *
	 * @param int $no Database connection number
	 *
	 * @return DatabaseConnectionInterface
	 */
	protected function getDatabaseConnection(int $no = 1): DatabaseConnectionInterface {
		return $this->container->get('db-connection-'.$no);
	}

	/**
	 * Get container
	 *
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface {
		return $this->container;
	}
}