<?php

namespace App\Actions\ROR;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadLatestRORData
{
    public static function handle(?callable $progressCallable = null): ?array
    {
        $url = 'https://zenodo.org/api/records';

        $response = Http::get($url, [
            'communities' => 'ror-data',
            'sort' => 'mostrecent',
        ]);

        $data = $response->json();

        $url = $data['hits']['hits'][0]['files'][0]['links']['self'] ?? null;
        $version = $data['hits']['hits'][0]['metadata']['version'] ?? null;

        // Download files to temporary storage
        if (! $url) {
            return null;
        }

        // create a path to store the file
        $base_path = Storage::path('ror_data');

        // make sure the path exists
        if (! file_exists($base_path)) {
            mkdir($base_path);
        }

        $fileName = $fileName = Str::of($url)
            ->beforeLast("/")
            ->afterLast("/")
            ->toString();
        $zipFile = $base_path . '/' . $fileName;

        // does the file already exist?
        if (! file_exists($zipFile)) {
            // use curl to download file
            $command = "curl -o $zipFile $url";

            $update = is_callable($progressCallable);

            if ($update) {
                $progressCallable('Staring ROR download: ' . $command . '...' . PHP_EOL);
            }
            $process = Process::timeout(120)->start($command, function ($type, $buffer) use ($progressCallable) {
                if ($type === 'stderr') {
                    $progressCallable('ERROR: ' . $buffer);
                } else {
                    $progressCallable($buffer);
                }
            });

            if (! $process->wait()->successful()) {
                return null;
            }
            if ($update) {
                $progressCallable('Finished download: ' . $zipFile . PHP_EOL);
            }
        }

        // is Json file already extracted?
        $jsonFile = $base_path . '/' . Str::beforeLast($fileName, '.') . '_schema_v2.json';

        if (file_exists($jsonFile)) {
            return [
                'jsonFile' => $jsonFile,
                'version' => $version,
            ];
        }

        $command = "unzip -o $fileName";

        $result = Process::path($base_path)->start($command, function ($type, $buffer) use ($progressCallable) {
            if ($type === 'stderr') {
                $progressCallable('ERROR: ' . $buffer);
            } else {
                $progressCallable($buffer);
            }
        });

        if ($result->wait()->successful()) {
            return [
                'jsonFile' => $jsonFile,
                'version' => $version,
            ];
        }

        return null;
    }
}
