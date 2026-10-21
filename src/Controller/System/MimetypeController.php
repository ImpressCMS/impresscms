<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Admin\AdminPageRenderer;
use App\Admin\Attribute\SystemAdminFunction;
use App\Admin\LegacyRedirect;
use App\Ipf\Form\IpfFormFactory;
use App\Ipf\IpfPersister;
use App\Legacy\Attribute\IcmsModuleHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The mimetype administration of the system module,
 * `modules/system/admin.php?fct=mimetype` (design D1, D2, D9), in place of
 * modules/system/admin/mimetype/main.php.
 *
 * The page logic is here: operations, validation, storage, token checks and
 * redirects. The output is still legacy: the content template, the IPF table
 * and form, and the admin theme. Saving and deleting need an ImpressCMS
 * security token, which legacy did not check.
 *
 * Switch it off with ICMS_TAKEOVERS_DISABLED=system.mimetype, which serves the
 * legacy page again.
 */
#[SystemAdminFunction('mimetype')]
final class MimetypeController
{
    public const ROUTE = 'system_mimetype';
    public const TAKEOVER = 'system.mimetype';

    private const TEMPLATE = 'db:system_adm_mimetype.html';

    public function __construct(
        private readonly AdminPageRenderer $renderer,
        private readonly LegacyRedirect $redirect,
        private readonly IpfFormFactory $forms,
        private readonly IpfPersister $persister,
    ) {
    }

    /**
     * admin.php takes `fct` from the query string, overridden by the POST body
     * (the delete confirmation posts it without a query string).
     */
    #[Route(
        '/modules/system/admin.php',
        name: self::ROUTE,
        methods: ['GET', 'POST'],
        condition: "(request.request.has('fct') ? request.request.get('fct') : request.query.get('fct')) == 'mimetype' and service('icms_takeovers').enabled('system.mimetype')",
    )]
    public function __invoke(Request $request, #[IcmsModuleHandler('system', 'mimetype')] object $mimetypes): Response
    {
        // main.php: `op` and `mimetypeid` from the query string, overridden by the POST body
        $op = (string) $request->request->get('op', (string) $request->query->get('op', ''));

        return match ($op) {
            'mod' => $this->edit($mimetypes, (int) $request->request->get('mimetypeid', (int) $request->query->get('mimetypeid', 0))),
            'addmimetype' => $this->save($mimetypes, $request),
            'del' => $this->delete($mimetypes, $request),
            default => $this->list($mimetypes),
        };
    }

    private function list(object $mimetypes): Response
    {
        return $this->renderer->render(self::TEMPLATE, static function (object $template) use ($mimetypes): void {
            $table = new \Icms\Ipf\View\Table($mimetypes);
            $table->addColumn(new \Icms\Ipf\View\Column('name', _GLOBAL_LEFT, 150));
            $table->addColumn(new \Icms\Ipf\View\Column('extension', _GLOBAL_LEFT, 150));
            $table->addColumn(new \Icms\Ipf\View\Column('types', _GLOBAL_LEFT));
            $table->addIntroButton('addmimetype', 'admin.php?fct=mimetype&amp;op=mod', _CO_ICMS_MIMETYPE_CREATE);
            $table->addQuickSearch(['name', 'extension', 'types']);

            $template->assign('icms_mimetype_table', $table->fetch());
            $template->assign('icms_mimetype_explain', true);
            $template->assign('icms_mimetype_title', _CO_ICMS_MIMETYPES_DSC);
        });
    }

    /**
     * The create form, or the edit form for an existing mimetype, with a token.
     */
    private function edit(object $mimetypes, int $id): Response
    {
        return $this->renderer->render(self::TEMPLATE, static function (object $template) use ($mimetypes, $id): void {
            $mimetype = $mimetypes->get($id);
            if ($mimetype->isNew()) {
                $mimetype->setVar('mimetypeid', 0);
                $mimetype->setVar('extension', '');
                [$caption, $title] = [_CO_ICMS_MIMETYPE_CREATE, _CO_ICMS_MIMETYPE_CREATE_INFO];
            } else {
                [$caption, $title] = [_CO_ICMS_MIMETYPE_EDIT, _CO_ICMS_MIMETYPE_EDIT_INFO];
            }
            $mimetype->getSecureForm($caption, 'addmimetype')->assign($template);
            $template->assign('icms_mimetype_title', $title);
        });
    }

    /**
     * Icms\Ipf\Controller::storeFromDefaultForm(), with a token check and
     * validation by the entity's metadata.
     */
    private function save(object $mimetypes, Request $request): Response
    {
        if (!\icms::$security->check()) {
            return $this->invalidToken();
        }

        $mimetype = $mimetypes->get((int) $request->request->get('mimetypeid', 0));
        $message = $mimetype->isNew() ? _CO_ICMS_MIMETYPE_CREATED : _CO_ICMS_MIMETYPE_MODIFIED;

        $form = $this->forms->submit($this->forms->create($mimetype), $request);
        $errors = $this->persister->save($form);
        if ($errors !== []) {
            return $this->redirect->to((string) \icms::$urls['previouspage'], 3, _CO_ICMS_SAVE_ERROR . self::htmlErrors($errors));
        }

        return $this->redirect->to((string) icms_get_page_before_form(), 2, $message);
    }

    /**
     * Icms\Ipf\Controller::handleObjectDeletion(): the confirmation, then the
     * deletion of a confirmed request with a valid token.
     */
    private function delete(object $mimetypes, Request $request): Response
    {
        $key = $mimetypes->keyName;
        // $_REQUEST: the POST body wins over the query string
        $id = (int) $request->request->get($key, (int) $request->query->get($key, 0));
        $mimetype = $mimetypes->get($id);
        if ($mimetype->isNew()) {
            return $this->redirect->to('javascript:history.go(-1)', 3, _CO_ICMS_NOT_SELECTED);
        }

        if (!$request->request->get('confirm')) {
            $hiddens = [
                'op' => 'del',
                $key => $mimetype->getVar($key),
                'confirm' => 1,
                'redirect_page' => \icms::$urls['previouspage'],
                'fct' => $request->query->get('fct', false),
            ];

            return $this->renderer->renderFragment(static function () use ($hiddens, $mimetype, $mimetypes): void {
                \Icms\Core\Message::confirm(
                    $hiddens,
                    (string) xoops_getenv('SCRIPT_NAME'),
                    sprintf(_CO_ICMS_DELETE_CONFIRM, $mimetype->getVar($mimetypes->identifierName)),
                    _CO_ICMS_DELETE,
                );
            });
        }

        if (!\icms::$security->check()) {
            return $this->invalidToken();
        }
        $target = (string) $request->request->get('redirect_page', '');
        $errors = $this->persister->delete($mimetype);
        if ($errors !== []) {
            return $this->redirect->to($target, 3, _CO_ICMS_DELETE_ERROR . $mimetype->getHtmlErrors());
        }

        return $this->redirect->to($target, 3, _CO_ICMS_DELETE_SUCCESS);
    }

    private function invalidToken(): Response
    {
        return $this->redirect->to(
            (string) \icms::$urls['previouspage'],
            3,
            _CORE_TOKENINVALID . '<br />' . implode('<br />', (array) \icms::$security->getErrors()),
        );
    }

    /**
     * Errors in the format of Icms\Core\Entity::getHtmlErrors().
     *
     * @param list<string> $errors
     */
    private static function htmlErrors(array $errors): string
    {
        return '<h4>' . _ERROR . '</h4>' . implode('', array_map(static fn (string $error): string => htmlspecialchars($error, ENT_QUOTES) . '<br />', $errors));
    }
}
