<?php

namespace Tests\Feature\Librarium;

use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\Pages\ListManuscripts;
use App\Models\ManuscriptRecord;
use App\Models\User;

describe('List Manuscripts tabs', function (): void {
    it('shows status tab badges from full filtered dataset even when a tab is active', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::DRAFT]);
        ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::IN_REVIEW]);
        ManuscriptRecord::factory()->create(['status' => ManuscriptRecordStatus::REVIEWED]);

        $component = $this->actingAs($admin)->livewire(ListManuscripts::class)
            ->set('activeTab', 'draft');

        $tabs = $component->instance()->getTabs();

        expect((int) $tabs['all']->getBadge())->toBe(3)
            ->and((int) $tabs['draft']->getBadge())->toBe(1)
            ->and((int) $tabs['in_review']->getBadge())->toBe(1)
            ->and((int) $tabs['reviewed']->getBadge())->toBe(1);
    });
});
