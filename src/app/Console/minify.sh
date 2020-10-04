#!/bin/bash
cd "$(dirname "$0")" || exit 1

cd ../webroot/css/ || exit 1

cat bootstrap.css jquery.reject.css whyjustrun.css | cleancss -o main-minified.css

cd ../js && r.js -o build.js
