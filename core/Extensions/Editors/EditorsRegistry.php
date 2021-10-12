<?php

namespace ImpressCMS\Core\Extensions\Editors;

use Exception;
use Imponeer\Contracts\Editor\Adapter\EditorAdapterInterface;
use Imponeer\Contracts\Editor\Exceptions\IncompatibleEditorException;
use Imponeer\Contracts\Editor\Factory\EditorFactoryInterface;
use Imponeer\Contracts\Editor\Info\WYSIWYGEditorInfoInterface;
use Psr\Container\ContainerInterface;

/**
 * Gaiters info about editors and makes instances
 *
 * @package ImpressCMS\Core\Extensions\Editors
 */
class EditorsRegistry
{

	/**
	 * What editors are allowed to be used
	 *
	 * @var string[]
	 */
	protected $allowedEditors = [];

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * EditorsRegistry constructor.
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Creates editor instance
	 *
	 * @param string $type Editor type
	 * @param string $name Internal editor name
	 * @param array $options Editor options
	 * @param bool $noHtml No HTML mode?
	 * @param string $onFailure Another editor that will be returned on first editor fail
	 *
	 * @return EditorAdapterInterface|null
	 *
	 * @throws IncompatibleEditorException
	 */
	public function create(string $type, string $name = '', ?array $options = null, bool $noHtml = false, string $onFailure = ''): ?EditorAdapterInterface
	{
		if (!is_array($options)) {
			$options = [];
		}

		if ($this->has($name)) {
			return $this->createAdapter($name, $options);
		}

		$list = array_keys($this->getList($type, $noHtml));
		if (empty($onFailure) || !in_array($onFailure, $list, true)) {
			$onFailure = $list[0];
		}

		return $this->createAdapter($onFailure, $options);
	}

	/**
	 * Checks if such editor exists
	 *
	 * @param string $name Editor name
	 *
	 * @return bool
	 */
	public function has(string $name): bool
	{
		return $this->getFactory($name) !== null;
	}

	/**
	 * Gets editor factory instance
	 *
	 * @param string $editorName Editor name
	 *
	 * @return EditorFactoryInterface|null
	 */
	protected function getFactory(string $editorName): ?EditorFactoryInterface
	{
		if (!$editorName) {
			return null;
		}

		if (!$this->container->has($editorName)) {
			return null;
		}

		$editorFactory = $this->container->get($editorName);
		return ($editorFactory instanceof EditorFactoryInterface) ? $editorFactory : null;
	}

	/**
	 * Create editor adapter instance
	 *
	 * @param string $name Editor internal name
	 * @param array $options Editor options
	 *
	 * @return EditorAdapterInterface|null
	 *
	 * @throws IncompatibleEditorException
	 */
	protected function createAdapter(string $name, array $options = []): ?EditorAdapterInterface
	{
		$factory = $this->getFactory($name);
		if (!$factory) {
			return null;
		}

		return $factory->create($options, true);
	}

	/**
	 * Gets list of available editors
	 *
	 * @param string $type Editors type
	 * @param bool $noHtml Do we need to use NoHTML options for this editor?
	 *
	 * @return  array<string, string>
	 *
	 * @throws Exception
	 */
	public function getList(string $type, bool $noHtml = false): array
	{
		$editors = [];

		$editorTag = 'editor.' . $type;

		if (!$this->container->has($editorTag)) {
			return $editors;
		}

		foreach ($this->container->get($editorTag) as $editorFactory) {
			if (!($editorFactory instanceof EditorFactoryInterface)) {
				continue;
			}
			$name = $this->container->getServiceDefinition(get_class($editorFactory))->getAlias();
			if (!empty($this->allowedEditors) && !in_array($name, $this->allowedEditors, true)) {
				continue;
			}
			$editorInfo = $editorFactory->getInfo();
			if ($noHtml && ($editorInfo instanceof WYSIWYGEditorInfoInterface)) {
				continue;
			}
			$editors[$name] = $editorInfo->getName();
		}

		asort($editors);

		return $editors;
	}
}
