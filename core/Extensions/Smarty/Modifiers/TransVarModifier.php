<?php

namespace ImpressCMS\Core\Extensions\Smarty\Modifiers;

use ImpressCMS\Core\Extensions\Smarty\SmartyModifierExtensionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trans var modifier (aka filter) similar as twig trans function
 *
 * @package ImpressCMS\Core\Extensions\Smarty\Modifiers
 */
class TransVarModifier implements SmartyModifierExtensionInterface
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * TransVarModifier constructor.
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	/**
	 * Executes modifier
	 *
	 * @param string $message Message or id string to translate
	 * @param array $parameters Translation parameters
	 * @param string|null $domain Translation domain
	 * @param string|null $locale Locale
	 *
	 * @return string
	 */
	public function execute($message, array $parameters = [], ?string $domain = null, ?string $locale = null) {
		return $this->translator->trans($message, $parameters, $domain, $locale);
	}

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'trans';
    }
}