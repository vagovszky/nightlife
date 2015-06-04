#!/bin/bash
THISDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PARENTDIR=$(dirname $THISDIR)
LOGDIR="$PARENTDIR/data/logs/"
PHP=$(which php)

mkdir -p $LOGDIR

cd $PARENTDIR
$PHP index.php parse >> "$LOGDIR/parse.log" 2>&1
$PHP index.php xml >> "$LOGDIR/xml.log" 2>&1
$PHP index.php mail >> "$LOGDIR/mail.log" 2>&1