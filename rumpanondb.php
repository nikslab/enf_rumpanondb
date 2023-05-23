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

/*
* This is not happening because we are not mysql dumping
* Keeping this code in case that happens at some point
* Current arrangement is that we are working with a backup file in the XXX.sql.gz format
* which is scp-ed to the query server
*

// Construct the mysqldump command with a pipe to myanon
$command = "mysqldump -h $db_server -P $db_port -u $db_user -p$db_pass $db_name " . implode(' ', $tables);
$pipe = " 2> /dev/null | $myanon_cmd -f $directory/myanon.cfg > $directory/$output_file 2> /dev/null";
logThis(4, "$command");
logThis(4, "$pipe");

*/

// Construct the gunzip with a pipe to myanon
$backup_file = getNewestFile($backup_directory);
logThis(3, "Backup file is $backup_file");
$command = "$gunzip $backup_file";
logThis(3, "Executing: $command");
shell_exec($command);

$database_dump = getNewestFile($backup_directory);
$command = "/usr/bin/cat $database_dump";
$pipe = "| $myanon_cmd -f $directory/myanon.cfg > $directory/$output_file 2> /dev/null";
logThis(4, "Executing: $command.$pipe");

// Execute the command and display the output
shell_exec($command.$pipe);

// Check if anonymization is complete
checkAnonymization("$directory/$output_file");

$end_time = microtime(true);
$execution_time = round($end_time - $start_time, 2);
logThis(1, "Execution time: $execution_time seconds");

logThis(1, "<--- rumpanondb.php done");


/* FUNCTIONS */

function getNewestFile($directory) {
    // Get the list of files in the directory
    $files = scandir($directory);

    // Remove . and .. from the list
    $files = array_diff($files, array('.', '..'));

    // Sort the files based on modification date (newest first)
    usort($files, function($a, $b) use ($directory) {
        return filemtime($directory . '/' . $b) - filemtime($directory . '/' . $a);
    });

    // Return the full path of the newest file
    if (!empty($files)) {
        $newestFile = $directory . '/' . $files[0];
        return $newestFile;
    }

    return null; // Return null if no files found
}

// Usage example:
$directory = '/path/to/directory'; // Replace with the actual directory path
$newestFile = getNewestFile($directory);
if ($newestFile !== null) {
    echo 'Newest file: ' . $newestFile;
} else {
    echo 'No files found in the directory.';
}


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

        // Check if the number is greater than 100,000
        if ($number > 100000) {
            logThis(1, "$number anonymizations done");
            print "1\n";
        } else {
            logThis(1, "Dump not anonymized! Could be a problem with the myanon config file!");
            print "0\n";
        }
    } else {
        logThis(1, "Dump not anonymized! Could be a problem with the myanon config file!");
        print "0\n";
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