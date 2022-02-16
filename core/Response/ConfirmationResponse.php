<?php

namespace ImpressCMS\Core\Response;

use icms;
use ImpressCMS\Core\View\Form\AbstractForm;
use ImpressCMS\Core\View\Form\Elements\ButtonElement;
use ImpressCMS\Core\View\Form\Elements\HiddenElement;
use ImpressCMS\Core\View\Form\Elements\RadioElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;
use ImpressCMS\Core\View\Form\ThemeForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Response used when user needs to confirm something
 *
 * @package ImpressCMS\Core\Response
 */
class ConfirmationResponse implements ResponseInterface
{
	/**
	 * @var array<string,string|array<string, string>>
	 */
	private $hiddenValues;

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @var string
	 */
	private $msg;

	/**
	 * @var string
	 */
	private $submit;

	/**
	 * @var bool
	 */
	private $addToken;

	/**
	 * @var ViewResponse
	 */
	private $viewResponse;

	/**
	 * ConfirmResponse constructor.
	 *
	 * @param array<string,string|array<string, string>> $hiddenValues Value to confirm
	 * @param string $action Action where send confirmation data
	 * @param string $msg Message to show for confirmation
	 * @param string $submit Submit button string
	 * @param bool $addToken Do we need to add token for form?
	 */
	public function __construct(array $hiddenValues, string $action, string $msg, string $submit = '', $addToken = true)
	{
		$this->hiddenValues = $hiddenValues;
		$this->action = $action;
		$this->msg = $msg;
		$this->submit = $submit ? trim($submit) : $this->translate('_SUBMIT');
		$this->addToken = $addToken;
		$this->viewResponse = new ViewResponse();
	}

	/**
	 * Translates a string
	 *
	 * @param string $str Value to get translation for
	 *
	 * @return string
	 */
	protected function translate(string $str): string
	{
		return $this->getTranslator()->trans($str, [], 'global');
	}

	/**
	 * Gets translator
	 *
	 * @return TranslatorInterface
	 */
	protected function getTranslator(): TranslatorInterface
	{
		return icms::getInstance()->get('translator');
	}

	/**
	 * @inheritDoc
	 */
	public function getProtocolVersion()
	{
		return $this->viewResponse->getProtocolVersion();
	}

	/**
	 * @inheritDoc
	 */
	public function withProtocolVersion($version)
	{
		$cloned = clone $this;

		$cloned->viewResponse->withProtocolVersion($version);

		return $cloned;
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaders()
	{
		return $this->viewResponse->getHeaders();
	}

	/**
	 * @inheritDoc
	 */
	public function hasHeader($name)
	{
		return $this->viewResponse->hasHeader();
	}

	/**
	 * @inheritDoc
	 */
	public function getHeader($name)
	{
		return $this->viewResponse->getHeader();
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaderLine($name)
	{
		return $this->viewResponse->getHeaderLine($name);
	}

	/**
	 * @inheritDoc
	 */
	public function withHeader($name, $value)
	{
		$cloned = clone $this;

		$cloned->viewResponse->withHeader($name, $value);

		return $cloned;
	}

	/**
	 * @inheritDoc
	 */
	public function withAddedHeader($name, $value)
	{
		$cloned = clone $this;

		$cloned->viewResponse->withAddedHeader($name, $value);

		return $cloned;
	}

	/**
	 * @inheritDoc
	 */
	public function withoutHeader($name)
	{
		$cloned = clone $this;

		$cloned->viewResponse->withoutHeader($name);

		return $cloned;
	}

	/**
	 * @inheritDoc
	 */
	public function getBody()
	{
		$this->viewResponse->assign(
			'icms_contents',
			$this->getForm()->render()
		);

		return $this->viewResponse->getBody();
	}

	/**
	 * Gets form build for this response
	 *
	 * @return AbstractForm
	 */
	public function getForm(): AbstractForm
	{
		$form = new ThemeForm($this->msg, '', $this->action, 'post', $this->addToken);
		$form->setExtra('class="confirmMsg alert" role="alert"');

		foreach ($this->hiddenValues as $name => $value) {
			if (!is_array($value)) {
				$form->addElement(
					new HiddenElement($name, $value)
				);
				continue;
			}
			foreach ($value as $caption => $value2) {
				$form->addElement(
					new RadioElement($caption, $name, $value2)
				);
			}
		}

		$buttonsTray = new TrayElement(null);
		$buttonsTray->setExtra('style="text-align: center;"');
		$buttonsTray->addElement(
			new ButtonElement('', 'confirm_submit', $this->submit, 'submit')
		);
		$backButton = new ButtonElement(
			'',
			'confirm_back',
			$this->translate('_CANCEL'),
			'button'
		);
		$backButton->setExtra('onclick="history.go(-1); return false;"');
		$buttonsTray->addElement($backButton);
		$form->addElement($buttonsTray);

		return $form;
	}

	/**
	 * @inheritDoc
	 */
	public function withBody(StreamInterface $body)
	{
		throw new RuntimeException('No body modification directly for this response type is available');
	}

	/**
	 * @inheritDoc
	 */
	public function getStatusCode()
	{
		return $this->viewResponse->getStatusCode();
	}

	/**
	 * @inheritDoc
	 */
	public function withStatus($code, $reasonPhrase = '')
	{
		$cloned = clone $this;

		$cloned->viewResponse->withStatus($code, $reasonPhrase);

		return $cloned;
	}

	/**
	 * @inheritDoc
	 */
	public function getReasonPhrase()
	{
		return $this->viewResponse->getReasonPhrase();
	}
}