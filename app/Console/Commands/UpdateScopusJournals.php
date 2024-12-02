<?php

namespace App\Console\Commands;

use App\Models\Journal;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;
use Str;

/**
 * This command will take the a "Scopus Source List" excel file and update the
 * journals in the database with the information from the file. This file can
 * be found here: https://www.scopus.com/home.uri
 */
class UpdateScopusJournals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:update-scopus-journals {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the journals in the database with the information from the Scopus Source List file.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Make sure the file exists and is readable
        $file = $this->argument('file');
        if (! file_exists($file) || ! is_readable($file)) {
            $this->error("The file {$file} does not exist or is not readable.");

            return Command::FAILURE;
        }

        $reader = SimpleExcelReader::create($this->argument('file'))->fromSheet(1);
        $rows = $reader->getRows();

        // list of ASJC codes we want to import
        $asjcCodes = collect([
            1000, // Multidisciplinary
            1100, // General Agricultural and Biological Sciences
            1101, // Agricultural and Biological Sciences (miscellaneous)
            1104, // Aquatic Science
            1105, // Ecology, Evolution, Behavior and Systematics
            1300, // General Biochemistry, Genetics and Molecular Biology
            1301, // Biochemistry, Genetics and Molecular Biology (miscellaneous)
            1303, // Biochemistry
            1311, // Genetics
            1312, // Molecular Biology
            1600, // General Chemistry
            1601, // Chemistry (miscellaneous)
            1605, // Organic Chemistry
            1800, // General Decision Sciences
            1801, // Decision Sciences (miscellaneous)
            1803, // Management Science and Operations Research
            1900, // General Earth and Planetary Sciences
            1901, // Earth and Planetary Sciences (miscellaneous)
            1902, // Atmospheric Science
            1910, // Oceanography
            2300, // General Environmental Science
            2301, // Environmental Science (miscellaneous)
            2302, // Ecological Modeling
            2303, // Ecology
            2304, // Environmental Chemistry
            2306, // Global and Planetary Change
            2308, // Management, Monitoring, Policy and Law
            2310, // Pollution
            2312, // Water Science and Technology
            2404, // Microbiology
        ]);

        $actionRegister = [
            0 => [
                'text' => 'No action',
                'count' => 0,
            ],
            1 => [
                'text' => 'Updated',
                'count' => 0,
            ],

            2 => [
                'text' => 'Created',
                'count' => 0,
            ],
            3 => [
                'text' => 'Deleted - Inactive',
                'count' => 0,
            ],
        ];

        $start = now();
        // go through the rows, only import ones that have an ASJC code we want
        $rows->each(function ($row) use ($asjcCodes, &$actionRegister) {
            // 'All Science Journal Classification Codes (ASJC)' column uses the following
            // format '1703; 2614; 1404; 1803;' so we need to split it into an array of
            // integers and then check if any of them are in the list of ASJC codes we
            // want to import.

            $id = $row['Sourcerecord ID'];
            $journal = Journal::where('scopus_source_record_id', $id)->first();

            // make sure journal is active
            if ($row['Active or Inactive'] == 'Inactive') {
                if ($journal && $journal->publications()->count() == 0) {
                    $journal->delete();
                    $actionRegister[3]['count']++;
                    $this->info('['.$actionRegister[3]['text'].']: '.$row['Source Title']);
                }

                return;
            }

            // make sure journal publishes in English and/or French
            if (! Str::contains($row['Article Language in Source (Three-Letter ISO Language Codes)'], ['ENG', 'FRE'])) {
                return;
            }

            // make sure journal has an ASJC code we want to import
            $asjcCodesInRow = array_map('intval', explode(';', $row['All Science Journal Classification Codes (ASJC)']));

            if ($asjcCodes->intersect($asjcCodesInRow)->isNotEmpty()) {
                $title = $row['Source Title'];
                $publisher = $row['Publisher Imprints Grouped to Main Publisher'];

                $issn = !empty($row['EISSN']) ? $row['EISSN'] : (!empty($row['ISSN']) ? $row['ISSN'] : null);
                if ($issn) {
                    $issn = $this->formatIssn($issn);
                }


                $action = 0;

                if ($journal) {
                    // update the title and publisher if they have changed
                    if ($journal->title != $title || $journal->publisher != $publisher || $journal->issn != $issn) {
                        $journal->title = $title;
                        $journal->publisher = $publisher;
                        $journal->issn = $issn;
                        $journal->save();
                        $action = 1;
                    }
                } else {
                    // create a new journal
                    Journal::forceCreate([
                        'title' => $title,
                        'publisher' => $publisher,
                        'scopus_source_record_id' => $id,
                        'issn' => $issn,
                    ]);
                    $action = 2;
                }

                $actionRegister[$action]['count']++;

                $this->info('['.$actionRegister[$action]['text'].']: '.$row['Source Title']);
            }
        });

        $time = now()->diffInSeconds($start);
        $this->line('');
        $this->info("Completed in {$time} seconds.");
        $this->table(['Action', 'Count'], $actionRegister);

        return Command::SUCCESS;
    }

    private function formatIssn(string $issn): string
    {
        return substr($issn, 0, 4).'-'.substr($issn, 4);
    }
}
