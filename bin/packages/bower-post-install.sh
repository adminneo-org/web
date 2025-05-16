#!/bin/sh

# Root directory.
BASEDIR=$( cd `dirname $0`/../.. ; pwd )
cd "$BASEDIR"

rm -Rf vendor_components/nette.ajax.js
cp -R vendor_bower/nette.ajax.js vendor_components/nette.ajax.js
