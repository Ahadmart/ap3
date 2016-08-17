#!/bin/bash

export db=ahadpos3
mysql -e"DROP SCHEMA IF EXISTS $db; CREATE SCHEMA $db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"
protected/yiic migrate
