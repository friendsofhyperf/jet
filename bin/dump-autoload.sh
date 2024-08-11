#!/bin/sh

SCRIPT_PATH=$(dirname $(realpath $0))
BASE_PATH=$(dirname ${SCRIPT_PATH})
AUTOLOAD=${BASE_PATH}/src/autoload.php
NAMESPACE="FriendsOfHyperf\\Jet\\"

cd $BASE_PATH
# echo $BASE_PATH
# echo $(pwd)
# exit 0

cat <<EOT > ${AUTOLOAD}
<?php

(function () {
    \$baseDir = realpath(__DIR__);
    \$classMap = array(
EOT

for FILE in `find ./src -type f -name "*.php"`; do
    # if the file path contains autoload.php, skip it
    if [[ ${FILE} == *autoload.php* ]]; then
        continue
    fi

    RELATIVE_PATH=`echo ${FILE} | sed -r "s/^\.\/src//"`
    BASENAME="${RELATIVE_PATH%.php}"
    CLASS="${NAMESPACE}${BASENAME//\//\\}"
    CLASSMAP="'${CLASS}' => \$baseDir . '${RELATIVE_PATH}',"

    echo "        ${CLASSMAP}" >> ${AUTOLOAD}

done

cat <<EOT >> ${AUTOLOAD}
    );

    spl_autoload_register(function (\$class) use (\$classMap) {
        if (isset(\$classMap[\$class]) && is_file(\$classMap[\$class])) {
            require_once \$classMap[\$class];
        }
    });
})();
EOT

echo "done!"

exit 0