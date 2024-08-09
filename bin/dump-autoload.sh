#!/bin/sh

BOOTSTRAP=`dirname $(realpath $0)`/../src/bootstrap.php
NS="FriendsOfHyperf\\Jet\\"

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

    # replace / with \
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