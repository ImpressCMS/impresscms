<?php

namespace ImpressCMS\Core\SetupSteps;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OutputDecorator implements OutputInterface
{
	/**
	 * Ident for each line in left
	 *
	 * @var int
	 */
	protected $indent = 0;

	protected $output;

	/**
	 * OutputDecorator constructor.
	 *
	 * @param OutputInterface $originalOutput Output interface where this decorator will write
	 */
	public function __construct(OutputInterface $originalOutput)
	{
		$this->output = $originalOutput;
	}

	/**
	 * Increases indent
	 */
	public function incrIndent()
	{
		$this->indent++;
	}

	/**
	 * Decreases indent
	 */
	public function decrIndent()
	{
		$this->indent--;
	}

	/**
	 * Resets indent
	 */
	public function resetIndent()
	{
		$this->indent = 0;
	}

	/**
	 * Prints fatal message
	 *
	 * @param string $message Message to print
	 * @param mixed ...$params Params for parsing message
	 */
	public function fatal(string $message, ...$params): void
	{
		$this->write('<error>' . vsprintf($message, $params) . '</error>', true);
	}

	/**
	 * Prints success message
	 *
	 * @param string $message Message to print
	 * @param mixed ...$params Params for parsing message
	 */
	public function success(string $message, ...$params): void
	{
		$this->write('<info>' . vsprintf($message, $params) . '</info>', true);
	}

	/**
	 * Prints error message
	 *
	 * @param string $message Message to print
	 * @param mixed ...$params Params for parsing message
	 */
	public function error(string $message, ...$params): void
	{
		$this->write('<error>' . vsprintf($message, $params) . '</error>', true);
	}

	/**
	 * Prints info message
	 *
	 * @param string $message Message to print
	 * @param mixed ...$params Params for parsing message
	 */
	public function info(string $message, ...$params): void
	{
		$this->write('<comment>' . vsprintf($message, $params) . '</comment>', true);
	}

	/**
	 * Prints simple message
	 *
	 * @param string $message Message to print
	 * @param mixed ...$params Params for parsing message
	 */
	public function msg(string $message, ...$params): void
	{
		$this->write(vsprintf($message, $params), true);
	}

	/**
	 * @inheritDoc
	 */
	public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
	{
		if ($this->indent > 0) {
			$tmpMsg = '';
			foreach (explode(PHP_EOL, $messages) as $line) {
				$tmpMsg .= str_repeat(' ', $this->indent * 2) . $line . PHP_EOL;
			}
			$messages = rtrim($tmpMsg);
		}
		/*if (trim($messages) == '') {
			return $this->output->write(var_export([$messages, debug_backtrace(false)[1]], true), $newline, $options);
		}*/
		return $this->output->write($messages, $newline, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function writeln($messages, $options = 0)
	{
		$this->write($messages, true, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function setVerbosity($level)
	{
		$this->output->setVerbosity($level);
	}

	/**
	 * @inheritDoc
	 */
	public function getVerbosity()
	{
		return $this->output->getVerbosity();
	}

	/**
	 * @inheritDoc
	 */
	public function isQuiet()
	{
		return $this->output->isQuiet();
	}

	/**
	 * @inheritDoc
	 */
	public function isVerbose()
	{
		return $this->output->isVerbose();
	}

	/**
	 * @inheritDoc
	 */
	public function isVeryVerbose()
	{
		return $this->output->isVeryVerbose();
	}

	/**
	 * @inheritDoc
	 */
	public function isDebug()
	{
		return $this->output->isDebug();
	}

	/**
	 * @inheritDoc
	 */
	public function setDecorated($decorated)
	{
		$this->output->setDecorated($decorated);
	}

	/**
	 * @inheritDoc
	 */
	public function isDecorated()
	{
		return $this->output->isDecorated();
	}

	public function setFormatter(OutputFormatterInterface $formatter)
	{
		$this->output->setFormatter($formatter);
	}

	/**
	 * @inheritDoc
	 */
	public function getFormatter()
	{
		return $this->output->getFormatter();
	}
}