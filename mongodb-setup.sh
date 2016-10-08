#!/bin/sh -e
#
# install mongodb

# MongoDB Server :

echo "MongoDB Server version:"
mongod --version

if test -f /sys/kernel/mm/transparent_hugepage/defrag; then
echo never > /sys/kernel/mm/transparent_hugepage/defrag
fi
if test -f /sys/kernel/mm/transparent_hugepage/enabled; then
echo never > /sys/kernel/mm/transparent_hugepage/enabled
fi

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