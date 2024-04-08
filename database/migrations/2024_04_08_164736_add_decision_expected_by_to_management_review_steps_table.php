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

        // Update the existing records to have a decision_expected_by date
        ManagementReviewStep::whereNull('decision_expected_by')->update([
            'decision_expected_by' => now()->addBusinessDays(config('management_review.default_days_to_complete')),
        ]);
    }
};
