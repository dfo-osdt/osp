<x-mail::message>
    # Hello {{ $user->first_name }},

    We regret to inform you that the manuscript record titled, *{{ $manuscriptRecord->title }}*, has been withheld for publication.

    For more information, please review the management review tab of your manuscript record in the Open Science Portal or contact your Division Manager.

    <x-mail::button :url="config('app.frontend_url').'#/auth/login?redirect=/manuscript/'.$manuscriptRecord->id.'/reviews'">
        See Manuscript Record
    </x-mail::button>

    ## Relevant Information from the Publication Policy
    At no time, will the inclusion of sensitive material (e.g. data, scientific conclusions) prevent publication of scientific papers.

    In the rare case where a manuscript does not fully adhere to policies and legislation relevant to scientific publications, the Division
    Manager discusses potential revisions with the author(s). If after that discussion, the Division Manager still has concerns, he/she will identify
    these concerns to the Regional Director of Science who makes the final determination. The Regional Director of Science reserves the right to withhold
    permission to publish but only in cases where the publication is not in compliance with the following legislation or policy:

    > The Public Servants Inventions Act, Financial Administration Act(section 34), Surplus Crown Assets Act, and with respect to collaborating authors,
    > the Access to Information Act, the Copyright Act and the Privacy Act, as well as the Treasury Board Secretariat policy on Communications and Federal Identity,
    > DFO policies on Intellectual Property and Collaborative Agreements, the SP and RE Collective Agreements, and the Career Progression Management Framework for
    > Federal Researchers.


    Thank you,<br>
    {{ config('app.name') }}
</x-mail::message>