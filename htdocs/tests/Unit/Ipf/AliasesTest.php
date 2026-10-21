<?php
/**
 * Verifies that every refactored Icms\Ipf\* class/interface retains its
 * legacy icms_ipf_* name so existing callers keep working.
 *
 * @package icms\tests
 */

declare(strict_types=1);

namespace Icms\Tests\Unit\Ipf;

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
            'About'                         => ['Icms\\Ipf\\About', 'icms_ipf_About'],
            'Controller'                    => ['Icms\\Ipf\\Controller', 'icms_ipf_Controller'],
            'Handler'                       => ['Icms\\Ipf\\Handler', 'icms_ipf_Handler'],
            'Highlighter'                   => ['Icms\\Ipf\\Highlighter', 'icms_ipf_Highlighter'],
            'Metagen'                       => ['Icms\\Ipf\\Metagen', 'icms_ipf_Metagen'],
            'Tree'                          => ['Icms\\Ipf\\Tree', 'icms_ipf_Tree'],
            'Entity (legacy Object)'        => ['Icms\\Ipf\\Entity', 'icms_ipf_Object'],
            'Category\\Entity'              => ['Icms\\Ipf\\Category\\Entity', 'icms_ipf_category_Object'],
            'Category\\Handler'             => ['Icms\\Ipf\\Category\\Handler', 'icms_ipf_category_Handler'],
            'Seo\\Entity'                   => ['Icms\\Ipf\\Seo\\Entity', 'icms_ipf_seo_Object'],
            'Member\\Handler'               => ['Icms\\Ipf\\Member\\Handler', 'icms_ipf_member_Handler'],
            'Permission\\Handler'           => ['Icms\\Ipf\\Permission\\Handler', 'icms_ipf_permission_Handler'],
            'Registry\\Handler'             => ['Icms\\Ipf\\Registry\\Handler', 'icms_ipf_registry_Handler'],
            'Export\\Handler'               => ['Icms\\Ipf\\Export\\Handler', 'icms_ipf_export_Handler'],
            'Export\\Renderer'              => ['Icms\\Ipf\\Export\\Renderer', 'icms_ipf_export_Renderer'],
            'View\\Column'                  => ['Icms\\Ipf\\View\\Column', 'icms_ipf_view_Column'],
            'View\\Row'                     => ['Icms\\Ipf\\View\\Row', 'icms_ipf_view_Row'],
            'View\\Single'                  => ['Icms\\Ipf\\View\\Single', 'icms_ipf_view_Single'],
            'View\\Table'                   => ['Icms\\Ipf\\View\\Table', 'icms_ipf_view_Table'],
            'View\\Tree'                    => ['Icms\\Ipf\\View\\Tree', 'icms_ipf_view_Tree'],
            'Form\\Base'                    => ['Icms\\Ipf\\Form\\Base', 'icms_ipf_form_Base'],
            'Form\\Secure'                  => ['Icms\\Ipf\\Form\\Secure', 'icms_ipf_form_Secure'],
            'Form\\Elements\\Autocomplete'  => ['Icms\\Ipf\\Form\\Elements\\Autocomplete', 'icms_ipf_form_elements_Autocomplete'],
            'Form\\Elements\\Blockoptions'  => ['Icms\\Ipf\\Form\\Elements\\Blockoptions', 'icms_ipf_form_elements_Blockoptions'],
            'Form\\Elements\\Checkbox'      => ['Icms\\Ipf\\Form\\Elements\\Checkbox', 'icms_ipf_form_elements_Checkbox'],
            'Form\\Elements\\Date'          => ['Icms\\Ipf\\Form\\Elements\\Date', 'icms_ipf_form_elements_Date'],
            'Form\\Elements\\Datetime'      => ['Icms\\Ipf\\Form\\Elements\\Datetime', 'icms_ipf_form_elements_Datetime'],
            'Form\\Elements\\File'          => ['Icms\\Ipf\\Form\\Elements\\File', 'icms_ipf_form_elements_File'],
            'Form\\Elements\\Fileupload'    => ['Icms\\Ipf\\Form\\Elements\\Fileupload', 'icms_ipf_form_elements_Fileupload'],
            'Form\\Elements\\Image'         => ['Icms\\Ipf\\Form\\Elements\\Image', 'icms_ipf_form_elements_Image'],
            'Form\\Elements\\Imageupload'   => ['Icms\\Ipf\\Form\\Elements\\Imageupload', 'icms_ipf_form_elements_Imageupload'],
            'Form\\Elements\\Language'      => ['Icms\\Ipf\\Form\\Elements\\Language', 'icms_ipf_form_elements_Language'],
            'Form\\Elements\\Page'          => ['Icms\\Ipf\\Form\\Elements\\Page', 'icms_ipf_form_elements_Page'],
            'Form\\Elements\\Parentcategory'=> ['Icms\\Ipf\\Form\\Elements\\Parentcategory', 'icms_ipf_form_elements_Parentcategory'],
            'Form\\Elements\\Passwordtray'  => ['Icms\\Ipf\\Form\\Elements\\Passwordtray', 'icms_ipf_form_elements_Passwordtray'],
            'Form\\Elements\\Radio'         => ['Icms\\Ipf\\Form\\Elements\\Radio', 'icms_ipf_form_elements_Radio'],
            'Form\\Elements\\Richfile'      => ['Icms\\Ipf\\Form\\Elements\\Richfile', 'icms_ipf_form_elements_Richfile'],
            'Form\\Elements\\Section'       => ['Icms\\Ipf\\Form\\Elements\\Section', 'icms_ipf_form_elements_Section'],
            'Form\\Elements\\Select'        => ['Icms\\Ipf\\Form\\Elements\\Select', 'icms_ipf_form_elements_Select'],
            'Form\\Elements\\Selectmulti'   => ['Icms\\Ipf\\Form\\Elements\\Selectmulti', 'icms_ipf_form_elements_Selectmulti'],
            'Form\\Elements\\Signature'     => ['Icms\\Ipf\\Form\\Elements\\Signature', 'icms_ipf_form_elements_Signature'],
            'Form\\Elements\\Source'        => ['Icms\\Ipf\\Form\\Elements\\Source', 'icms_ipf_form_elements_Source'],
            'Form\\Elements\\Text'          => ['Icms\\Ipf\\Form\\Elements\\Text', 'icms_ipf_form_elements_Text'],
            'Form\\Elements\\Time'          => ['Icms\\Ipf\\Form\\Elements\\Time', 'icms_ipf_form_elements_Time'],
            'Form\\Elements\\Upload'        => ['Icms\\Ipf\\Form\\Elements\\Upload', 'icms_ipf_form_elements_Upload'],
            'Form\\Elements\\Urllink'       => ['Icms\\Ipf\\Form\\Elements\\Urllink', 'icms_ipf_form_elements_Urllink'],
            'Form\\Elements\\User'          => ['Icms\\Ipf\\Form\\Elements\\User', 'icms_ipf_form_elements_User'],
            'Form\\Elements\\Yesno'         => ['Icms\\Ipf\\Form\\Elements\\Yesno', 'icms_ipf_form_elements_Yesno'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testLegacyAliasResolvesToModernClass(string $modern, string $legacy): void
    {
        self::assertLegacyAlias($modern, $legacy);
    }
}
