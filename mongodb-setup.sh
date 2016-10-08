#!/bin/sh -e
#
# install mongodb

# MongoDB Server :

echo "MongoDB Server version:"
mongod --version

### BEGIN INIT INFO
# Provides:          disable-transparent-hugepages
# Required-Start:    $local_fs
# Required-Stop:
# X-Start-Before:    mongod mongodb-mms-automation-agent
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Disable Linux transparent huge pages
# Description:       Disable Linux transparent huge pages, to improve
#                    database performance.
### END INIT INFO

case $1 in
  start)
    if [ -d /sys/kernel/mm/transparent_hugepage ]; then
      thp_path=/sys/kernel/mm/transparent_hugepage
    elif [ -d /sys/kernel/mm/redhat_transparent_hugepage ]; then
      thp_path=/sys/kernel/mm/redhat_transparent_hugepage
    else
      return 0
    fi

    echo 'never' > ${thp_path}/enabled
    echo 'never' > ${thp_path}/defrag

    unset thp_path
    ;;
esac

mongo notymo_test_db;

# PHP Extension :

if (php --version | grep -i HipHop > /dev/null); then
  echo "skip PHP extension installation on HHVM"
else
  pecl install -f mongodb
  echo "extension = mongodb.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
  echo "MongoDB PHP Extension version:"
  php -i |grep mongodb -4 |grep -2 version
fi

cat /etc/mongodb.conf