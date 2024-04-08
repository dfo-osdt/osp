<?php

use App\Models\ManagementReviewStep;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('management_review_steps', function (Blueprint $table) {
            $table->datetime('decision_expected_by')->nullable()->after('decision');
        });

        // Update the existing records to have a decision_expected_by
        ManagementReviewStep::whereNull('decision_expected_by')->get()
            ->each(fn (ManagementReviewStep $step) => $step->update([
                'decision_expected_by' => $step->created_at->addBusinessDays(config('osp.management_review.decision_expected_business_days'))
            ]));
    }
};
