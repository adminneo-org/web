#!/bin/sh

set -e

# Root directory.
BASEDIR=$( cd `dirname $0`/.. ; pwd )
cd "$BASEDIR"

usage() {
    echo "Usage:"
    echo "  sh $0 [options]"
    echo ""
    echo "OPTIONS:"
    echo "  -v    Version: 5.0.0"
    echo "  -p    Project: admin, editor"
    echo "  -d    List of comma separated drivers"
    echo "  -l    List of comma separated languages"
    echo "  -t    List of comma separated themes"
    echo ""
    echo "EXAMPLES:"
    echo "  sh $0 -p admin"
    echo "  sh $0 -p admin -d mysql,pgsgl"
    echo "  sh $0 -p admin -l en,de,cs"
    echo "  sh $0 -p admin -t default"
    echo "  sh $0 -p admin -t default-blue,default-green"
    exit 1
}

PROJECT=admin
VERSION=5.0.0-rc
DRIVERS=
LANGUAGES=
THEMES=

# Read command line options.
while getopts p:v:d:l:t:h option
do
    case $option in
        v)
            VERSION="$OPTARG"
            ;;
        p)
            PROJECT="$OPTARG"
            ;;
        d)
            DRIVERS="$OPTARG"
            ;;
        l)
            LANGUAGES="$OPTARG"
            ;;
        t)
            THEMES="$OPTARG"
            ;;
        t)
            usage
            ;;
        *)
            usage
            ;;
    esac
done

# TODO version is required

if [ ! -d tmp/adminneo ]; then
    git clone https://github.com/adminneo-org/adminneo.git tmp/adminneo
fi

cd tmp/adminneo

# TODO only if tag is not known
git checkout main
git pull

# TODO only if we are in different tag
git checkout "v$VERSION"
composer install

php bin/compile.php $PROJECT $DRIVERS $LANGUAGES $THEMES -o compiled/${PROJECT}neo.php
