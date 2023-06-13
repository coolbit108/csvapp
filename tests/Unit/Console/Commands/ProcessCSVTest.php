<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use App\Console\Commands\ProcessCSV;

class ProcessCSVTest extends TestCase
{
    /**
     * Test the readCSV function.
     *
     * @return void
     */
    public function testReadCSV()
    {
        // Create a test CSV file with sample data
        $csvData = "name\nJohn Doe\nJane Smith";
        $file = 'test.csv';
        file_put_contents($file, $csvData);

        // Instantiate ProcessCSV command and call the readCSV function
        $processCSV = new ProcessCSV();
        $generator = $processCSV->handle()->readCSV($file);

        // Assert that the readCSV function returns the correct number of rows
        $batch1 = $generator->current();
        $this->assertCount(1, $batch1);
        $this->assertEquals('John Doe', $batch1[0]['name']);

        $generator->next();
        $batch2 = $generator->current();
        $this->assertCount(1, $batch2);
        $this->assertEquals('Jane Smith', $batch2[0]['name']);

        // Clean up the test CSV file
        unlink($file);
    }

    /**
     * Test the parseName function.
     *
     * @return void
     */
    public function testParseName()
    {
        // Instantiate ProcessCSV command
        $processCSV = new ProcessCSV();

        // Test case 1: "Mr John Smith"
        $name1 = 'Mr John Smith';
        $expected1 = [
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ];
        $this->assertEquals($expected1, $processCSV->handle()->parseName($name1));

        // Test case 2: "Mr and Mrs Smith"
        $name2 = 'Mr and Mrs Smith';
        $expected2 = [
            'title' => 'Mr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ];
        $this->assertEquals($expected2, $processCSV->handle()->parseName($name2));

        // Test case 3: "Mr J. Smith"
        $name3 = 'Mr J. Smith';
        $expected3 = [
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'J',
            'last_name' => 'Smith',
        ];
        $this->assertEquals($expected3, $processCSV->handle()->parseName($name3));
    }
}
