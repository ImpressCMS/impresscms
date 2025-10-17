<?php

declare(strict_types=1);

namespace icms\messaging;

use icms_messaging_Handler;
use PDO;
use Throwable;

/**
 * MentionService
 * - Extract mentioned users from rich text content
 * - Notify mentioned users via ImpressCMS messaging (PM)
 *
 * Conventions
 * - Parses plain text @username tokens
 * - Parses legacy HTML links to user profiles containing uid query param
 * - Resolves only active users (level > 0)
 */
final class MentionService
{
    /**
     * Extract unique mentioned user IDs from content.
     * Supports:
     * - Plain text: "@username" (alnum, underscore, dash, dot allowed)
     * - Legacy HTML link: <a href="...uid=123">...</a>
     */
    public static function extractMentionedUserIds(string $content): array
    {
        $userIds = [];

        // 1) Plain text @username
        $usernames = self::parseAtUsernames($content);
        if ($usernames) {
            $idsFromNames = self::mapUsernamesToIds($usernames);
            foreach ($idsFromNames as $uid) {
                $userIds[$uid] = true;
            }
        }

        // 2) Legacy HTML links with uid=...
        foreach (self::parseLegacyUidLinks($content) as $uid) {
            $userIds[$uid] = true;
        }

        $ids = array_keys($userIds);
        sort($ids, SORT_NUMERIC);
        return array_map('intval', $ids);
    }

    /**
     * Send PM notifications to mentioned users.
     * Skips the sender if included in recipients.
     */
    public static function notifyMentionedUsers(array $userIds, string $subject, string $url, int $senderId, string $context): void
    {
        if (empty($userIds)) {
            return;
        }

        // Filter out sender and ensure users are active.
        $recipients = self::filterActiveUsers(array_values(array_unique(array_diff($userIds, [$senderId]))));
        if (empty($recipients)) {
            return;
        }

        $handler = new icms_messaging_Handler();
        $handler->usePM();

        $senderName = is_object(\icms::$user) ? (string) \icms::$user->getVar('uname') : 'system';
        $body = self::buildBody($subject, $url, $senderName, $context);

        $handler->setSubject($subject);
        $handler->setBody($body);

        // Load user objects and send one by one to preserve per-user templating in Handler.
        $memberHandler = \icms::handler('icms_member');
        foreach ($recipients as $uid) {
            $userObj = $memberHandler->getUser($uid);
            if ($userObj) {
                $handler->reset();
                $handler->usePM();
                $handler->setSubject($subject);
                $handler->setBody($body);
                $handler->setToUsers($userObj);
                // Ignore result; upstream logs errors internally. We keep best-effort semantics.
                $handler->send(false);
            }
        }
    }

    // ---------- Internals ----------

    /**
     * Parse @username tokens.
     * Returns lowercase unique usernames.
     */
    private static function parseAtUsernames(string $content): array
    {
        // Allow usernames like user.name, user_name, user-name, digits.
        // Boundaries: start or non-word char before @, then capture, ensure not email domain (avoid in emails).
        $pattern = '/(?<![\w@])@([A-Za-z0-9._-]{1,64})/u';
        preg_match_all($pattern, $content, $m);
        if (empty($m[1])) {
            return [];
        }
        $names = [];
        foreach ($m[1] as $name) {
            // Skip obvious email parts like example.com
            if (strpos($name, '.') !== false && preg_match('/^[A-Za-z0-9._-]+\.[A-Za-z]{2,}$/', $name)) {
                continue;
            }
            $names[strtolower($name)] = true;
        }
        return array_keys($names);
    }

    /**
     * Parse legacy HTML links that contain uid in query string.
     * Supports various paths (user.php, modules/profile, etc.).
     */
    private static function parseLegacyUidLinks(string $content): array
    {
        $uids = [];
        // Match href="...uid=123" minimal; capture the number.
        $pattern = '/href\s*=\s*"[^"]*?uid=(\d+)/i';
        preg_match_all($pattern, $content, $m);
        if (!empty($m[1])) {
            foreach ($m[1] as $uid) {
                $uid = (int) $uid;
                if ($uid > 0) {
                    $uids[$uid] = true;
                }
            }
        }
        return array_map('intval', array_keys($uids));
    }

    /**
     * Map usernames to user IDs for active users only.
     * Returns list of ints.
     */
    private static function mapUsernamesToIds(array $usernames): array
    {
        if (empty($usernames)) {
            return [];
        }

        // Build placeholders
        $placeholders = [];
        $params = [];
        foreach ($usernames as $i => $uname) {
            $key = ":u{$i}";
            $placeholders[] = $key;
            $params[$key] = $uname;
        }

        try {
            /** @var PDO $pdo */
            $pdo = \icms::$db;
            $sql = 'SELECT uid FROM ' . str_replace('`','', \icms::$xoopsDB->prefix('users')) .
                ' WHERE level > 0 AND LOWER(uname) IN (' . implode(',', $placeholders) . ')';
            $stmt = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, strtolower($v), PDO::PARAM_STR);
            }
            $stmt->execute();
            $uids = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $uids[] = (int) $row['uid'];
            }
            return $uids;
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Ensure target user IDs are active.
     */
    private static function filterActiveUsers(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }
        $placeholders = [];
        $params = [];
        foreach ($userIds as $i => $uid) {
            $key = ":id{$i}";
            $placeholders[] = $key;
            $params[$key] = (int) $uid;
        }
        try {
            /** @var PDO $pdo */
            $pdo = \icms::$db;
            $sql = 'SELECT uid FROM ' . str_replace('`','', \icms::$xoopsDB->prefix('users')) .
                ' WHERE level > 0 AND uid IN (' . implode(',', $placeholders) . ')';
            $stmt = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v, PDO::PARAM_INT);
            }
            $stmt->execute();
            $uids = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $uids[] = (int) $row['uid'];
            }
            return $uids;
        } catch (Throwable $e) {
            return [];
        }
    }

    private static function buildBody(string $subject, string $url, string $senderName, string $context): string
    {
        $site = (string) ($GLOBALS['icmsConfig']['sitename'] ?? 'ImpressCMS');
        $lines = [
            sprintf('%s mentioned you in %s.', $senderName, $context),
            sprintf('Subject: %s', $subject),
            sprintf('Link: %s', $url),
            '',
            sprintf('Sent from %s', $site),
        ];
        return implode("\n", $lines);
    }
}

