# enf_rumpanondb
Creates a rump dump of a mysql database (only some tables) and anonymizes it using myanon

## Installation

1. Clone repo
2. Install myanon: [https://github.com/ppomes/myanon](https://github.com/ppomes/myanon)
3. Copy the example config: `cp myanon-top-example.cfg myanon-top.cfg`
4. Edit `myanon-top.cfg` as needed
5. Copy the example config: `cp rumpanondb-config-example.php rumpanondb-config.php`
6. Edit `rumpanondb-config.php` as needed

## Running
Run as a cron job: `/path/to/rumpanondb.php`

## Output
Output of this script is a rump and anonymized mysqldump. It needs to be moved to a query server and re/imported into query database.

Note that if database is successfully anonymized the script will return 1. If something goes wrong (database not anonymized: dangerous to put it online!) it will return 0.

On my computer the script takes about 10 seconds to run.

## Test
Run it on your own laptop, then import into a database on your laptop say into a database `rumpanon` with something like `mysql -u user -ppass rumpanon < rumpanondb.sql` and check that everything is anonymized properly and only some tables as in the database, as per config.

## Logging
There is a log in `rumpanondb.log`
