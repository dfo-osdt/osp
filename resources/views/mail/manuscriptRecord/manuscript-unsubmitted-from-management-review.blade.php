<x-mail::message>
# Hello {{ $user->first_name }},

*(le français suit)*

A manuscript record submission has been unsubmitted from the Manuscript Management Review queue by {{ $unsubmittedBy->fullName }} and returned to draft.

**Reason for unsubmission:**<br /> {{ $reason }}

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

Une soumission au registre du manuscrit a été retirée de la révision par la gestion par {{ $unsubmittedBy->fullName }} et remise à l'état de brouillon.

**Raison du retrait de la soumission :**<br /> {{ $reason }}

Une révision par la gestion du manuscrit n'est plus requise pour ce dossier de manuscrit. Si vous avez des questions, veuillez communiquer avec l'auteur.

<x-mail::panel>
# Résumé du manuscrit

**Titre de travail :**<br /> {{ $manuscriptRecord->title }}

**Auteur(s) :**<br /> {{ $manuscriptRecord->manuscriptAuthors->implode('author.apaName', '; ') }}

**Résumé :**<br />{!! $manuscriptRecord->abstract !!}
</x-mail::panel>

<x-email.regards locale="fr" />

</x-mail::message>
