<?php
/**
 * Verifies that every refactored Icms\Feeds\* class retains its legacy
 * icms_feeds_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Form;

use Icms\Tests\Support\LegacyAliasAssertions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AliasesTest extends TestCase
{
    use LegacyAliasAssertions;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function aliasProvider(): array
    {
        return [
            'Form Base'       => ['Icms\\Form\\Base', 'icms_form_Base'],
            'Form Element'       => ['Icms\\Form\\Element', 'icms_form_Element'],
            'Form Group Permission'       => ['Icms\\Form\\GroupPermission', 'icms_form_GroupPermission'],
            'Simple Form'       => ['Icms\\Form\\Simple', 'icms_form_Simple'],
            'Table Form'       => ['Icms\\Form\\Table', 'icms_form_Table'],
            'Theme Form'       => ['Icms\\Form\\Theme', 'icms_form_Theme'],
            'Button Form Element'       => ['Icms\\Form\\Elements\\Button', 'icms_form_elements_Button'],
            'Captcha Form Element'       => ['Icms\\Form\\Elements\\Captcha', 'icms_form_elements_Captcha'],
            'Checkbox Form Element'       => ['Icms\\Form\\Elements\\Checkbox', 'icms_form_elements_Checkbox'],
            'Colorpicker Form Element'       => ['Icms\\Form\\Elements\\Colorpicker', 'icms_form_elements_Colorpicker'],
            'Date Form Element'       => ['Icms\\Form\\Elements\\Date', 'icms_form_elements_Date'],
            'Datetime Form Element'       => ['Icms\\Form\\Elements\\Datetime', 'icms_form_elements_Datetime'],
            'Dhtmltextarea Form Element'       => ['Icms\\Form\\Elements\\Dhtmltextarea', 'icms_form_elements_Dhtmltextarea'],
            'Textarea Form Element'       => ['Icms\\Form\\Elements\\Textarea', 'icms_form_elements_Textarea'],
            'File Form Element'       => ['Icms\\Form\\Elements\\File', 'icms_form_elements_File'],
            'GroupPerm Form Element'       => ['Icms\\Form\\Elements\\Groupperm', 'icms_form_elements_Groupperm'],
            'Hidden Form Element'       => ['Icms\\Form\\Elements\\Hidden', 'icms_form_elements_Hidden'],
            'HiddenToken Form Element'       => ['Icms\\Form\\Elements\\HiddenToken', 'icms_form_elements_Hiddentoken'],
            'Label Form Element'       => ['Icms\\Form\\Elements\\Label', 'icms_form_elements_Label'],
            'Password Form Element'       => ['Icms\\Form\\Elements\\Password', 'icms_form_elements_Password'],
            'Radio Form Element'       => ['Icms\\Form\\Elements\\Radio', 'icms_form_elements_Radio'],
            'Radio Y/N Form Element'       => ['Icms\\Form\\Elements\\Radioyn', 'icms_form_elements_Radioyn'],
            'Select Form Element'       => ['Icms\\Form\\Elements\\Select', 'icms_form_elements_Select'],
            'Text Form Element'       => ['Icms\\Form\\Elements\\Text', 'icms_form_elements_Text'],
            'Tray Form Element'       => ['Icms\\Form\\Elements\\Tray', 'icms_form_elements_Tray'],


        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
