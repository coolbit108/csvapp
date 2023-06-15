<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProcessCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:process {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a CSV file and split the names into individual person records';

    /**
     * The batch size for processing CSV rows.
     *
     * @var int
     */
    private const BATCH_SIZE = 1000;

    /**
     * Handle the console command.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->argument('file');
        $handle = fopen(storage_path("app/{$file}"), 'r');

        $headers = fgetcsv($handle);
        $people = [];

        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($headers, $data);
            $names = explode('&', $row['name']);

            foreach ($names as $name) {
                $person = $this->parseName($name);
                $people[] = $person;

                if (count($people) >= self::BATCH_SIZE) {
                    $this->outputBatch($people);
                    $people = [];
                }
            }
        }

        if (count($people) > 0) {
            $this->outputBatch($people);
        }

        fclose($handle);
    }

    /**
     * Output a batch of people records.
     *
     * @param array $people
     * @return void
     */
    private function outputBatch(array $people)
    {
        foreach ($people as $person) {
            $this->line(json_encode($person));
        }
    }

    /**
     * Parse a name string into individual person fields.
     *
     * @param string $name
     * @return array
     */
    private function parseName($name)
    {
        $person = [
            'title' => null,
            'first_name' => null,
            'initial' => null,
            'last_name' => null,
        ];

        $nameParts = explode(' ', $name);
        $numParts = count($nameParts);
       // dd($nameParts);

        $person['title'] = $nameParts[0];

        if ($numParts >= 2 && $numParts < 4) {
            // Check if the second part is "and" or "&"
            if (!in_array(strtolower($nameParts[1]), ['and', '&'])) {
                if(strlen($nameParts[1]) == 1){
                    $person['initial'] =   $nameParts[1];
                }
                else{
                    $person['first_name'] = $nameParts[1];
                }
            }
        }
        if($numParts == 3) {
            $person['last_name'] = $nameParts[2];
        }
        if ($numParts > 3) {
                // Check if the third part is "and" or "& or MR or Miss or MS or MRS"
                if (!in_array(strtolower($nameParts[3]), ['and', '&','Mr','Mrs','Miss','Ms'])) {
                    $person['last_name'] = $nameParts[3];
                }
            }


        return $person;
    }
}
