<?php

namespace App\Jobs;

use App\Http\Integrations\Orcid\Data\EmploymentData;
use App\Http\Integrations\Orcid\OrcidMemberAPIConnector;
use App\Http\Integrations\Orcid\Requests\DeleteEmploymentRequest;
use App\Http\Integrations\Orcid\Requests\PostEmploymentRequest;
use App\Http\Integrations\Orcid\Requests\PutEmploymentRequest;
use App\Models\AuthorEmployment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\LaravelData\Optional;

class SyncAuthorEmploymentWithOrcid implements ShouldQueue
{
    use Queueable;

    const SYNC_TYPE_CREATE = 'create';

    const SYNC_TYPE_UPDATE = 'update';

    const SYNC_TYPE_DELETE = 'delete';

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(protected AuthorEmployment $authorEmployment, protected string $syncType)
    {
        Log::debug('Job Created: Syncing author employment with ORCID', [
            'author_employment_id' => $authorEmployment->id,
            'syncType' => $syncType,
        ]);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug('Job Starting: Syncing author employment with ORCID');

        // Does the author have a valid ORCID connection?
        $author = $this->authorEmployment->author;
        if (! $author->hasValidOrcidCredentials()) {
            Log::error('SyncAuthor: Author does not have valid ORCID credentials');

            return;
        }

        $connector = new OrcidMemberAPIConnector($author->orcid_access_token, $author->orcid_number);

        switch ($this->syncType) {
            case self::SYNC_TYPE_CREATE:
                $this->createEmploymentRecordInOrcid($connector);
                break;
            case self::SYNC_TYPE_UPDATE:
                $this->updateEmploymentRecordInOrcid($connector);
                break;
            case self::SYNC_TYPE_DELETE:
                $this->deleteEmploymentRecordInOrcid($connector);
                break;
            default:
                Log::error('SyncAuthor: Invalid sync type');
                $this->fail('Invalid sync type');
                break;
        }
    }

    private function createEmploymentRecordInOrcid(OrcidMemberAPIConnector $connector): void
    {
        $employmentData = EmploymentData::fromModel($this->authorEmployment);

        // there should be no putcode here; the only scneario where this
        // is usefull is when the record is being updated after being
        //mistakenly deleted in ORCID profile by user.
        $employmentData->putCode = Optional::create();

        $request = new PostEmploymentRequest($employmentData);
        $response = $connector->send($request);

        if ($response->failed() || $response->status() !== 201) {
            Log::error('SyncAuthor: Error creating employment record in ORCID', [
                'status' => $response->status(),
                'headers' => $response->headers()->all(),
            ]);
            $response->throw();
        }

        if (! $response->header('location')) {
            throw new \Exception('No location header in response - cannot get putcode');
        }

        $location = $response->header('location');
        $putcode = (int) Str::afterLast($location, '/');

        $this->authorEmployment->orcid_putcode = $putcode;
        $this->authorEmployment->orcid_updated_at = now();
        $this->authorEmployment->save();

        Log::info('SyncAuthor: Employment record created in ORCID', [
            'author_employment_id' => $this->authorEmployment->id,
        ]);

    }

    private function updateEmploymentRecordInOrcid(OrcidMemberAPIConnector $connector): void
    {
        $employmentData = EmploymentData::fromModel($this->authorEmployment);
        $request = new PutEmploymentRequest($employmentData);
        $response = $connector->send($request);

        if ($response->failed() || $response->status() !== 200) {

            if ($response->status() === 404) {
                Log::error('SyncAuthor: Employment record not found in ORCID - Likely Deleted', [
                    'author_employment_id' => $this->authorEmployment->id,
                ]);
                $this->createEmploymentRecordInOrcid($connector);

                return;
            }

            Log::error('SyncAuthor: Error updating employment record in ORCID', [
                'status' => $response->status(),
                'headers' => $response->headers()->all(),
            ]);
            $response->throw();
        }

        $this->authorEmployment->orcid_updated_at = now();
        $this->authorEmployment->save();

        Log::info('SyncAuthor: Employment record updated in ORCID', [
            'author_employment_id' => $this->authorEmployment->id,
        ]);

    }

    private function deleteEmploymentRecordInOrcid(OrcidMemberAPIConnector $connector): void
    {
        $putcode = $this->authorEmployment->orcid_putcode;

        // It's possible that the record was never created in ORCID, in this case
        // we can just delete the record from the database.
        if(! $putcode) {
            Log::info('SyncAuthor: Deleting Employment record without a putcode', [
                'author_employment_id' => $this->authorEmployment->id,
            ]);
            $this->authorEmployment->forceDelete();
            return;
        }

        $request = new DeleteEmploymentRequest($putcode);
        $response = $connector->send($request);

        if ($response->failed() || $response->status() !== 204 || $response->status() !== 404) {
            Log::error('SyncAuthor: Error deleting employment record in ORCID', [
                'status' => $response->status(),
                'headers' => $response->headers()->all(),
            ]);
            $response->throw();
        }

        Log::info('SyncAuthor: Employment record deleted in ORCID', [
            'author_employment_id' => $this->authorEmployment->id,
        ]);

        $this->authorEmployment->forceDelete();

    }
}
