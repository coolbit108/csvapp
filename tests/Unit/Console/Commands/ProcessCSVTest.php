<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ProcessCSV;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCSVTest extends TestCase
{

    /**
     * Test the ProcessCSV command with "Mr John Smith" input.
     *
     * @return void
     */
    public function testProcessCSVWithMrJohnSmithInput()
    {
        // Arrange
        $filename = 'test.csv';
        $csvData = "name\nMr John Smith";
        Storage::disk('local')->put($filename, $csvData);

        // Act
        Artisan::call('csv:process', ['file' => $filename]);
        $output = Artisan::output();

        // Assert
        $expectedOutput = [
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => NULL,
            'last_name' => 'Smith',
        ];
        $this->assertStringContainsString(json_encode($expectedOutput), $output);
    }

    /**
     * Test the ProcessCSV command with "Mr and Mrs Smith" input.
     *
     * @return void
     */
    public function testProcessCSVWithMrAndMrsSmithInput()
    {
        // Arrange
        $filename = 'test.csv';
        $csvData = "name\nMr and Mrs Smith";
        Storage::disk('local')->put($filename, $csvData);

        // Act
        Artisan::call('csv:process', ['file' => $filename]);
        $output = Artisan::output();

        // Assert
        $expectedOutput1 = [
            'title' => 'Mr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ];

        $this->assertStringContainsString(json_encode($expectedOutput1), $output);
    }

    /**
     * Test the ProcessCSV command with "Mr J. Smith" input.
     *
     * @return void
     */
    public function testProcessCSVWithMrJSmithInput()
    {
        // Arrange
        $filename = 'test.csv';
        $csvData = "name\nMr J Smith";
        Storage::disk('local')->put($filename, $csvData);

        // Act
        Artisan::call('csv:process', ['file' => $filename]);
        $output = Artisan::output();

        // Assert
        $expectedOutput = [
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'J',
            'last_name' => 'Smith',
        ];
        $this->assertStringContainsString(json_encode($expectedOutput,true), $output);
    }
}
