<?php
if (isset($_POST['install'])):
    ?>
    <p>Fresh Install Process..</p>
    <p>Droping Database <?php echo $_POST['db']; ?>..</p>
    <?php
    $passwd = '';
    if (isset($_POST['passwd'])) {
        $passwd = "-p{$_POST['passwd']}";
    }
    exec("mysql -uroot {$passwd} -e'drop database {$_POST['db']}'");
    ?>Database Dropped!
    <p>Creating Database <?php echo $_POST['db']; ?>..</p>
    <?php
    exec("mysql -uroot {$passwd} -e'CREATE DATABASE IF NOT EXISTS `{$_POST['db']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci'");
    ?>Database Created!
    <p>Migrating Database..</p>
    <?php
    $output = '';
    exec('protected/yiic migrate', $output);
    foreach ($output as $row) {
        echo $row . '<br />';
    }
    ?>
    <?php
else:
    ?>
    <form method="POST">
        <fieldset>
            <legend>
                MySql Configuration:
            </legend>
            <label>HOST: <input type="text" name="host" value="localhost" /></label><br />
            <label>DB: <input type="text" name="db" value="ahadpos3" /></label><br />
            <label>User: <input type="text" name="user" value="root" /></label><br />
            <label>Password: <input type="text" name="passwd" /></label>
        </fieldset>
        <input type="submit" name="install" value="Install"/>
    </form>
<?php
endif;
