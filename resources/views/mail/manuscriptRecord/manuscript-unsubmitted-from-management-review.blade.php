<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

A manuscript record submission from {{ $manuscriptRecord->user->fullname }} has been unsubmitted from the Manuscript Management Review queue and returned to draft.

A Manuscript Management Review is no longer expected for this manuscript record. If you have any questions, please contact the author.

<x-mail::panel>
# Manuscript Summary
**Working Title:**<br /> {{ $manuscriptRecord->title }}

**Author(s):**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Abstract:** <br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="en" />

---
<br />

# Bonjour {{ $user->first_name }},

Une soumission de dossier de manuscrit de {{ $manuscriptRecord->user->fullname }} a été retirée de la file d'attente de l'examen de gestion du manuscrit et remise à l'état de brouillon.

Un examen de gestion du manuscrit n'est plus requis pour ce dossier de manuscrit. Si vous avez des questions, veuillez communiquer avec l'auteur.

<x-mail::panel>
# Résumé du manuscrit

**Titre de travail :**<br /> {{ $manuscriptRecord->title }}

**Auteur(s) :**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé :**<br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="fr" />

</x-mail::message>