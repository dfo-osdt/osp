<?php

namespace App\Exports;

use App\Models\Publication;
use App\Queries\PublicationListQuery;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PublicationsSheet implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private readonly PublicationListQuery $query) {}

    public function title(): string
    {
        return 'Publications';
    }

    public function query()
    {
        return $this->query->with('manuscriptRecord.functionalArea');
    }

    public function headings(): array
    {
        return [
            'Title / Titre',
            'Journal / Series / Série',
            'Publisher / Éditeur',
            'ISSN',
            'Year / Année',
            'Published On / Date de publication',
            'Accepted On / Date d\'acceptation',
            'Embargoed Until / Sous embargo jusqu\'au',
            'Open Access / Libre accès',
            'Status / Statut',
            'DOI',
            'ISBN',
            'Catalogue Number / Numéro de catalogue',
            'Region / Région',
            'Functional Area / Secteur fonctionnel',
            'Author Count / Nombre d\'auteurs',
        ];
    }

    /** @param Publication $publication */
    public function map($publication): array
    {
        return [
            $publication->title,
            $publication->journal?->title,
            $publication->journal?->publisher,
            $publication->journal?->issn ? trim($publication->journal->issn) : null,
            $publication->published_on?->format('Y'),
            $publication->published_on?->format('Y-m-d'),
            $publication->accepted_on?->format('Y-m-d'),
            $publication->embargoed_until?->format('Y-m-d'),
            $publication->is_open_access ? 'Yes' : 'No',
            $publication->status->value,
            $publication->doi,
            $publication->isbn,
            $publication->catalogue_number,
            $publication->region ? $publication->region->name_en.' / '.$publication->region->name_fr : null,
            $publication->manuscriptRecord?->functionalArea
                ? $publication->manuscriptRecord->functionalArea->name_en.' / '.$publication->manuscriptRecord->functionalArea->name_fr
                : null,
            $publication->publicationAuthors->count(),
        ];
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
