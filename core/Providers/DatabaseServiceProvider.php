<?php

namespace ImpressCMS\Core\Providers;

use Aura\Sql\ExtendedPdoInterface;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Database service provider
 */
class DatabaseServiceProvider extends AbstractServiceProvider
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'db',
		'xoopsDB',
		'db-connection-1'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		/**
		 * @var Container $container
		 */
		$container = $this->getContainer();

		$container->add('db-connection-1', function () {
			return $this->createDatabaseConnection(
				env('DB_TYPE', 'mysql'),
				env('DB_HOST', '127.0.0.1'),
				env('DB_USER', ''),
				env('DB_PASS', ''),
				(int)env('DB_PCONNECT', 0),
				env('DB_NAME', 'impresscms'),
				env('DB_CHARSET', 'utf8'),
				env('DB_PREFIX'),
				(int)env('DB_PORT', 3306)
			);
		})->addTag('database_connection');

		/* These lines should be enabled in the future
		 *
		 * $i = 1;
		while($host = env('DB_HOST_' . (++$i), null)) {
			$is_master = (bool)env('DB_MASTER', 0);
			$container->add('db-connection-' . $i, function () use ($host, $i) {
				return $this->createDatabaseConnection(
					env('DB_TYPE', 'mysql'),
					$host,
					env('DB_USER_' . $i, env('DB_USER')),
					env('DB_PASS_' . $i, env('DB_PASS')),
					(int)env('DB_PCONNECT', 0),
					env('DB_NAME_' .$i, env('DB_NAME', 'impresscms')),
					env('DB_CHARSET', 'utf8'),
					env('DB_PREFIX'),
					(int)env('DB_PORT_' . $i, env('DB_PORT', 3306))
				);
			})
				->addTag('database_connection')
				->addTag('database_connection_' . ($is_master ? 'write' : 'read') );
		}*/

		$container->add('db', function () use ($container) {
			return $container->get('db-connection-1');
			/*return new ConnectionLocator(
				function () use ($container) {
					return $container->get('db-connection-1');
				},
				$container->has('database_connection_read') ? $container->get('database_connection_read') : [],
				$container->has('database_connection_write') ? $container->get('database_connection_write') : []
			);*/
		});
		$container->add('xoopsDB', function () use ($container) {
			return $container->get('db');
		});
	}

	/**
	 * Create database connection
	 *
	 * @param string $type Database type
	 * @param string $host Hostname
	 * @param string $user Username
	 * @param string $pass Password
	 * @param $persistentConnection Use persistent connection?
	 * @param string|null $name Database name
	 * @param string|null $charset Charset used for connection
	 * @param string $prefix Database tables prefix
	 * @param int $port Port
	 *
	 * @return ExtendedPdoInterface
	 * @throws \Exception
	 */
	protected function createDatabaseConnection(string $type, string $host, string $user, ?string $pass, $persistentConnection, ?string $name, ?string $charset, ?string $prefix, int $port): ExtendedPdoInterface
	{
		if (substr($type, 0, 4) == 'pdo.') {
			$type = substr($type, 4);
		}

		$dsn = $type . ':host=' . $host;
		if ($name && !(defined('DB_NO_AUTO_SELECT') && DB_NO_AUTO_SELECT)) {
			$dsn .= ';dbname=' . $name;
		}
		$dsn .= ';port=' . $port;
		if ($charset) {
			$dsn .= ';charset=' . $charset;
		}

		$options = [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_PERSISTENT => (bool)$persistentConnection,
		];

		$connection = new \icms_db_Connection(
			$dsn,
			$user,
			$pass,
			$options
		);

		$connection->setPrefix($prefix);

		$connection->connect();

		if (!$connection->isConnected()) {
			/* this requires that include/functions.php has been loaded */
			icms_loadLanguageFile('core', 'core');
			trigger_error(_CORE_DB_NOTRACEDB, E_USER_ERROR);
		}

		$enabled = (bool)env('LOGGING_ENABLED', false);
		$logger = new \icms_core_Logger(
			'DB',
			[
				new \Monolog\Handler\RotatingFileHandler(
					ICMS_LOGGING_PATH . '/db.log',
					0,
					$enabled ? \Monolog\Logger::DEBUG : \Monolog\Logger::ERROR
				)
			]
		);
		if ($enabled) {
			$logger->enableRendering();
		}

		$connection->setProfiler(
			new \Aura\Sql\Profiler\Profiler($logger)
		);

		$connection->getProfiler()->setLogFormat('{function} ({duration} seconds): {statement}');
		$connection->getProfiler()->setActive($enabled);

		return $connection;
	}
}
