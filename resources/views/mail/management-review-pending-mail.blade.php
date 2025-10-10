<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

This is your weekly summary of pending management reviews. You have {{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }} awaiting your review:

<x-mail::table>
| Manuscript Title | Due Date | Pending For |
| :------------- | :------------- | :------------- |
@foreach($reviews as $review)
| [{{ Str::limit($review->manuscriptRecord->title, 60) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $review->manuscript_record_id }}/reviews) | {{ $review->decision_expected_by->locale('en_CA')->isoFormat('LL') }} | {{ $review->created_at->diffForHumans() }} |
@endforeach
</x-mail::table>

Please click the button below to view all your pending management reviews.

<x-mail::button :url="config('app.frontend_url').'#/my-reviews'">
View My Reviews
</x-mail::button>

<x-email.regards locale="en" />

---

# Bonjour {{ $user->first_name }},

Voici votre résumé hebdomadaire des révisions de gestion en attente. Vous avez {{ $reviews->count() }} {{ $reviews->count() === 1 ? 'révision en attente' : 'révisions en attente' }} de votre révision :

<x-mail::table>
| Titre du manuscrit | Date d'échéance | En attente depuis |
| :------------- | :------------- | :------------- |
@foreach($reviews as $review)
| [{{ Str::limit($review->manuscriptRecord->title, 60) }}]({{ config('app.frontend_url') }}#/manuscript/{{ $review->manuscript_record_id }}/reviews) | {{ $review->decision_expected_by->locale('fr_CA')->isoFormat('LL') }} | {{ $review->created_at->locale('fr_CA')->diffForHumans() }} |
@endforeach
</x-mail::table>

Veuillez cliquer sur le bouton ci-dessous pour voir toutes vos révisions de gestion en attente.

<x-mail::button :url="config('app.frontend_url').'#/my-reviews'">
Voir mes révisions
</x-mail::button>

<x-email.regards locale="fr" />

</x-mail::message>
