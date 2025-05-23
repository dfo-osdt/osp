@props(['locale' => 'en', 'managementReviewStep' => null])
@if($locale == 'fr')
    @if($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING)
        @if($managementReviewStep->decision_expected_by === null)
        Une réponse à cette révision est attendue dans un délai raisonnable.
        @else
        Une réponse à cette révision est attendue d'ici le {{ $managementReviewStep->decision_expected_by->locale('fr_CA')->isoFormat('LL') }}.
        @endif
    @elseif($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::ON_HOLD)
    Une réponse à cette révision est actuellement en attente jusqu'à ce que les commentaires soient traités par les auteurs.
    @endif
@else
    @if($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::PENDING)
        @if($managementReviewStep->decision_expected_by === null)
        A response to this review is expected in a timely manner.
        @else
        A response to this review is expected by {{ $managementReviewStep->decision_expected_by->locale('en_CA')->isoFormat('LL') }}.
        @endif
    @elseif($managementReviewStep->status == App\Enums\ManagementReviewStepStatus::ON_HOLD)
    A response to this review is currently on hold until the comments are addressed by the authors.
    @endif
@endif