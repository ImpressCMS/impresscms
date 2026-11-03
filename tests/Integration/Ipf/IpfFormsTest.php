<?php

declare(strict_types=1);

namespace App\Tests\Integration\Ipf;

use App\Ipf\Form\IpfForm;
use App\Ipf\Form\IpfFormFactory;
use App\Ipf\Form\UnsupportedIpfControlException;
use App\Ipf\IpfPersister;
use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyAppTestCase;
use App\Tests\Support\TestSite;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Metadata-driven forms and the persister against the real mimetype entity
 * and its legacy IPF form (design L31, L32).
 */
#[RunTestsInSeparateProcesses]
#[Group('spec:ipf-forms')]
final class IpfFormsTest extends LegacyAppTestCase
{
    private const ALPHA = 124;

    protected function setUp(): void
    {
        parent::setUp();
        // the legacy database layer only writes during a same-site POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_REFERER'] = 'http://127.0.0.1/';
        $this->app->make(LegacyRuntime::class)->bootTo('online');
    }

    /**
     * @scenario Mimetype entity
     */
    public function testFieldsAreThoseOfTheLegacyForm(): void
    {
        $mimetype = $this->mimetypes()->get(self::ALPHA);
        $form = $this->forms()->for($mimetype);

        $legacy = [];
        foreach ($mimetype->getForm('caption', 'addmimetype')->getElements(true) as $element) {
            $legacy[$element->getName(false)] = $element;
        }

        // the page before the form is navigation, not entity data
        unset($legacy['icms_page_before_form']);

        self::assertSame(array_keys($legacy), array_keys($form->fields()));
        self::assertTrue($form->field('mimetypeid')->hidden);
        foreach (['extension', 'types', 'name', 'dirname', 'use_extension'] as $name) {
            $field = $form->field($name);
            self::assertFalse($field->hidden, $name);
            self::assertSame(strip_tags($legacy[$name]->getCaption()), $field->caption, $name);
            self::assertSame($legacy[$name]->getDescription() ?: null, $field->description, $name);
        }

        self::assertSame([1, 2], $form->field('use_extension')->value);
        self::assertSame(['system'], $form->field('dirname')->value);
    }

    /**
     * @scenario Select-multi control with an item handler
     */
    public function testSelectMultiOffersTheLegacyOptionsInOrder(): void
    {
        $mimetype = $this->mimetypes()->get(self::ALPHA);
        $field = $this->forms()->for($mimetype)->field('dirname');

        $active = \icms::handler('icms_module')->getActive();
        self::assertNotEmpty($active);
        self::assertSame(array_map('strval', array_keys($active)), array_map('strval', array_keys((array) $field->options)));
        self::assertTrue($field->multiple);

        $legacy = null;
        foreach ($mimetype->getForm('caption', 'addmimetype')->getElements(true) as $element) {
            if ($element->getName(false) === 'dirname') {
                $legacy = $element;
            }
        }

        self::assertNotNull($legacy);
        self::assertSame($legacy->getOptions(), $field->options);
    }

    /**
     * @scenario Legacy form posted to the framework form
     */
    public function testLegacyFormHtmlBindsEveryValue(): void
    {
        $mimetype = $this->mimetypes()->get(self::ALPHA);
        $html = '<html><body>' . $mimetype->getForm('caption', 'addmimetype')->render() . '</body></html>';
        $legacyForm = (new Crawler($html, 'http://127.0.0.1/modules/system/admin.php'))->filterXPath('//form[@id="addmimetype"]')->form();
        $legacyForm['extension'] = 'icmsz';
        $legacyForm['name'] = 'Renamed';
        $legacyForm['types'] = 'application/x-icms-zeta';
        $legacyForm['use_extension']->select(['1', '3']);

        $form = $this->forms()->for($mimetype)->submit(Request::create('/', 'POST', $legacyForm->getPhpValues()));

        self::assertFalse($form->fails(), implode("\n", $form->errors()));
        self::assertSame('icmsz', $mimetype->getVar('extension', 'n'));
        self::assertSame('Renamed', $mimetype->getVar('name', 'n'));
        self::assertSame('application/x-icms-zeta', $mimetype->getVar('types', 'n'));
        self::assertSame(['system'], $mimetype->vars['dirname']['value']);
        self::assertSame([1, 3], $form->permissionGroups('use_extension'));
        self::assertSame(self::ALPHA, (int) $mimetype->getVar('mimetypeid'));
    }

    /**
     * @scenario Required field left empty
     */
    public function testViolationsNameTheFieldCaption(): void
    {
        $form = $this->submit($this->mimetypes()->get(self::ALPHA), ['extension' => '']);
        self::assertTrue($form->fails());
        self::assertContains(sprintf(_XOBJ_ERR_REQUIRED, _CO_ICMS_MIMETYPE_EXTENSION), $form->errors());

        $mimetype = $this->mimetypes()->get(self::ALPHA);
        $mimetype->vars['name']['maxlength'] = 5;
        $form = $this->submit($mimetype, ['name' => 'Too long a name']);
        self::assertTrue($form->fails());
        self::assertContains(sprintf(_XOBJ_ERR_SHORTERTHAN, _CO_ICMS_MIMETYPE_NAME, 5), $form->errors());

        $form = $this->submit($this->mimetypes()->get(self::ALPHA), ['dirname' => ['no-such-module']]);
        self::assertTrue($form->fails());
        self::assertStringContainsString(_CO_ICMS_MIMETYPE_DIRNAME, implode("\n", $form->errors()));

        $errors = $this->persister()->save($this->submit($this->mimetypes()->get(self::ALPHA), ['extension' => '']));
        self::assertSame([sprintf(_XOBJ_ERR_REQUIRED, _CO_ICMS_MIMETYPE_EXTENSION)], $errors);
        self::assertSame(['extension' => 'icmsa'], self::row(self::ALPHA, ['extension']), 'an invalid submission is not stored');
    }

    /**
     * @scenario Upload control
     */
    public function testUnsupportedControlIsRejected(): void
    {
        $mimetype = $this->mimetypes()->create();
        $mimetype->setControl('types', 'upload');

        try {
            $this->forms()->for($mimetype);
            self::fail('A form with an upload control was built.');
        } catch (UnsupportedIpfControlException $e) {
            self::assertSame('types', $e->variable);
            self::assertSame('upload', $e->control);
            self::assertStringContainsString('"types"', $e->getMessage());
            self::assertStringContainsString('"upload"', $e->getMessage());
        }
    }

    /**
     * @scenario Storing a new mimetype
     * @scenario Mimetype upload permission
     */
    public function testSaveRunsTheHandlerEventsAndReplacesThePermissions(): void
    {
        $handler = $this->recordingHandler();
        $mimetype = $handler->create();
        $form = $this->submit($mimetype, [
            'extension' => 'icmsd',
            'types' => 'application/x-icms-delta',
            'name' => 'ICMS Fixture Delta',
            'dirname' => ['system'],
            'use_extension' => ['1', '2'],
        ]);

        self::assertSame([], $this->persister()->save($form));
        // in the order the legacy handler runs them
        self::assertSame(['beforeSave', 'beforeInsert', 'afterSave', 'afterInsert'], $handler->events);
        $id = (int) $mimetype->id();
        self::assertGreaterThan(0, $id);
        self::assertSame(['extension' => 'icmsd', 'name' => 'ICMS Fixture Delta', 'dirname' => 'system'], self::row($id, ['extension', 'name', 'dirname']));
        self::assertSame([1, 2], self::useExtensionGroups($id));

        $form = $this->submit($handler->get($id), ['use_extension' => ['2', '3']]);
        self::assertSame([], $this->persister()->save($form));
        self::assertSame([2, 3], self::useExtensionGroups($id), 'the bound groups replace the previous ones');

        // a selection the browser did not send means no groups, as in legacy
        $form = $this->submit($handler->get($id), ['name' => 'ICMS Fixture Delta 2']);
        self::assertSame([], $this->persister()->save($form));
        self::assertSame([], self::useExtensionGroups($id));
    }

    public function testDeleteRemovesTheMimetypeAndItsPermissions(): void
    {
        self::assertSame([1, 2], self::useExtensionGroups(self::ALPHA));

        self::assertSame([], $this->persister()->delete($this->mimetypes()->get(self::ALPHA)));

        self::assertTrue($this->mimetypes()->get(self::ALPHA)->isNew());
        self::assertSame([], self::useExtensionGroups(self::ALPHA));
    }

    private function mimetypes(): object
    {
        return $this->app->make('icms.handlers')->get('module:system/mimetype');
    }

    private function forms(): IpfFormFactory
    {
        return $this->app->make(IpfFormFactory::class);
    }

    private function persister(): IpfPersister
    {
        return $this->app->make(IpfPersister::class);
    }

    /**
     * @param array<string, mixed> $values
     */
    private function submit(object $mimetype, array $values): IpfForm
    {
        return $this->forms()->for($mimetype)->submit(Request::create('/', 'POST', $values));
    }

    /**
     * A mimetype handler that records the events it runs.
     */
    private function recordingHandler(): object
    {
        $this->mimetypes();

        return new class (\icms::$xoopsDB) extends \SystemMimetypeHandler {
            /** @var list<string> */
            public array $events = [];

            public function beforeSave(object $obj): bool
            {
                return $this->record(__FUNCTION__);
            }

            public function beforeInsert(object $obj): bool
            {
                return $this->record(__FUNCTION__);
            }

            public function afterInsert(object $obj): bool
            {
                return $this->record(__FUNCTION__);
            }

            public function afterSave(object $obj): bool
            {
                return $this->record(__FUNCTION__);
            }

            private function record(string $event): bool
            {
                $this->events[] = $event;

                return true;
            }
        };
    }

    /**
     * @param list<string> $columns
     * @return array<string, string>
     */
    private static function row(int $id, array $columns): array
    {
        $stmt = TestSite::connection()->prepare('SELECT ' . implode(', ', $columns) . ' FROM ' . TestSite::table('system_mimetype') . ' WHERE mimetypeid = ?');
        $stmt->execute([$id]);

        return array_map('strval', (array) $stmt->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * @return list<int>
     */
    private static function useExtensionGroups(int $id): array
    {
        $stmt = TestSite::connection()->prepare(
            'SELECT gperm_groupid FROM ' . TestSite::table('group_permission')
            . " WHERE gperm_name = 'use_extension' AND gperm_modid = 1 AND gperm_itemid = ? ORDER BY gperm_groupid",
        );
        $stmt->execute([$id]);

        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }
}
