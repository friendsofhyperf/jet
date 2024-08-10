#!/bin/sh

SCRIPT_PATH=$(dirname $(realpath $0))
BASE_PATH=$(dirname ${SCRIPT_PATH})
BOOTSTRAP=${BASE_PATH}/src/bootstrap.php
NAMESPACE="FriendsOfHyperf\\Jet\\"

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

    RELATIVE_PATH=`echo ${FILE} | sed -r "s/^\.\/src//"`
    BASENAME="${RELATIVE_PATH%.php}"
    CLASS="${NAMESPACE}${BASENAME//\//\\}"
    CLASSMAP="'${CLASS}' => \$baseDir . '${RELATIVE_PATH}',"

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