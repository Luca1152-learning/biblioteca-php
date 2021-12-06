<?php
    function connect_database()
    {
        // Sursa: https://www.doabledanny.com/Deploy-PHP-And-MySQL-to-Heroku
        // Get Heroku ClearDB connection information
        $cleardb_url = parse_url("mysql://b9960eb5b31032:58fe0ff1@eu-cdbr-west-01.cleardb.com/heroku_b52a27f697a2c16?reconnect=true");
        $cleardb_server = $cleardb_url["host"];
        $cleardb_username = $cleardb_url["user"];
        $cleardb_password = $cleardb_url["pass"];
        $cleardb_db = substr($cleardb_url["path"], 1);
        $active_group = 'default';
        $query_builder = TRUE;

        // Connect to DB
        $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

        return $conn;
    }

?>
