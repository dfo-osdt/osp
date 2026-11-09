<x-mail::message>
# Hello,

*(le français suit)*

Science Publications has marked the publication titled "**{!! \App\Services\TitleMarkup::sanitize($publication->title) !!}**" as published
in "**{{ $publication->journal->title }}**" on {{ $publication->published_on?->format('Y-m-d') }}.
@if ($publication->doi)

DOI: [{{ $publication->doi }}]({{ $publication->doi }})
@endif

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    View Publication
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour,

L'équipe des publications scientifiques a marqué la publication intitulée "**{!! \App\Services\TitleMarkup::sanitize($publication->title) !!}**" comme publiée
dans "**{{ $publication->journal->title }}**" le {{ $publication->published_on?->format('Y-m-d') }}.
@if ($publication->doi)

DOI : [{{ $publication->doi }}]({{ $publication->doi }})
@endif

<x-mail::button :url="config('app.frontend_url').'#/publication/'.$publication->id">
    Voir la publication
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
