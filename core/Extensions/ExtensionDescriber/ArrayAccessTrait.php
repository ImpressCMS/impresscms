<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber;

/**
 * Access class properties as array items
 */
trait ArrayAccessTrait
{

	/**
	 * @inheritDoc
	 */
	public function offsetExists($offset)
	{
		return property_exists($this, $offset);
	}

	/**
	 * @inheritDoc
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	/**
	 * @inheritDoc
	 */
	public function offsetSet($offset, $value)
	{
		if (!$this->offsetExists($offset)) {
			throw new \RuntimeException('Property not found');
		}

		$this->$offset = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function offsetUnset($offset)
	{
		throw new \RuntimeException('Unsetting is not supported');
	}

}