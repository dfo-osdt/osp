<?php

namespace Database\Factories;

use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shareable>
 */
class ShareableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => User::factory()->create()->id,
            'shared_by' => User::factory()->create()->id,
            'shareable_type' => ManuscriptRecord::class,
            'shareable_id' => ManuscriptRecord::factory()->create()->id,
        ];
    }

    public function expires(int $days = 5): self
    {
        return $this->state([
            'expires_at' => now()->addDays($days),
        ]);
    }

    public function expired(): self
    {
        return $this->state([
            'expires_at' => now()->subDays(5),
        ]);
    }

    public function editable(): self
    {
        return $this->state([
            'can_edit' => true,
        ]);
    }

    public function deletable(): self
    {
        return $this->state([
            'can_delete' => true,
        ]);
    }
}
