<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

This is your monthly reminder to update the status of your manuscripts. You have {{ $manuscripts->count() }} {{ $manuscripts->count() === 1 ? 'manuscript' : 'manuscripts' }} that {{ $manuscripts->count() === 1 ? 'has' : 'have' }} been reviewed but not yet marked as accepted in the system:

<x-mail::table>
| Manuscript Title | Status | Submitted to Journal | Reviewed |
| :------------- | :------------- | :------------- | :------------- |
@foreach($manuscripts as $manuscript)
| [{{ Str::limit($manuscript->title, 50) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $manuscript->id }}) | {{ ucfirst(str_replace('_', ' ', $manuscript->status->value)) }} | {{ $manuscript->submitted_to_journal_on ? \Carbon\Carbon::parse($manuscript->submitted_to_journal_on)->locale('en_CA')->isoFormat('LL') : 'N/A' }} | {{ $manuscript->reviewed_at ? \Carbon\Carbon::parse($manuscript->reviewed_at)->locale('en_CA')->diffForHumans() : 'N/A' }} |
@endforeach
</x-mail::table>

If any of these manuscripts have been accepted by a journal, please update their status in the system. If you're still waiting for acceptance, no action is needed.

<x-mail::button :url="config('app.frontend_url').'#/manuscripts'">
View My Manuscripts
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $user->first_name }},

Ceci est votre rappel mensuel de mettre à jour le statut de vos manuscrits. Vous avez {{ $manuscripts->count() }} {{ $manuscripts->count() === 1 ? 'manuscrit qui a été révisé' : 'manuscrits qui ont été révisés' }} mais pas encore {{ $manuscripts->count() === 1 ? 'marqué comme accepté' : 'marqués comme acceptés' }} dans le système :

<x-mail::table>
| Titre du manuscrit | Statut | Soumis à la revue | Révisé |
| :------------- | :------------- | :------------- | :------------- |
@foreach($manuscripts as $manuscript)
| [{{ Str::limit($manuscript->title, 50) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $manuscript->id }}) | {{ ucfirst(str_replace('_', ' ', $manuscript->status->value)) }} | {{ $manuscript->submitted_to_journal_on ? \Carbon\Carbon::parse($manuscript->submitted_to_journal_on)->locale('fr_CA')->isoFormat('LL') : 'N/A' }} | {{ $manuscript->reviewed_at ? \Carbon\Carbon::parse($manuscript->reviewed_at)->locale('fr_CA')->diffForHumans() : 'N/A' }} |
@endforeach
</x-mail::table>

Si l'un de ces manuscrits a été accepté par une revue, veuillez mettre à jour son statut dans le système. Si vous attendez toujours l'acceptation, aucune action n'est nécessaire.

<x-mail::button :url="config('app.frontend_url').'#/manuscripts'">
Voir mes manuscrits
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
