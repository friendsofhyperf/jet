#!/bin/sh

SCRIPT_PATH=$(dirname $(realpath $0))
BASE_PATH=$(dirname ${SCRIPT_PATH})
BOOTSTRAP=${BASE_PATH}/src/bootstrap.php
NS="FriendsOfHyperf\\Jet\\"

cd $BASE_PATH
# echo $BASE_PATH
# echo $(pwd)
# exit 0

cat <<EOT > ${BOOTSTRAP}
<?php

\$baseDir = realpath(__DIR__);
\$classMap = array(
EOT

for FILE in `find ./src -type f -name "*.php"`; do
    if [ "${FILE}" == *bootstrap.php* ]; then
        continue
    fi

    REALPATH=`echo ${FILE} |sed -r "s/^\.\/src//"`
    BASENAME="${REALPATH%.php}"
    CLASS="${NS}${BASENAME//\//\\}"
    CLASSMAP="'${CLASS}' => \$baseDir . '${REALPATH}',"

    echo "    ${CLASSMAP}" >> ${BOOTSTRAP}

done

cat <<EOT >> ${BOOTSTRAP}
);

spl_autoload_register(function (\$class) use (\$classMap) {
    if (isset(\$classMap[\$class]) && is_file(\$classMap[\$class])) {
        require_once \$classMap[\$class];
    }
});
EOT

echo "done!"

exit 0