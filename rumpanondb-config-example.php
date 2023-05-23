<?php

/*
* Copy this to rumpanon-config.php and edit as needed
* rumpanondb-config.php does not go into the repo and is in .gitignore 
*/

# Working directory needed for cron
$directory = "/home/enf/enf_rumpanondb";

# Directory where database backup is stored in XXX.sql.gz format
$backup_directory = "/home/enf/database";

# Path to myanon
$gunzip = '/usr/bin/gunzip';

# Path to myanon
$myanon_cmd = '/usr/local/bin/myanon';

# Database info for the query database that gets the imports
$db_server = "localhost";
$db_port = '3306';
$db_name = 'apolloQ';
$db_user = 'username';
$db_pass = 'password';

# List of tables to be dumped
$tables_file = 'apollo-tables.config'; 

# myanon top of config file
$myanon_top = "myanon-top.cfg";

# myanon tables cfg
$myanon_cfg = "myanon.cfg";

# Rump and anonymized .sql file
$output_file = "rumpanondb.sql";

# Logging
$LOG_FILE  = "rumpanondb.log";
$LOG_LEVEL = 5; // 0=log nothing; 5=log a lot
$LOG_PRINT = true;

