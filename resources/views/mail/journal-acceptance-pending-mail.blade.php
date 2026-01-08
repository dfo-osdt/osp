<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

This is your monthly reminder to update the status of your manuscripts and publications.

@if($manuscripts->count() > 0)
## Manuscripts Pending Acceptance

You have {{ $manuscripts->count() }} {{ $manuscripts->count() === 1 ? 'manuscript' : 'manuscripts' }} that {{ $manuscripts->count() === 1 ? 'has' : 'have' }} been reviewed but not yet marked as accepted in the system:

<x-mail::table>
| Manuscript Title | Status | Submitted to Journal | Reviewed |
| :------------- | :------------- | :------------- | :------------- |
@foreach($manuscripts as $manuscript)
| [{{ Str::limit($manuscript->title, 50) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $manuscript->id }}) | {{ ucfirst(str_replace('_', ' ', $manuscript->status->value)) }} | {{ $manuscript->submitted_to_journal_on ? \Carbon\Carbon::parse($manuscript->submitted_to_journal_on)->locale('en_CA')->isoFormat('LL') : 'N/A' }} | {{ $manuscript->reviewed_at ? \Carbon\Carbon::parse($manuscript->reviewed_at)->locale('en_CA')->diffForHumans() : 'N/A' }} |
@endforeach
</x-mail::table>

If any of these manuscripts have been accepted by a journal, please update their status in the system. If you're still waiting for acceptance, no action is needed.
@endif

@if($publications->count() > 0)
## Publications Pending Publishing Status

You have {{ $publications->count() }} {{ $publications->count() === 1 ? 'publication' : 'publications' }} that {{ $publications->count() === 1 ? 'has' : 'have' }} been accepted but not yet marked as published:

<x-mail::table>
| Publication Title | Journal | Accepted On |
| :------------- | :------------- | :------------- |
@foreach($publications as $publication)
| [{{ Str::limit($publication->title, 50) }}]({{ config('app.frontend_url') }}#/publication/{{ $publication->id }}) | {{ $publication->journal->title }} | {{ $publication->accepted_on ? $publication->accepted_on->locale('en_CA')->isoFormat('LL') : 'N/A' }} |
@endforeach
</x-mail::table>

Please update the publication record with the final details (DOI, publication date, etc.) and attach the accepted copy of the paper, then mark them as published.
@endif

<x-mail::button :url="config('app.frontend_url').'#/manuscripts'">
View My Manuscripts & Publications
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $user->first_name }},

Ceci est votre rappel mensuel de mettre à jour le statut de vos manuscrits et publications.

@if($manuscripts->count() > 0)
## Manuscrits en attente d'acceptation

Vous avez {{ $manuscripts->count() }} {{ $manuscripts->count() === 1 ? 'manuscrit qui a été révisé' : 'manuscrits qui ont été révisés' }} mais pas encore {{ $manuscripts->count() === 1 ? 'marqué comme accepté' : 'marqués comme acceptés' }} dans le système :

<x-mail::table>
| Titre du manuscrit | Statut | Soumis à la revue | Révisé |
| :------------- | :------------- | :------------- | :------------- |
@foreach($manuscripts as $manuscript)
| [{{ Str::limit($manuscript->title, 50) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $manuscript->id }}) | {{ ucfirst(str_replace('_', ' ', $manuscript->status->value)) }} | {{ $manuscript->submitted_to_journal_on ? \Carbon\Carbon::parse($manuscript->submitted_to_journal_on)->locale('fr_CA')->isoFormat('LL') : 'N/A' }} | {{ $manuscript->reviewed_at ? \Carbon\Carbon::parse($manuscript->reviewed_at)->locale('fr_CA')->diffForHumans() : 'N/A' }} |
@endforeach
</x-mail::table>

Si l'un de ces manuscrits a été accepté par une revue, veuillez mettre à jour son statut dans le système. Si vous attendez toujours l'acceptation, aucune action n'est nécessaire.
@endif

@if($publications->count() > 0)
## Publications en attente de statut publié

Vous avez {{ $publications->count() }} {{ $publications->count() === 1 ? 'publication qui a été acceptée' : 'publications qui ont été acceptées' }} mais pas encore {{ $publications->count() === 1 ? 'marquée comme publiée' : 'marquées comme publiées' }} :

<x-mail::table>
| Titre de la publication | Revue | Acceptée le |
| :------------- | :------------- | :------------- |
@foreach($publications as $publication)
| [{{ Str::limit($publication->title, 50) }}]({{ config('app.frontend_url') }}#/publication/{{ $publication->id }}) | {{ $publication->journal->title }} | {{ $publication->accepted_on ? $publication->accepted_on->locale('fr_CA')->isoFormat('LL') : 'N/A' }} |
@endforeach
</x-mail::table>

Veuillez mettre à jour le dossier de publication avec les détails finaux (DOI, date de publication, etc.) et joindre la copie acceptée de l'article, puis les marquer comme publiées.
@endif

<x-mail::button :url="config('app.frontend_url').'#/manuscripts'">
Voir mes manuscrits et publications
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
