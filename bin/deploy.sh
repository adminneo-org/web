#!/bin/sh

set -e

# Root directory.
BASEDIR=$( cd `dirname $0`/.. ; pwd )
cd "$BASEDIR"

internal_chmod()
{
    perm=$1
    path=$2

    if [ "`uname`" = FreeBSD ]
        then
        # Call chmod only when the permissions are different.
        # This is needed on FreeBSD for filesystems with NFSv4 ACLs (ZFS) -
        # otherwise deploy script will fail if the user running the deploy
        # script is not the owner of the directories, even when there is no
        # need to change the permissions.
        statperm=` stat -f %p "$path" | sed 's/.*\(...\)$/\1/;s/^0*//' `
        if [ "$statperm" != "$perm" ]
        then
            chmod $perm $path
        fi
    else
        chmod $perm $path
    fi
}

internal_chmod 777 tmp
internal_chmod 777 log
internal_chmod 777 public/compiled
internal_chmod 777 public/files

rm -rf tmp/cache
rm -f public/compiled/*

composer install
