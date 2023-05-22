# enf_rumpanondb
Creates a rump dump of a database (only some tables) and anonymizes it using myanon
# Installation

0. Clone repo
1. Install myanon https://github.com/ppomes/myanon
2. cp myanon-top-example.cfg myanon-top.cfg
3. Edit myanon-top.cfg as needed
4. cp rumpanondb-config-example.php rumpanondb-config.php
5. Edit rumpanondb-config.php as needed

# Running
Run as a cron job as /path/to/rumpanondb.php

# Output
Output of this script is a rump and anonimized mysqldump. It needs to be moved to a query server and re/imported into query database.
# Logging
There is a log in rumpanondb.log