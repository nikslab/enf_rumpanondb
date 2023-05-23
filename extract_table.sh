#!/bin/bash

# Check if the required parameters are provided
if [ $# -lt 2 ]; then
    echo "Usage: $0 <mysqldump_file> <table_name>"
    exit 1
fi

# MySQL database dump file path
dump_file=$1

# Table name to extract
table_name=$2

# Extract table structure and data to standard output
sed -n "/-- Table structure for table \`$table_name\`/,/^-- Table structure for table/p" "$dump_file" | grep -v 
'^--' | grep -v '^$'
