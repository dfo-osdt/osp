<?php

namespace App\Exports;

use App\Models\Publication;
use App\Queries\PublicationListQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PublicationAuthorsSheet implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private readonly PublicationListQuery $query) {}

    public function title(): string
    {
        return 'Authors / Auteurs';
    }

    public function query(): Builder
    {
        return $this->query
            ->with([
                'publicationAuthors.author',
                'publicationAuthors.organization',
            ]);
    }

    public function headings(): array
    {
        return [
            'Publication Title / Titre de la publication',
            'Published On / Date de publication',
            'Author / Auteur',
            'ORCID',
            'Organization / Organisation',
            'ROR',
            'Corresponding Author / Auteur correspondant',
        ];
    }

    /** @param Publication $publication */
    public function map($publication): array
    {
        return $publication->publicationAuthors
            ->map(fn ($pa): array => [
                $publication->title,
                $publication->published_on?->format('Y-m-d'),
                $pa->author->apa_name,
                $pa->author->orcid,
                $pa->organization->name_en.' / '.$pa->organization->name_fr,
                $pa->organization->ror_identifier,
                $pa->is_corresponding_author ? 'Yes' : 'No',
            ])
            ->all();
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF006153']],
            ],
        ];
    }
}
