<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

You have {{ $reviews->count() }} management {{ $reviews->count() === 1 ? 'review' : 'reviews' }} that {{ $reviews->count() === 1 ? 'requires' : 'require' }} your attention:

<x-mail::table>
| Manuscript Title | Due Date | Status |
| :------------- | :------------- | :------------- |
@foreach($reviews as $review)
| [{{ Str::limit($review->manuscriptRecord->title, 60) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $review->manuscript_record_id }}/reviews) | {{ $review->decision_expected_by->locale('en_CA')->isoFormat('LL') }} | @if($review->decision_expected_by < now())**Overdue** ({{ $review->decision_expected_by->diffForHumans() }})@else {{ $review->decision_expected_by->diffForHumans() }}@endif |
@endforeach
</x-mail::table>

Please click the button below to view all your pending management reviews.

<x-mail::button :url="config('app.frontend_url').'#/my-reviews'">
View My Reviews
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $user->first_name }},

Vous avez {{ $reviews->count() }} {{ $reviews->count() === 1 ? 'révision de gestion qui nécessite' : 'révisions de gestion qui nécessitent' }} votre attention:

<x-mail::table>
| Titre du manuscrit | Date d'échéance | Statut |
| :------------- | :------------- | :------------- |
@foreach($reviews as $review)
| [{{ Str::limit($review->manuscriptRecord->title, 60) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $review->manuscript_record_id }}/reviews) | {{ $review->decision_expected_by->locale('fr_CA')->isoFormat('LL') }} | @if($review->decision_expected_by < now())**En retard** ({{ $review->decision_expected_by->locale('fr_CA')->diffForHumans() }})@else {{ $review->decision_expected_by->locale('fr_CA')->diffForHumans() }}@endif |
@endforeach
</x-mail::table>

Veuillez cliquer sur le bouton ci-dessous pour voir toutes vos révisions de gestion en attente.

<x-mail::button :url="config('app.frontend_url').'#/my-reviews'">
Voir mes révisions
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
