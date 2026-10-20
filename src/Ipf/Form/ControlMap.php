<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Maps an IPF variable to a Symfony form field (design D7), following the
 * choices Icms\Ipf\Form\Base::createElements() makes for the legacy form:
 *
 * | IPF                                    | field                 | constraints        |
 * |----------------------------------------|-----------------------|--------------------|
 * | XOBJ_DTYPE_TXTBOX, control `text`      | TextType              | Length(maxlength)  |
 * | XOBJ_DTYPE_TXTAREA, control `textarea` | TextareaType          | –                  |
 * | XOBJ_DTYPE_INT                         | IntegerType           | an integer         |
 * | control `select`, `selectmulti`        | ChoiceType (multiple) | one of the choices |
 * | required variable                      | –                     | NotBlank           |
 *
 * Messages name the field by its caption, in the words of the legacy
 * validation (_XOBJ_ERR_REQUIRED, _XOBJ_ERR_SHORTERTHAN). Any other control or
 * data type raises UnsupportedIpfControlException.
 */
final class ControlMap
{
    /**
     * The field type and options for a variable shown on the form.
     *
     * @param array<string, mixed>      $var     the variable definition (Entity::$vars[$key])
     * @param array<string, mixed>|null $control the variable's control, if any
     *
     * @return array{class-string, array<string, mixed>}
     */
    public static function field(object $entity, string $key, array $var, ?array $control): array
    {
        $caption = self::caption($key, $var);
        $dataType = (int) $var['data_type'];

        if ($control !== null) {
            $name = (string) (($control['name'] ?? '') ?: 'select');
            [$type, $options] = match ($name) {
                // the legacy form gives integers a text control
                'text' => $dataType === XOBJ_DTYPE_INT ? self::integer($caption) : self::text($var, $caption),
                'textarea' => [TextareaType::class, []],
                'select' => self::choice($entity, $control, $caption, false),
                'selectmulti' => self::choice($entity, $control, $caption, true),
                default => throw new UnsupportedIpfControlException($key, $name),
            };
        } else {
            [$type, $options] = match ($dataType) {
                XOBJ_DTYPE_TXTBOX => self::text($var, $caption),
                XOBJ_DTYPE_TXTAREA => [TextareaType::class, []],
                XOBJ_DTYPE_INT => self::integer($caption),
                default => throw new UnsupportedIpfControlException($key, self::dataTypeName($dataType)),
            };
        }

        $constraints = $options['constraints'] ?? [];
        if ($var['required']) {
            $constraints[] = new NotBlank(message: self::message(_XOBJ_ERR_REQUIRED, $caption));
        }

        return [$type, [
            'label' => $caption,
            'help' => ((string) ($var['form_dsc'] ?? '')) ?: null,
            'required' => (bool) $var['required'],
            'disabled' => !empty($var['readonly']),
        ] + ['constraints' => $constraints] + $options];
    }

    /**
     * The options of a select control, in the order the legacy select list
     * shows them (Icms\Ipf\Form\Elements\Select).
     *
     * @param array<string, mixed> $control
     *
     * @return array<int|string, string> value => label
     */
    public static function options(object $entity, array $control): array
    {
        if (isset($control['options'])) {
            return (array) $control['options'];
        }
        if (isset($control['object'])) {
            $options = method_exists($control['object'], $control['method']) ? $control['object']->{$control['method']}() : [];

            return is_array($options) ? $options : [];
        }

        if (isset($control['itemHandler'])) {
            $handler = isset($control['module'])
                ? icms_getModuleHandler($control['itemHandler'], $control['module'])
                : \icms::handler($control['itemHandler']);
        } else {
            $handler = $entity->handler;
        }
        if (!isset($control['method']) || !method_exists($handler, $control['method'])) {
            return [];
        }
        $options = call_user_func_array([$handler, $control['method']], $control['params'] ?? []);

        return is_array($options) ? $options : [];
    }

    /**
     * The caption the legacy form shows for a variable, as plain text.
     *
     * @param array<string, mixed> $var
     */
    public static function caption(string $key, array $var): string
    {
        $caption = trim(html_entity_decode(strip_tags((string) ($var['form_caption'] ?? '')), ENT_QUOTES, 'UTF-8'));

        return $caption !== '' ? $caption : $key;
    }

    /**
     * A message in the legacy wording, naming the caption.
     */
    public static function message(string $format, string $caption, int ...$arguments): string
    {
        // the validator fills {{ placeholders }}; the caption is final text
        return str_replace('{{', '{ {', sprintf($format, $caption, ...$arguments));
    }

    /**
     * @param array<string, mixed> $var
     *
     * @return array{class-string, array<string, mixed>}
     */
    private static function text(array $var, string $caption): array
    {
        $constraints = [];
        if (isset($var['maxlength']) && (int) $var['maxlength'] > 0) {
            $constraints[] = new Length(max: (int) $var['maxlength'], maxMessage: self::message(_XOBJ_ERR_SHORTERTHAN, $caption, (int) $var['maxlength']));
        }

        // legacy trims when it validates, but stores what was posted
        return [TextType::class, ['constraints' => $constraints, 'trim' => false]];
    }

    /**
     * @return array{class-string, array<string, mixed>}
     */
    private static function integer(string $caption): array
    {
        return [IntegerType::class, ['invalid_message' => $caption . ': please enter a whole number.']];
    }

    /**
     * @param array<string, mixed> $control
     *
     * @return array{class-string, array<string, mixed>}
     */
    private static function choice(object $entity, array $control, string $caption, bool $multiple): array
    {
        $labels = self::options($entity, $control);

        return [ChoiceType::class, [
            'choices' => array_keys($labels),
            'choice_label' => static fn (int|string $value): string => (string) $labels[$value],
            'multiple' => $multiple,
            'expanded' => false,
            // ChoiceType rejects values outside the choices with this message
            'invalid_message' => $caption . ': the selected choice is invalid.',
        ]];
    }

    private static function dataTypeName(int $type): string
    {
        foreach (get_defined_constants() as $name => $value) {
            if (str_starts_with((string) $name, 'XOBJ_DTYPE_') && $value === $type) {
                return $name;
            }
        }

        return 'data type ' . $type;
    }
}
