<?php

namespace App\Actions\ROR;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DownloadLatestRORData
{
    /**
     * Resolve the latest ROR data dump metadata from Zenodo without downloading it.
     *
     * @return array{url: string, version: string}|null
     */
    public static function latestVersion(): ?array
    {
        $response = Http::get('https://zenodo.org/api/records', [
            'communities' => 'ror-data',
            'sort' => 'mostrecent',
        ]);

        $data = $response->json();

        $url = $data['hits']['hits'][0]['files'][0]['links']['self'] ?? null;
        $version = $data['hits']['hits'][0]['metadata']['version'] ?? null;

        if (! $url || ! $version) {
            return null;
        }

        return [
            'url' => $url,
            'version' => $version,
        ];
    }

    /**
     * Download (streamed) and extract the latest ROR data dump.
     *
     * Pass an already-resolved {@see self::latestVersion()} result to avoid a
     * second Zenodo lookup; otherwise it is fetched here.
     *
     * @param  array{url: string, version: string}|null  $latest
     * @return array{jsonFile: string, version: string}|null
     */
    public static function handle(?callable $progressCallable = null, ?array $latest = null): ?array
    {
        $latest ??= self::latestVersion();

        if (! $latest) {
            return null;
        }

        ['url' => $url, 'version' => $version] = $latest;

        $update = is_callable($progressCallable);

        // create a path to store the file
        $basePath = Storage::path('ror_data');

        // make sure the path exists
        if (! file_exists($basePath)) {
            mkdir($basePath, recursive: true);
        }

        $fileName = Str::of($url)
            ->beforeLast('/')
            ->afterLast('/')
            ->toString();
        $zipFile = $basePath.'/'.$fileName;

        // does the zip already exist? if not, stream it to disk
        if (! file_exists($zipFile)) {
            if ($update) {
                $progressCallable('Starting ROR download...'.PHP_EOL);
            }

            $response = Http::timeout(600)
                ->withOptions([
                    // Guzzle streams progress as bytes; report a live percentage.
                    'progress' => function ($downloadTotal, $downloadedBytes) use ($progressCallable, $update): void {
                        if ($update && $downloadTotal > 0) {
                            $progressCallable("\rDownloading: ".round($downloadedBytes / $downloadTotal * 100).'%   ');
                        }
                    },
                ])
                ->sink($zipFile)
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            if ($update) {
                $progressCallable(PHP_EOL.'Finished download: '.$zipFile.PHP_EOL);
            }
        }

        // is the JSON file already extracted?
        $jsonFile = $basePath.'/'.Str::beforeLast($fileName, '.').'.json';

        if (! file_exists($jsonFile)) {
            if ($update) {
                $progressCallable('Extracting '.$fileName.'...'.PHP_EOL);
            }

            $zip = new ZipArchive;

            if ($zip->open($zipFile) !== true) {
                return null;
            }

            $zip->extractTo($basePath);
            $zip->close();
        }

        // extraction should have produced the JSON dump
        if (! is_file($jsonFile)) {
            return null;
        }

        return [
            'jsonFile' => $jsonFile,
            'version' => $version,
        ];
    }
}
