<x-mail::message>
# Management Review Delegation Created

*(le français suit)*

A new management review delegation has been created with the following details:

- **Delegator:** {{ $owner->first_name }} {{ $owner->last_name }} ({{ $owner->email }})
- **Delegate:** {{ $delegate->first_name }} {{ $delegate->last_name }} ({{ $delegate->email }})
- **Starts:** {{ $delegation->starts_at ? $delegation->starts_at->format('Y-m-d H:i') : 'Immediately' }}
- **Ends:** {{ $delegation->ends_at->format('Y-m-d H:i') }}
@if($delegation->comment)
- **Comment:** {{ $delegation->comment }}
@endif

New management review steps assigned to {{ $owner->first_name }} {{ $owner->last_name }} will be automatically reassigned to {{ $delegate->first_name }} {{ $delegate->last_name }} during this period.

<x-email.regards locale="en" />

---

# Délégation de révision de gestion créée

Une nouvelle délégation de révision de gestion a été créée avec les détails suivants :

- **Délégateur :** {{ $owner->first_name }} {{ $owner->last_name }} ({{ $owner->email }})
- **Délégué :** {{ $delegate->first_name }} {{ $delegate->last_name }} ({{ $delegate->email }})
- **Début :** {{ $delegation->starts_at ? $delegation->starts_at->format('Y-m-d H:i') : 'Immédiatement' }}
- **Fin :** {{ $delegation->ends_at->format('Y-m-d H:i') }}
@if($delegation->comment)
- **Commentaire :** {{ $delegation->comment }}
@endif

Les nouvelles étapes de révision de gestion assignées à {{ $owner->first_name }} {{ $owner->last_name }} seront automatiquement réassignées à {{ $delegate->first_name }} {{ $delegate->last_name }} durant cette période.

<x-email.regards locale="fr" />

</x-mail::message>
