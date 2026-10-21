<?php

declare(strict_types=1);

namespace App\Tests\Integration\Ipf;

use App\Ipf\Form\IpfFormFactory;
use App\Ipf\Form\UnsupportedIpfControlException;
use App\Ipf\IpfPersister;
use App\Legacy\LegacyRuntime;
use App\Tests\Support\LegacyWebTestCase;
use App\Tests\Support\TestSite;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Metadata-driven forms and the persister against the real mimetype entity
 * and its legacy IPF form (design D7, D8).
 */
#[RunTestsInSeparateProcesses]
#[Group('spec:ipf-forms')]
final class IpfFormsTest extends LegacyWebTestCase
{
    private const ALPHA = 124;

    protected function setUp(): void
    {
        parent::setUp();
        // the legacy database layer only writes during a same-site POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_REFERER'] = 'http://127.0.0.1/';
        self::bootKernel();
        static::getContainer()->get(LegacyRuntime::class)->bootTo('online');
    }

    /**
     * @scenario Mimetype entity
     */
    public function testFieldsAreThoseOfTheLegacyForm(): void
    {
        $mimetype = self::mimetypes()->get(self::ALPHA);
        $form = self::forms()->create($mimetype);

        $legacy = [];
        foreach ($mimetype->getForm('caption', 'addmimetype')->getElements(true) as $element) {
            $legacy[$element->getName(false)] = $element;
        }
        // the page before the form is navigation, not entity data
        unset($legacy['icms_page_before_form']);

        self::assertSame(array_keys($legacy), array_keys($form->all()));
        self::assertSame(HiddenType::class, self::type($form->get('mimetypeid')));
        foreach (['extension', 'types', 'name', 'dirname', 'use_extension'] as $field) {
            self::assertNotSame(HiddenType::class, self::type($form->get($field)), $field);
            self::assertSame(strip_tags($legacy[$field]->getCaption()), $form->get($field)->getConfig()->getOption('label'), $field);
            self::assertSame($legacy[$field]->getDescription() ?: null, $form->get($field)->getConfig()->getOption('help'), $field);
        }
        self::assertSame([1, 2], $form->get('use_extension')->getData());
        self::assertSame(['system'], $form->get('dirname')->getData());
    }

    /**
     * @scenario Select-multi control with an item handler
     */
    public function testSelectMultiOffersTheLegacyOptionsInOrder(): void
    {
        $mimetype = self::mimetypes()->get(self::ALPHA);
        $field = self::forms()->create($mimetype)->get('dirname');

        $active = \icms::handler('icms_module')->getActive();
        self::assertNotEmpty($active);
        $choices = $field->getConfig()->getOption('choices');
        self::assertSame(array_map('strval', array_keys($active)), array_map('strval', $choices));
        self::assertTrue($field->getConfig()->getOption('multiple'));

        $legacy = null;
        foreach ($mimetype->getForm('caption', 'addmimetype')->getElements(true) as $element) {
            if ($element->getName(false) === 'dirname') {
                $legacy = $element;
            }
        }
        self::assertNotNull($legacy);
        self::assertSame(array_map('strval', array_keys($legacy->getOptions())), array_map('strval', $choices));
        $label = $field->getConfig()->getOption('choice_label');
        self::assertSame(array_values($legacy->getOptions()), array_map($label, $choices));
    }

    /**
     * @scenario Legacy form posted to the framework form
     */
    public function testLegacyFormHtmlBindsEveryValue(): void
    {
        $mimetype = self::mimetypes()->get(self::ALPHA);
        $html = '<html><body>' . $mimetype->getForm('caption', 'addmimetype')->render() . '</body></html>';
        $legacyForm = (new Crawler($html, 'http://127.0.0.1/modules/system/admin.php'))->filterXPath('//form[@id="addmimetype"]')->form();
        $legacyForm['extension'] = 'icmsz';
        $legacyForm['name'] = 'Renamed';
        $legacyForm['types'] = 'application/x-icms-zeta';
        $legacyForm['use_extension']->select(['1', '3']);
        $values = $legacyForm->getPhpValues();

        $form = self::forms()->submit(self::forms()->create($mimetype), Request::create('/', 'POST', $values));

        self::assertTrue($form->isValid(), (string) $form->getErrors(true));
        self::assertSame('icmsz', $mimetype->getVar('extension', 'n'));
        self::assertSame('Renamed', $mimetype->getVar('name', 'n'));
        self::assertSame('application/x-icms-zeta', $mimetype->getVar('types', 'n'));
        self::assertSame(['system'], $mimetype->vars['dirname']['value']);
        self::assertSame([1, 3], $form->get('use_extension')->getData());
        self::assertSame(self::ALPHA, (int) $mimetype->getVar('mimetypeid'));
    }

    /**
     * @scenario Required field left empty
     */
    public function testViolationsNameTheFieldCaption(): void
    {
        $mimetype = self::mimetypes()->get(self::ALPHA);
        $form = self::submit($mimetype, ['extension' => '']);
        self::assertFalse($form->isValid());
        self::assertStringContainsString(sprintf(_XOBJ_ERR_REQUIRED, _CO_ICMS_MIMETYPE_EXTENSION), (string) $form->getErrors(true));

        $mimetype = self::mimetypes()->get(self::ALPHA);
        $mimetype->vars['name']['maxlength'] = 5;
        $form = self::submit($mimetype, ['name' => 'Too long a name']);
        self::assertFalse($form->isValid());
        self::assertStringContainsString(sprintf(_XOBJ_ERR_SHORTERTHAN, _CO_ICMS_MIMETYPE_NAME, 5), (string) $form->getErrors(true));

        $mimetype = self::mimetypes()->get(self::ALPHA);
        $form = self::submit($mimetype, ['dirname' => ['no-such-module']]);
        self::assertFalse($form->isValid());
        self::assertStringContainsString(_CO_ICMS_MIMETYPE_DIRNAME, (string) $form->getErrors(true));

        $errors = self::persister()->save(self::submit(self::mimetypes()->get(self::ALPHA), ['extension' => '']));
        self::assertSame([sprintf(_XOBJ_ERR_REQUIRED, _CO_ICMS_MIMETYPE_EXTENSION)], $errors);
        self::assertSame(['extension' => 'icmsa'], self::row(self::ALPHA, ['extension']), 'an invalid submission is not stored');
    }

    /**
     * @scenario Upload control
     */
    public function testUnsupportedControlIsRejected(): void
    {
        $mimetype = self::mimetypes()->create();
        $mimetype->setControl('types', 'upload');

        try {
            self::forms()->create($mimetype);
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
        $handler = self::recordingHandler();
        $mimetype = $handler->create();
        $form = self::submit($mimetype, [
            'extension' => 'icmsd',
            'types' => 'application/x-icms-delta',
            'name' => 'ICMS Fixture Delta',
            'dirname' => ['system'],
            'use_extension' => ['1', '2'],
        ]);

        self::assertSame([], self::persister()->save($form));
        // in the order the legacy handler runs them
        self::assertSame(['beforeSave', 'beforeInsert', 'afterSave', 'afterInsert'], $handler->events);
        $id = (int) $mimetype->id();
        self::assertGreaterThan(0, $id);
        self::assertSame(['extension' => 'icmsd', 'name' => 'ICMS Fixture Delta', 'dirname' => 'system'], self::row($id, ['extension', 'name', 'dirname']));
        self::assertSame([1, 2], self::useExtensionGroups($id));

        $form = self::submit($handler->get($id), ['use_extension' => ['2', '3']]);
        self::assertSame([], self::persister()->save($form));
        self::assertSame([2, 3], self::useExtensionGroups($id), 'the bound groups replace the previous ones');

        // a selection the browser did not send means no groups, as in legacy
        $form = self::submit($handler->get($id), ['name' => 'ICMS Fixture Delta 2']);
        self::assertSame([], self::persister()->save($form));
        self::assertSame([], self::useExtensionGroups($id));
    }

    public function testDeleteRemovesTheMimetypeAndItsPermissions(): void
    {
        self::assertSame([1, 2], self::useExtensionGroups(self::ALPHA));

        self::assertSame([], self::persister()->delete(self::mimetypes()->get(self::ALPHA)));

        self::assertTrue(self::mimetypes()->get(self::ALPHA)->isNew());
        self::assertSame([], self::useExtensionGroups(self::ALPHA));
    }

    // -- Helpers ------------------------------------------------------------------

    private static function mimetypes(): object
    {
        return static::getContainer()->get('icms.module_handlers')->get('system', 'mimetype');
    }

    private static function forms(): IpfFormFactory
    {
        return static::getContainer()->get(IpfFormFactory::class);
    }

    private static function persister(): IpfPersister
    {
        return static::getContainer()->get(IpfPersister::class);
    }

    /**
     * @param array<string, mixed> $values
     */
    private static function submit(object $mimetype, array $values): FormInterface
    {
        return self::forms()->submit(self::forms()->create($mimetype), Request::create('/', 'POST', $values));
    }

    private static function type(FormInterface $field): string
    {
        return $field->getConfig()->getType()->getInnerType()::class;
    }

    /**
     * A mimetype handler that records the events it runs.
     */
    private static function recordingHandler(): object
    {
        self::mimetypes();

        return new class (\icms::$xoopsDB) extends \SystemMimetypeHandler {
            /** @var list<string> */
            public array $events = [];

            public function beforeSave(object $obj): bool { return $this->record(__FUNCTION__); }
            public function beforeInsert(object $obj): bool { return $this->record(__FUNCTION__); }
            public function afterInsert(object $obj): bool { return $this->record(__FUNCTION__); }
            public function afterSave(object $obj): bool { return $this->record(__FUNCTION__); }

            private function record(string $event): bool
            {
                $this->events[] = $event;

                return true;
            }
        };
    }

    /**
     * @param list<string> $columns
     *
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
