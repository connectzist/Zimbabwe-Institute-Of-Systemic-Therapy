<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CpanelEmailService
{
    protected $host;
    protected $user;
    protected $token;
    protected $port;

    public function __construct()
    {
        $this->host  = env('CPANEL_HOST');
        $this->user  = env('CPANEL_USER');
        $this->token = env('CPANEL_TOKEN');
        $this->port  = env('CPANEL_PORT', 2083);
    }

    /**
     * Change a cPanel email account password.
     *
     * @param string $email
     * @param string $newPassword
     * @return bool|string
     */
    public function resetEmailPassword(string $email, string $newPassword)
    {
        $localPart = explode('@', $email)[0];
        $domain = explode('@', $email)[1] ?? '';

        $url = "https://{$this->host}:{$this->port}/json-api/cpanel";

        $response = Http::withHeaders([
            'Authorization' => "cpanel {$this->user}:{$this->token}",
        ])->post($url, [
            'cpanel_jsonapi_user'       => $this->user,
            'cpanel_jsonapi_apiversion' => '2',
            'cpanel_jsonapi_module'     => 'Email',
            'cpanel_jsonapi_func'       => 'passwdpop',
            'email'                     => $localPart,
            'domain'                    => $domain,
            'password'                  => $newPassword,
        ]);

        if (!$response->successful()) {
            return "HTTP error: " . $response->status();
        }

        $body = $response->json('cpanelresult');
        $data = $body['data'][0] ?? null;
        $eventResult = $body['event']['result'] ?? 0;

        if ($eventResult == 1 && isset($data['result']) && $data['result'] == 1) {
            return true;
        }

        $reason = $data['reason'] ?? 'Unknown error';
        return "cPanel error: " . $reason;
    }

    /**
     * List all email accounts on the cPanel domain.
     *
     * @return array
     */
    public function listAllEmails(): array
    {
        $url = "https://{$this->host}:{$this->port}/json-api/cpanel";

        $response = Http::withHeaders([
            'Authorization' => "cpanel {$this->user}:{$this->token}",
        ])->get($url, [
            'cpanel_jsonapi_user'       => $this->user,
            'cpanel_jsonapi_apiversion' => '2',
            'cpanel_jsonapi_module'     => 'Email',
            'cpanel_jsonapi_func'       => 'listpops',
        ]);

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json('cpanelresult.data') ?? [];

        return collect($data)
            ->pluck('email')
            ->map(fn($email) => strtolower($email))
            ->toArray();
    }

    /**
     * Check if a specific email exists.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return in_array(strtolower($email), $this->listAllEmails());
    }
}
