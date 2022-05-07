#!/bin/bash

echo "Hello Welcame to the buildpackage.sh by author:lazyb0yyy "


git status \
&& git add . \
&& git commit -m "$1" \
&& git push -f

echo "Build package is done"