#!/bin/bash

# Start the cron service
service cron start

# Start Supervisor to manage queue workers
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf