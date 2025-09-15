<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ImapAuthenticator
{
    public function attempt(string $email, string $password): bool
    {
        // Optional: limit logins to your domain only
        $allowedDomain = env('IMAP_ALLOWED_DOMAIN');
        if ($allowedDomain && !str_ends_with(strtolower($email), '@' . strtolower($allowedDomain))) {
            return false;
        }

        $host        = env('IMAP_HOST');
        $port        = (int) env('IMAP_PORT', 993);
        $encryption  = strtolower(env('IMAP_ENCRYPTION', 'ssl')); // ssl or tls
        $validate    = filter_var(env('IMAP_VALIDATE_CERT', true), FILTER_VALIDATE_BOOLEAN);
        $timeout     = (int) env('IMAP_TIMEOUT', 10);

        if (!$host || !$port) return false;

        // Build mailbox string
        $flags = '/imap';
        if ($encryption === 'ssl') {
            $flags .= '/ssl';
        } elseif ($encryption === 'tls') {
            $flags .= '/tls';
        }
        $flags .= $validate ? '/validate-cert' : '/novalidate-cert';

        $mailbox = sprintf('{%s:%d%s}INBOX', $host, $port, $flags);

        // Timeouts (avoid long hangs)
        if (function_exists('imap_timeout')) {
            imap_timeout(IMAP_OPENTIMEOUT, $timeout);
            imap_timeout(IMAP_READTIMEOUT, $timeout);
            imap_timeout(IMAP_WRITETIMEOUT, $timeout);
            imap_timeout(IMAP_CLOSETIMEOUT, $timeout);
        }

        // Try connect
        $errorsBefore = imap_errors() ?: [];
        $stream = @imap_open($mailbox, $email, $password, OP_READONLY, 1, []);
        if ($stream !== false) {
            imap_close($stream);
            return true;
        }

        // Optional: log IMAP errors for debugging (don’t show to users)
        $errorsAfter = imap_errors() ?: [];
        $newErrors = array_diff((array)$errorsAfter, (array)$errorsBefore);
        if (!empty($newErrors)) {
            Log::warning('IMAP auth failed', ['email' => $email, 'errors' => array_values($newErrors)]);
        }

        return false;
    }
}
