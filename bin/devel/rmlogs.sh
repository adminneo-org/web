#!/bin/sh

# Root directory.
BASEDIR=$( cd `dirname $0`/../.. ; pwd )
cd "$BASEDIR"

rm -rf log/*.log
rm -rf log/*.html
