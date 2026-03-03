<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationGroupMember>
 */
class NotificationGroupMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'member_user_id' => User::factory(),
            'expires_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'expires_at' => now()->subDay(),
        ]);
    }

    public function expiresInDays(int $days): static
    {
        return $this->state([
            'expires_at' => now()->addDays($days),
        ]);
    }
}
