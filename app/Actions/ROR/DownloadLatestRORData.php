<?php

namespace App\Actions\ROR;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Process;

class DownloadLatestRORData
{
    public static function handle(callable $progressCallable = null): string | null
    {
        $url = 'https://zenodo.org/api/records';

        $response = Http::get($url, [
            'communities' => 'ror-data',
            'sort' => 'mostrecent',
        ]);

        $data = $response->json();

        $url = $data['hits']['hits'][0]['files'][0]['links']['self'] ?? null;

        // Download files to temporary storage
        if(!$url) return null;

        // create a path to store the file
        $base_path = Storage::path('ror_data');

        // make sure the path exists
        if(!file_exists($base_path)) mkdir($base_path);

        $fileName = Str::afterLast($url, '/');
        $zipFile = $base_path . '/' . $fileName;

        // does the file already exist?
        if(!file_exists($zipFile)){
            // use curl to download file
            $command = "curl -o $zipFile $url";

            $update = is_callable($progressCallable);

            if($update) $progressCallable("Staring ROR download: ". $command . "...".PHP_EOL);
            $process = Process::timeout(120)->start($command, function($type, $buffer) use ($progressCallable) {
                if($type === 'stderr') {
                    $progressCallable("ERROR: " . $buffer);
                } else {
                    $progressCallable($buffer);
                }
            });


            if(!$process->wait()->successful()) return null;
            if($update) $progressCallable("Finished download: ". $zipFile.PHP_EOL);
        }

        // is Json file already extracted?
        $jsonFile = $base_path . '/' . Str::beforeLast($fileName, '.') .'.json';

        if(file_exists($jsonFile)) return $jsonFile;

        $command = "unzip -o $fileName";

        $result = Process::path($base_path)->start($command, function($type, $buffer) use ($progressCallable) {
            if($type === 'stderr') {
                $progressCallable("ERROR: " . $buffer);
            } else {
                $progressCallable($buffer);
            }
        });

        if($result->wait()->successful()) return $jsonFile;

        return null;
    }
}