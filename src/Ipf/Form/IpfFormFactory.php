<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Forms for IPF entities (design D7). The root form has no name, so its
 * fields are the top-level request keys of the legacy IPF form (`extension`,
 * `dirname[]`, the permission names). Other keys of that form (`op`, `fct`,
 * `icms_page_before_form`, the ImpressCMS token) are allowed and ignored; the
 * ImpressCMS token is checked by the page, so Symfony's CSRF field is off.
 */
final class IpfFormFactory
{
    public function __construct(private readonly FormFactoryInterface $forms)
    {
    }

    public function create(object $entity): FormInterface
    {
        $options = ['allow_extra_fields' => true, 'data_class' => $entity::class];
        $builder = $this->forms->createNamedBuilder('', IpfEntityType::class, $entity, $options + self::withoutCsrf($this->forms));

        return $builder->getForm();
    }

    /**
     * Bind a request the way the legacy controller reads $_POST: fields that were
     * not sent keep their value.
     */
    public function submit(FormInterface $form, Request $request): FormInterface
    {
        $form->submit($request->request->all(), false);

        return $form;
    }

    /**
     * @return array<string, false>
     */
    private static function withoutCsrf(FormFactoryInterface $forms): array
    {
        $options = $forms->createBuilder()->getFormConfig()->getOptions();

        return array_key_exists('csrf_protection', $options) ? ['csrf_protection' => false] : [];
    }
}
