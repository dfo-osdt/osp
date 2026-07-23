<?php

namespace Tests\Feature\Librarium;

use App\Filament\Resources\Regions\Pages\ManageRegionNotificationGroup;
use App\Models\Region;
use App\Models\RegionNotificationGroupMember;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

describe('Manage Region Notification Group', function (): void {

    beforeEach(function (): void {
        $this->region = Region::query()->first();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    });

    it('Admin can render the notification group page', function (): void {
        $this->actingAs($this->admin)
            ->get(ManageRegionNotificationGroup::getUrl(['record' => $this->region->getRouteKey()]))
            ->assertOk();
    });

    it('Admin can add a member to the regional notification group', function (): void {
        $member = User::factory()->create(['active' => true]);

        $this->actingAs($this->admin)
            ->livewire(ManageRegionNotificationGroup::class, ['record' => $this->region->getRouteKey()])
            ->callAction(CreateAction::class, [
                'user_id' => $member->id,
            ])
            ->assertNotified();

        assertDatabaseHas(RegionNotificationGroupMember::class, [
            'region_id' => $this->region->id,
            'user_id' => $member->id,
        ]);
    });

    it('Admin can remove a member from the regional notification group', function (): void {
        $membership = RegionNotificationGroupMember::factory()->create([
            'region_id' => $this->region->id,
        ]);

        $this->actingAs($this->admin)
            ->livewire(ManageRegionNotificationGroup::class, ['record' => $this->region->getRouteKey()])
            ->callAction(TestAction::make(DeleteAction::class)->table($membership));

        assertDatabaseMissing(RegionNotificationGroupMember::class, [
            'id' => $membership->id,
        ]);
    });
});
