<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AuthenticationLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AuthenticationLog
 */
class UserAuthenticationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // example user agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36
        $agentArray = preg_split("/[\(\)]/", (string) $this->user_agent);
        $agentForHumans = $agentArray[1] ?? $this->user_agent;

        return [
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'user_agent_for_humans' => $agentForHumans,
            'login_at' => $this->login_at,
            'login_successful' => $this->login_successful,
            'logout_at' => $this->logout_at,
        ];
    }
}
