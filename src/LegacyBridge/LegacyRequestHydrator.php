<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gives a legacy script the superglobals and working directory it would have
 * if the web server had invoked it directly, and restores the host's state
 * afterwards.
 *
 * Sessions are left alone: ImpressCMS configures the save handler and session
 * name itself (Icms\Core\Session) and must be the one to start the session.
 */
final class LegacyRequestHydrator
{
    /**
     * @return array<string, mixed> snapshot for restore()
     */
    public function hydrate(Request $request, LegacyResolution $resolution, string $legacyRoot): array
    {
        $sessionActive = session_status() === PHP_SESSION_ACTIVE;
        $snapshot = [
            '_SERVER' => $_SERVER,
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_FILES' => $_FILES,
            '_REQUEST' => $_REQUEST,
            'session_active' => $sessionActive,
            '_SESSION' => $sessionActive ? ($_SESSION ?? []) : null,
            'cwd' => getcwd() ?: null,
        ];

        $_GET = $request->query->all();
        $_POST = $request->request->all();
        $_COOKIE = $request->cookies->all();
        $_FILES = $this->normalizeUploadedFiles($request->files->all());
        $_REQUEST = self::requestVariables();

        $_SERVER = array_replace($_SERVER, $request->server->all());
        $_SERVER['DOCUMENT_ROOT'] = $legacyRoot;
        $_SERVER['REQUEST_METHOD'] = strtoupper($request->getMethod());
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();
        $_SERVER['QUERY_STRING'] = (string) $request->server->get('QUERY_STRING', '');
        $_SERVER['SCRIPT_FILENAME'] = $resolution->resolvedPath;
        $_SERVER['SCRIPT_NAME'] = $resolution->scriptName;
        $_SERVER['PHP_SELF'] = $resolution->scriptName;
        $_SERVER['PATH_TRANSLATED'] = $resolution->resolvedPath;
        unset($_SERVER['PATH_INFO'], $_SERVER['ORIG_PATH_INFO']);

        chdir($resolution->workingDirectory);

        return $snapshot;
    }

    /**
     * @param array<string, mixed> $snapshot
     */
    public function restore(array $snapshot): void
    {
        $_SERVER = $snapshot['_SERVER'];
        $_GET = $snapshot['_GET'];
        $_POST = $snapshot['_POST'];
        $_COOKIE = $snapshot['_COOKIE'];
        $_FILES = $snapshot['_FILES'];
        $_REQUEST = $snapshot['_REQUEST'];

        // A session the legacy script started belongs to it (ImpressCMS writes it
        // at shutdown); only a session the host had open is put back.
        if ($snapshot['session_active']) {
            $_SESSION = $snapshot['_SESSION'];
        }

        if (is_string($snapshot['cwd'])) {
            chdir($snapshot['cwd']);
        }
    }

    /**
     * $_REQUEST as PHP builds it: request_order (or variables_order) decides
     * which of GET, POST and cookies are merged, later ones winning.
     *
     * @return array<string, mixed>
     */
    private static function requestVariables(): array
    {
        $order = strtoupper((string) (ini_get('request_order') ?: ini_get('variables_order')));
        $request = [];
        foreach (str_split($order) as $source) {
            $request = match ($source) {
                'G' => array_replace($request, $_GET),
                'P' => array_replace($request, $_POST),
                'C' => array_replace($request, $_COOKIE),
                default => $request,
            };
        }

        return $request;
    }

    /**
     * @param array<string, UploadedFile|array<int|string, mixed>|null> $files
     * @return array<string, mixed>
     */
    private function normalizeUploadedFiles(array $files): array
    {
        $normalized = [];
        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFile) {
                $normalized[$key] = [
                    'name' => $value->getClientOriginalName(),
                    'type' => $value->getClientMimeType(),
                    'tmp_name' => $value->getPathname(),
                    'error' => $value->getError(),
                    'size' => $value->getSize(),
                ];
            } elseif (is_array($value)) {
                $normalized[$key] = $this->normalizeUploadedFiles($value);
            } else {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }
}
