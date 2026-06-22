<?php

namespace App\Console\Commands;

use App\Actions\Delegations\ReassignPendingStepsOnDelegationEnd;
use App\Models\ManagementReviewDelegation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Reassign pending delegated review steps when a delegation has naturally expired')]
#[Signature('osp:reassign-expired-delegation-steps')]
class ReassignExpiredDelegationSteps extends Command
{
    public function handle(): int
    {
        $expired = ManagementReviewDelegation::query()
            ->where('ends_at', '<=', now())
            ->whereNull('ended_early_at')
            ->get();

        foreach ($expired as $delegation) {
            ReassignPendingStepsOnDelegationEnd::handle($delegation);
        }

        $this->info("Processed {$expired->count()} expired delegation(s).");

        return 0;
    }
}
