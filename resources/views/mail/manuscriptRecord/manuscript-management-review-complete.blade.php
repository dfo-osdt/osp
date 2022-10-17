@component('mail::message')
# Hello {{ $manuscript->user->name }},

Your manuscripts' management review is complete.

You may proceed with the publication process for the manuscript titled "{{ $manuscriptRecord->title }}". Please ensure that The text “Fisheries and Oceans Canada” appears
as an author's affiliation in order to ensure that publications by DFO authors are retrievable in online searches and discovered for bibliometric analysis.

Once published, please ensure that you update the manuscript record with your publication information in the Open Science Portal.
If you decide to no longer publish this manuscript, update the manuscript record status to "withdrawn".

@component('mail::button', ['url' => config('app.frontend_url').'#/auth/login?email='.$user->email.'&redirect=/manuscript/'.$manuscriptRecord->id])
Update Manuscript Status
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
