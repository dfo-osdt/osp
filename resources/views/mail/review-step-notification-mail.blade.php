@component('mail::message')
# Hello {{ $managementReviewStep->user->first_name }},
{{ $previousStep->user->fullName }} has identified you as the next management reviewer for the manuscript titled "{{ $manuscriptRecord->title }}".

@component('mail::panel')
# Previous Review Summary
**Decision:**<br/>
{{ $previousStep->decision }}

**Comments:** <br/>
{!! $previousStep->comments !!}
@endcomponent

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$managementReviewStep->user->email}}) account to review this manuscript record.

@component('mail::button', ['url' => config('app.frontend_url').'#/auth/login?email='.$managementReviewStep->user->email.'&redirect=/manuscript/'.$managementReviewStep->manuscriptRecord->id])
Review Manuscript
@endcomponent

@component('mail::panel')
# Manuscript Summary
**Working Title:**<br/> {{ $manuscriptRecord->title }}

**Author(s):**<br/> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{!! $manuscriptRecord->abstract !!}
@endcomponent


Thank you,<br>
{{ config('app.name') }}

{{-- If is a primary manuscript --}}
@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY)
@component('mail::subcopy')
Note that, as per section 1.5.3 of the [Fisheries and Oceans Canada National Policy for Science Publications](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html#153), science management commits to a 10 working-day turnaround for signoff of manuscripts for publication. If managers do not respond with an approval within 10 working days, authors may submit their manuscripts to the publisher.
@endcomponent
@endif

@endcomponent
