#!/bin/bash

echo "Enter the commit message: "
read commitMessage
echo "Enter the SOURCE_DIR: "
read SOURCE_DIR
echo "Enter the TARGET_DIR: "
read TARGET_DIR
rm -rf $TARGET_DIR \
&& mkdir -m 750 $TARGET_DIR \
&& cp -af $SOURCE_DIR $TARGET_DIR \
&& git status \
&& git add . \
&& git commit -m $commitMessage \
&& git push -f

echo "Build package is done"