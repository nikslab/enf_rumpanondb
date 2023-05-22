#!/usr/bin/php
<?php

require './rumpanondb-config.php';

logThis(1, "rumpanondb.php running --->");
$start_time = microtime(true);

// Read the table names to be dumped from the file into an array
$tables = file($tables_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($tables === false) {
    logThis(-1, "Error reading table names from file $tables_file");
}

// Concat myanon-top.cfg and myanon-apollo.cfg => myanon.cfg
$file1_contents = file_get_contents("$directory/myanon-top.cfg");
$file2_contents = file_get_contents("$directory/myanon-apollo.cfg");
$combined_contents = $file1_contents . $file2_contents;
file_put_contents("$directory/myanon.cfg", $combined_contents);

// Construct the mysqldump command with a pipe to myanon
$command = "mysqldump -u $db_user -p$db_pass $db_name " . implode(' ', $tables);
$pipe = " 2> /dev/null | $myanon_cmd -f $directory/myanon.cfg > $directory/$output_file 2> /dev/null";
logThis(4, "$command");
logThis(4, "$pipe");

// Execute the command and display the output
shell_exec($command.$pipe);

// Check if anonymization is complete
checkAnonymization("$directory/$output_file");

$end_time = microtime(true);
$execution_time = round($end_time - $start_time, 2);
logThis(1, "Execution time: $execution_time seconds");

logThis(1, "<--- rumpanondb.php done");


/* FUNCTIONS */

function checkAnonymization($filename) {
    $f = fopen($filename, 'r');
    if ($f === false) {
        logThis(-1, "Could not open rump dump file $filename\n");
    }

    $cursor = -1;
    fseek($f, $cursor, SEEK_END);
    $char = fgetc($f);

    $lastLine = '';

    // Read backwards until we get to the start of the file or a new line
    while ($char === "\n" || $char === "\r") {
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }

    // Read until the start of the file or first newline character
    while ($char !== false && $char !== "\n" && $char !== "\r") {
        // Prepend the new character
        $lastLine = $char . $lastLine;
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }

    fclose($f);

    // Extract the number from the last line using a regex
    if (preg_match('/(\d+)/', $lastLine, $matches)) {
        $number = intval($matches[1]);

        // Check if the number is greater than 0
        if ($number > 0) {
            logThis(1, "$number anonymizations done");
        } else {
            logThis(1, "Dump not anonymized! Could be a problem with the myanon config file!");
        }
    } else {
        logThis(1, "Dump not anonymized! Could be a problem with the myanon config file!");
    }
}

function logThis($level, $msg) {
    global $LOG_FILE, $LOG_LEVEL, $LOG_PRINT;

    $script = basename($_SERVER['PHP_SELF'], '.php');

    if ($level <= $LOG_LEVEL) {
        $now = date('Y-m-d H:i:s');
        $entry = "$now $script [$level] $msg\n";
        if ($level < 1) {
            $entry .= "$now $script [$level] ...exiting\n";
        }    
        $fp = fopen($LOG_FILE, 'a');
        fwrite($fp, $entry);
        fclose($fp);
        if ( ($LOG_PRINT) || ($level < 1) ) {
            print "$entry";
        }
    }
    if ($level < 1) {
        exit(0);
    }
}

?>