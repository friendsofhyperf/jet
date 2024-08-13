#!/bin/sh

SCRIPT_PATH=$(dirname $(realpath $0))
BASE_PATH=$(dirname ${SCRIPT_PATH})
AUTOLOAD=${BASE_PATH}/autoload.php
NAMESPACE="FriendsOfHyperf\\Jet\\"
LOADER="JetClassLoader"$(openssl rand -hex 12)
# echo $LOADER
# exit 0

cd $BASE_PATH
# echo $BASE_PATH
# echo $(pwd)
# exit 0

cat <<EOT > ${AUTOLOAD}
<?php

/*
 * This file is part of friendsofhyperf/jet.
 *
 * @link     https://github.com/friendsofhyperf/jet
 * @document https://github.com/friendsofhyperf/jet/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

class ${LOADER}
{
    static \$registered = false;

    public static function register()
    {
        if (self::\$registered) {
            return;
        }

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

    echo "            ${CLASSMAP}" >> ${AUTOLOAD}

done

cat <<EOT >> ${AUTOLOAD}
        );

        spl_autoload_register(function (\$class) use (\$classMap) {
            if (isset(\$classMap[\$class]) && is_file(\$classMap[\$class])) {
                require_once \$classMap[\$class];
            }
        });

        self::\$registered = true;
    }
}

${LOADER}::register();
EOT

echo "done!"

exit 0