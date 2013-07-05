#!/bin/bash
# Simple script to rename "MarkMx" namespace and "Skel" bundle to user's choice.
# Note. If run after composer install suggest first run:
#       php app/console cache:clear --no-optional-warmers --no-warmup
#
# Usage: name-skeleton.sh <namespace> <bundle-name>
#
# Example: name-skeleton.sh Xyz HyperBlog

newNamespace=$1
newBundleName=$2

newNamespaceLc=$(echo $newNamespace | awk '{print tolower($newNamespace)}')
newBundleNameLc=$(echo $newBundleName | awk '{print tolower($newBundleName)}')

sed -i s/skel/${newBundleNameLc}/g src/MarkMx/SkelBundle/Twig/Extension/DemoExtension.php

find src web app -type f | xargs sed -i s/MarkMx/${newNamespace}/g
find src web app -type f | xargs sed -i s/markmx/${newNamespaceLc}/g

find src web app -type f | xargs sed -i s/SkelBundle/${newBundleName}Bundle/g
find src -type f | xargs sed -i s/\.skel/.${newBundleNameLc}/g

mv src/MarkMx/SkelBundle/MarkMxSkelBundle.php src/MarkMx/SkelBundle/${newNamespace}${newBundleName}Bundle.php
mv src/MarkMx/SkelBundle src/MarkMx/${newBundleName}Bundle
mv src/MarkMx src/$newNamespace
find src -type f -name \*MarkMx\* | rename s/MarkMx/$newNamespace/
[ -d web/bundles/markmxskel ] && mv web/bundles/markmxskel web/bundles/${newNamespaceLc}${newBundleNameLc}

echo "MarkMx namespace renamed to ${newNamespace}"
echo "Skel bundle renamed to ${newBundleName}"

exit
