<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Agent\Agent;

class UserAuthenticationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);
        $agentForHumans = ($agent->platform() ?: $this->user_agent).' - '.($agent->browser() ?: '??');

        return [
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'user_agent_for_humans' => $agentForHumans,
            'login_at' => $this->login_at,
            'login_successful' => $this->login_successful,
            'logout_at' => $this->logout_at,
            'location' => $this->location,
        ];
    }
}
