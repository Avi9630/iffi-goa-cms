<?php

namespace App\Console\Commands;

use App\Models\CuratedSection as ModelsCuratedSection;
use App\Models\InternationalCinemaBasicDetail;
use Illuminate\Support\Facades\Storage;
use App\Models\InternationalCinema;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CuratedSection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:curated-section';
    protected $signature = 'curated:section {file=test.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for curated section';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $file = $this->argument('file');
    //     $csvFile = storage_path('/app/CSV/' . $file);
    //     if (!file_exists($csvFile)  || !is_readable($csvFile)) {
    //         $this->error("File not found: storage/app/{$file}");
    //         return Command::FAILURE;
    //     }
    //     if (($handle = fopen($csvFile, 'r')) === false) {
    //         $this->error("❌ Could not open file: {$csvFile}");
    //         return Command::FAILURE;
    //     }
    //     $header = null;
    //     $rowsProcessed = 0;

    //     try {
    //         while (($row = fgetcsv($handle)) !== false) {
    //             $row = array_map(function ($field) {
    //                 return $field !== null
    //                     ? mb_convert_encoding($field, 'UTF-8', 'UTF-8, ISO-8859-1, CP1252')
    //                     : null;
    //             }, $row);
    //             if (!$header) {
    //                 $header = $row;
    //                 continue;
    //             }

    //             $data = [
    //                 'section'         => $row[0] ?? null,
    //                 'title'           => $row[1] ?? null,
    //                 'original_title'  => $row[2] ?? null,
    //                 'country'         => $row[3] ?? null,
    //                 'production_year' => $row[4] ?? null,
    //                 'language'        => $row[5] ?? null,
    //                 'runtime'         => $row[6] ?? null,
    //                 'color'           => $row[7] ?? null,
    //                 'director'        => $row[8] ?? null,
    //                 'director_bio'    => $row[9] ?? null,
    //                 'producer'        => $row[10] ?? null,
    //                 'screenplay'      => $row[11] ?? null,
    //                 'dop'             => $row[12] ?? null,
    //                 'editor'          => $row[13] ?? null,
    //                 'cast'            => $row[14] ?? null,
    //                 'synopsis'        => $row[15] ?? null,
    //                 'premiere'        => $row[16] ?? null,
    //                 'award'           => $row[17] ?? null,
    //                 'festival_history' => $row[18] ?? null,
    //                 'trailer_link'    => $row[19] ?? null,
    //                 'tags'            => $row[20] ?? null,
    //                 'sales'           => $row[21] ?? null,
    //                 'instagram'       => $row[26] ?? null,
    //                 'twitter'         => $row[27] ?? null,
    //                 'facebook'        => $row[28] ?? null,
    //                 'award_year'      => $row[29] ?? null,
    //                 'co_screenplay'   => $row[30] ?? null,
    //                 'cinematographer' => $row[31] ?? null,
    //                 'producer_bio'    => $row[32] ?? null,
    //             ];

    //             $curated = CuratedSection::where('title', $data['section'])->first();
    //             dd($curated);
    //             if (!$curated) {
    //                 $this->warn("⚠️ Skipping: no curated section found for '{$data['section']}'");
    //                 continue;
    //             }

    //             $cinema = InternationalCinema::updateOrCreate(
    //                 [
    //                     'title' => $data['title'],
    //                     'award_year' => $data['award_year'],
    //                 ],
    //                 [
    //                     'curated_section_id' => $curated->id,
    //                     'slug' => str_replace(' ', '-', $data['title']),
    //                     'directed_by' => $data['director'],
    //                     'country_of_origin' => $data['country'],
    //                     'language' => $data['language'],
    //                     'year' => $data['production_year'],
    //                     'status' => 1,
    //                     'updated_at' => now(),
    //                     'created_at' => now(),
    //                 ],
    //             );

    //             InternationalCinemaBasicDetail::updateOrCreate(
    //                 [
    //                     'cinema_id' => $cinema->id,
    //                 ],
    //                 [
    //                     'director' => $data['director'],
    //                     'producer' => $data['producer'],
    //                     'screenplay' => $data['screenplay'],
    //                     'co_screenplay' => $data['co_screenplay'],
    //                     'cinematographer' => $data['cinematographer'],
    //                     'editor' => $data['editor'],
    //                     'cast' => $data['cast'],
    //                     'dop' => $data['dop'],
    //                     'other_details' => "{$data['runtime']} | {$data['color']} | {$data['country']}",
    //                     'synopsis' => $data['synopsis'],
    //                     'director_bio' => $data['director_bio'],
    //                     'producer_bio' => $data['producer_bio'],
    //                     'sales_agent' => $data['sales'],
    //                     'award' => $data['award'],
    //                     'trailer_link' => $data['trailer_link'],
    //                     'original_title' => $data['original_title'],
    //                     'premiere' => $data['premiere'],
    //                     'festival_history' => $data['festival_history'],
    //                     'link_trailer' => $data['trailer_link'],
    //                     'tags' => $data['tags'],
    //                     'instagram' => $data['instagram'],
    //                     'twitter' => $data['twitter'],
    //                     'facebook' => $data['facebook'],
    //                     'updated_at' => now(),
    //                     'created_at' => now(),
    //                 ],
    //             );
    //             $rowsProcessed++;
    //         }
    //         fclose($handle);
    //         $this->info("✅ CSV import completed. {$rowsProcessed} rows processed.");
    //         return Command::SUCCESS;
    //     } catch (\Exception $e) {
    //         $this->error("❌ Error: " . $e->getMessage());
    //         return Command::FAILURE;
    //     }
    // }

    public function handle()
    {
        $file = $this->argument('file');
        $csvFile = storage_path("app/CSV/{$file}");

        if (!is_readable($csvFile)) {
            $this->error("❌ File not found or unreadable: storage/app/CSV/{$file}");
            return Command::FAILURE;
        }

        if (($handle = fopen($csvFile, 'r')) === false) {
            $this->error("❌ Could not open file: {$csvFile}");
            return Command::FAILURE;
        }

        $header = null;
        $rowsProcessed = 0;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $row = array_map(fn($field) => $field !== null
                    ? mb_convert_encoding($field, 'UTF-8', 'UTF-8, ISO-8859-1, CP1252')
                    : null, $row);

                if (!$header) {
                    $header = $row;
                    continue;
                }

                $data = $this->mapRowToData($row);

                $curated = ModelsCuratedSection::where('title', $data['section'])->first();
                
                if (!$curated) {
                    $this->warn("⚠️ Skipping: no curated section found for '{$data['section']}'");
                    continue;
                }

                $cinema = InternationalCinema::updateOrCreate(
                    ['title' => $data['title'], 'award_year' => $data['award_year']],
                    [
                        'curated_section_id' => $curated->id,
                        'slug' => Str::slug($data['title']),
                        'directed_by' => $data['director'],
                        'country_of_origin' => $data['country'],
                        'language' => $data['language'],
                        'year' => $data['production_year'],
                        'status' => 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                InternationalCinemaBasicDetail::updateOrCreate(
                    ['cinema_id' => $cinema->id],
                    [
                        'director' => $data['director'],
                        'producer' => $data['producer'],
                        'screenplay' => $data['screenplay'],
                        'co_screenplay' => $data['co_screenplay'],
                        'cinematographer' => $data['cinematographer'],
                        'editor' => $data['editor'],
                        'cast' => $data['cast'],
                        'dop' => $data['dop'],
                        'other_details' => "{$data['runtime']} | {$data['color']} | {$data['country']}",
                        'synopsis' => $data['synopsis'],
                        'director_bio' => $data['director_bio'],
                        'producer_bio' => $data['producer_bio'],
                        'sales_agent' => $data['sales'],
                        'award' => $data['award'],
                        'trailer_link' => $data['trailer_link'],
                        'original_title' => $data['original_title'],
                        'premiere' => $data['premiere'],
                        'festival_history' => $data['festival_history'],
                        'link_trailer' => $data['trailer_link'],
                        'tags' => $data['tags'],
                        'instagram' => $data['instagram'],
                        'twitter' => $data['twitter'],
                        'facebook' => $data['facebook'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                $rowsProcessed++;
            }

            fclose($handle);
            $this->info("✅ CSV import completed. {$rowsProcessed} rows processed.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function mapRowToData(array $row): array
    {
        return [
            'section'         => $row[0] ?? null,
            'title'           => $row[1] ?? null,
            'original_title'  => $row[2] ?? null,
            'country'         => $row[3] ?? null,
            'production_year' => $row[4] ?? null,
            'language'        => $row[5] ?? null,
            'runtime'         => $row[6] ?? null,
            'color'           => $row[7] ?? null,
            'director'        => $row[8] ?? null,
            'director_bio'    => $row[9] ?? null,
            'producer'        => $row[10] ?? null,
            'screenplay'      => $row[11] ?? null,
            'dop'             => $row[12] ?? null,
            'editor'          => $row[13] ?? null,
            'cast'            => $row[14] ?? null,
            'synopsis'        => $row[15] ?? null,
            'premiere'        => $row[16] ?? null,
            'award'           => $row[17] ?? null,
            'festival_history' => $row[18] ?? null,
            'trailer_link'    => $row[19] ?? null,
            'tags'            => $row[20] ?? null,
            'sales'           => $row[21] ?? null,
            'instagram'       => $row[26] ?? null,
            'twitter'         => $row[27] ?? null,
            'facebook'        => $row[28] ?? null,
            'award_year'      => $row[29] ?? null,
            'co_screenplay'   => $row[30] ?? null,
            'cinematographer' => $row[31] ?? null,
            'producer_bio'    => $row[32] ?? null,
        ];
    }
}
