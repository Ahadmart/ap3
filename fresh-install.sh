export db=ahadpos32
mysql -uroot -e"DROP SCHEMA IF EXISTS $db; CREATE SCHEMA $db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"
protected/yiic migrate

