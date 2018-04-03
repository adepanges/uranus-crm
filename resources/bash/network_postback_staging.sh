#!/bin/bash
counter=1
until [ $counter -gt 12 ]
do
php /srv/users/serverpilot/apps/admincreathinkernet/cron.php network postback send
sleep 5
((counter++))
done
echo All done
