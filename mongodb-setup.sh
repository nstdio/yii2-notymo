#!/bin/sh -e
#
# install mongodb

# MongoDB Server :

echo "MongoDB Server version:"
mongod --version


mongo notymo_test_db --eval 'db.createUser({user: "travis", pwd: "test", roles: ["readWrite", "dbAdmin"]});'

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
cat /sys/kernel/mm/transparent_hugepage/defrag
cat /sys/kernel/mm/transparent_hugepage/enabled