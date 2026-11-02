<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Support\IsolatedLegacyTestCase;
use App\Tests\Support\TestSite;
use Icms\Kernel\Bootstrap;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The admin dashboard's warnings about the trust path and pending host files
 * (b_system_admin_warnings_show()).
 */
#[RunTestsInSeparateProcesses]
#[Group('spec:host-deployment')]
final class AdminTrustPathWarningTest extends IsolatedLegacyTestCase
{
    /**
     * @return list<string>
     */
    private function warnings(): array
    {
        (new Bootstrap(Bootstrap::MODE_HEADLESS, TestSite::legacyRoot() . '/mainfile.php'))->runAll();
        require_once ICMS_ROOT_PATH . '/include/cp_functions.php';
        require_once ICMS_ROOT_PATH . '/modules/system/blocks/system_admin_blocks.php';

        // the block echoes a <br /> when Protector is not active
        ob_start();
        $block = b_system_admin_warnings_show();
        ob_end_clean();

        return array_map('strval', $block['msg']);
    }

    /**
     * @scenario Warning for a public trust path
     */
    public function testATrustPathUnderTheDocumentRootIsReported(): void
    {
        // the test site's trust path is var/test/trust: under this document root
        $_SERVER['DOCUMENT_ROOT'] = TestSite::varDir();

        $warnings = implode("\n", $this->warnings());

        self::assertStringContainsString(TestSite::trustPath(), $warnings);
        self::assertStringContainsString('XOOPS_TRUST_PATH in mainfile.php', $warnings);
    }

    public function testATrustPathOutsideThePublicDirectoriesIsNotReported(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = TestSite::legacyRoot();

        self::assertStringNotContainsString(TestSite::trustPath(), implode("\n", $this->warnings()));
    }

    public function testHostFilesWaitingInTheStagingDirectoryAreReported(): void
    {
        $staging = TestSite::legacyRoot() . '/trust_dist';
        if (is_dir($staging)) {
            self::markTestSkipped('htdocs/trust_dist exists (bin/build-dist); not touching it.');
        }
        $_SERVER['DOCUMENT_ROOT'] = TestSite::legacyRoot();

        (new Filesystem())->mkdir($staging);
        try {
            $warnings = implode("\n", $this->warnings());
        } finally {
            (new Filesystem())->remove($staging);
        }

        self::assertStringContainsString('op=update&amp;module=system', $warnings);
        self::assertStringContainsString($staging, $warnings);
    }
}
