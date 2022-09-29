@component('mail::message')
# Hello {{ $user->first_name }},
As a Division Manager, you have received a new manuscript record submission from {{ $manuscriptRecord->user->fullname }}.

Please click on the button below or login to your [Open Science Portal]({{config('app.frontend_url')}}#/auth/login?email={{$user->email}}) account to review this manuscript record.


@component('mail::button', ['url' => ''])
Review Manuscript
@endcomponent

@component('mail::panel')
# Manuscript Summary
**Working Title:**<br/> {{ $manuscriptRecord->title }}

**Author(s):**<br/> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{{ $manuscriptRecord->abstract }}
@endcomponent


Thank you,<br>
The Open Science Portal Team

{{-- If is a primary manuscript --}}
@if($manuscriptRecord->type === App\Enums\ManuscriptRecordType::PRIMARY)
@component('mail::subcopy')
Note that, as per section 1.5.3 of the [Fisheries and Oceans Canada National Policy for Science Publications](https://www.dfo-mpo.gc.ca/about-notre-sujet/publications/science/policy-politique/index-eng.html#153), science management commits to a 10 working-day turnaround for signoff of manuscripts for publication. If managers do not respond with an approval within 10 working days, authors may submit their manuscripts to the publisher.
@endcomponent
@endif
@endcomponent
