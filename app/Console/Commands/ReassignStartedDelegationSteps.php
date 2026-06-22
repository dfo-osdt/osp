<?php

namespace App\Console\Commands;

use App\Actions\Delegations\ReassignPendingStepsOnDelegationStart;
use App\Models\ManagementReviewDelegation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Reassign pending review steps to delegates whose scheduled delegation has just become active')]
#[Signature('osp:reassign-started-delegation-steps')]
class ReassignStartedDelegationSteps extends Command
{
    public function handle(): int
    {
        // Only scheduled delegations (starts_at IS NOT NULL) that are now active.
        // Immediate delegations (starts_at IS NULL) are handled at creation time.
        // The action only picks up PENDING steps, so re-running is safe.
        $started = ManagementReviewDelegation::active()
            ->whereNotNull('starts_at')
            ->get();

        foreach ($started as $delegation) {
            ReassignPendingStepsOnDelegationStart::handle($delegation);
        }

        $this->info("Processed {$started->count()} started scheduled delegation(s).");

        return 0;
    }
}
