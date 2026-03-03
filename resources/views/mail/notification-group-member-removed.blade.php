<x-mail::message>
# Hello {{ $owner->first_name }},

*(le français suit)*

{{ $member->first_name }} {{ $member->last_name }} has removed themselves from your notification group. They will no longer receive copies of your management review notifications.

<x-email.regards locale="en" />

---

# Bonjour {{ $owner->first_name }},

{{ $member->first_name }} {{ $member->last_name }} s'est retiré(e) de votre groupe de notification. Cette personne ne recevra plus de copies de vos notifications de révision de gestion.

<x-email.regards locale="fr" />

</x-mail::message>
