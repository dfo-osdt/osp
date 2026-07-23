<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\RegionNotificationGroupMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegionNotificationGroupMember>
 */
class RegionNotificationGroupMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'region_id' => fn () => Region::query()->inRandomOrder()->value('id'),
            'user_id' => User::factory(),
        ];
    }
}
