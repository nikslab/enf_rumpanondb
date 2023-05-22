# enf_rumpanondb
Creates a rump dump of a mysql database (only some tables) and anonymizes it using myanon

## Installation

1. Clone repo
2. Install myanon: `https://github.com/ppomes/myanon`
3. Copy the example config: `cp myanon-top-example.cfg myanon-top.cfg`
4. Edit `myanon-top.cfg` as needed
5. Copy the example config: `cp rumpanondb-config-example.php rumpanondb-config.php`
6. Edit `rumpanondb-config.php` as needed

## Running
Run as a cron job: `/path/to/rumpanondb.php`

## Output
Output of this script is a rump and anonymized mysqldump. It needs to be moved to a query server and re/imported into query database.

## Logging
There is a log in `rumpanondb.log`
