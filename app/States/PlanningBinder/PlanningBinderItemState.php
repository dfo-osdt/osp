<?php

namespace App\States\PlanningBinder;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use Thunk\Verbs\State;

class PlanningBinderItemState extends State
{

    public PlanningBinderItemStatus $status = PlanningBinderItemStatus::FLAGGED;

    public ManuscriptRecordType $manuscript_record_type;
    public ?string $manuscript_record_ulid = null;
    public ?int $publication_id = null;
}
