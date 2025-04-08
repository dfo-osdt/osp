<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            'user_id' => User::factory()->invited()->create()->id,
            'invitation_token' => Invitation::generateInvitationToken(),
            'invited_by' => User::factory()->create()->id,
        ];
    }
}
