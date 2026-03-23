<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Models\ManuscriptRecord;
use App\Models\PlanningBinderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlanningBinderItem>
 */
class PlanningBinderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plannable_type' => 'App\Models\ManuscriptRecord',
            'plannable_id' => ManuscriptRecord::factory()->create()->id,
            'type' => ManuscriptRecordType::PRIMARY,
            'status' => PlanningBinderItemStatus::FLAGGED,
            'region_id' => 1,
        ];
    }
}
