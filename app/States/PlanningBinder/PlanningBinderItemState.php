<?php

namespace App\States\PlanningBinder;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use Thunk\Verbs\State;

class PlanningBinderItemState extends State
{
    public PlanningBinderItemStatus $status = PlanningBinderItemStatus::FLAGGED;

    public ManuscriptRecordType $manuscript_record_type;

    public int $referrer_user_id;

    public ?string $manuscript_record_ulid = null;

    public ?int $publication_id = null;
}
