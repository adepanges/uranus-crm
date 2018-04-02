#!/bin/bash
counter=1
until [ $counter -gt 12 ]
do
php /mnt/d/www/dermeva/cron.php network postback send
sleep 5
((counter++))
done
echo All done
