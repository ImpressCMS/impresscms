<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A form built from the metadata of an IPF entity (design D7): one field per
 * variable, with the visibility rules of Icms\Ipf\Form\Base::createElements(),
 * plus one group selection per permission of the entity's handler
 * (createPermissionControls()).
 *
 * - the key field and variables not shown on the form are hidden values; the
 *   key is not mapped, the entity is loaded by it
 * - other variables get the field ControlMap chooses, labelled with
 *   `form_caption` and described with `form_dsc`
 * - each permission is a multiple choice of all groups, named after the
 *   permission, not mapped (App\Ipf\IpfPersister stores it)
 *
 * The field names are the variable names, so with an unnamed root form
 * (IpfFormFactory) the legacy IPF form's HTML submits to it unchanged.
 * Values are mapped by IpfEntityDataMapper.
 */
final class IpfEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data'];
        if (!is_object($entity) || !isset($entity->vars, $entity->handler)) {
            throw new \InvalidArgumentException('An IpfEntityType form needs an IPF entity as its data.');
        }

        foreach (self::fields($entity) as $key => [$type, $fieldOptions]) {
            $builder->add($key, $type, $fieldOptions);
        }
        foreach (self::permissionFields($entity) as $name => $fieldOptions) {
            $builder->add($name, ChoiceType::class, $fieldOptions);
        }

        $builder->setDataMapper(new IpfEntityDataMapper());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => true,
            'empty_data' => null,
        ]);
    }

    /**
     * The entity's fields, in the order of its variables.
     *
     * @return array<string, array{class-string, array<string, mixed>}>
     */
    public static function fields(object $entity): array
    {
        $fields = [];
        foreach ($entity->vars as $key => $var) {
            $key = (string) $key;
            if ($key === $entity->handler->keyName || !$var['displayOnForm']) {
                $fields[$key] = [HiddenType::class, ['mapped' => $key !== $entity->handler->keyName]];
                continue;
            }
            // XOBJ_DTYPE_ARRAY without a control has no element in the legacy form either
            if ((int) $var['data_type'] === XOBJ_DTYPE_ARRAY && !isset($entity->controls[$key])) {
                continue;
            }
            $fields[$key] = ControlMap::field($entity, $key, $var, $entity->controls[$key] ?? null);
        }

        return $fields;
    }

    /**
     * The group selections for the handler's permissions, with the groups that
     * hold each permission now (for a new entity: the module's default).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function permissionFields(object $entity): array
    {
        $permissions = $entity->handler->getPermissions();
        if (!$permissions) {
            return [];
        }

        $groups = \icms::handler('icms_member')->getGroupList();
        asort($groups);
        $moduleConfig = $entity->isNew() ? $entity->handler->getModuleConfig() : [];

        $fields = [];
        foreach ($permissions as $permission) {
            $name = (string) $permission['perm_name'];
            $selected = $entity->isNew()
                ? ($moduleConfig['def_perm_' . $name] ?? false)
                : $entity->getGroupPerm($name);
            $caption = ControlMap::caption($name, ['form_caption' => $permission['caption']]);

            $fields[$name] = [
                'label' => $caption,
                'help' => ((string) ($permission['description'] ?? '')) ?: null,
                'choices' => array_keys($groups),
                'choice_label' => static fn (int|string $id): string => (string) $groups[$id],
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'data' => array_values(array_map('intval', (array) ($selected ?: []))),
                'invalid_message' => $caption . ': the selected choice is invalid.',
            ];
        }

        return $fields;
    }
}
