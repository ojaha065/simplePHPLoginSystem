<?php
    if(!isset($_POST["hostname"],$_POST["port"],$_POST["databaseName"],$_POST["databaseUsername"],$_POST["databasePassword"])){
        die("One or more values were missing from the request.");
    }

    $host = $_POST["hostname"];
    $port = $_POST["port"];
    $databaseName = $_POST["databaseName"];
    $databaseUsername = $_POST["databaseUsername"];
    $databasePassword = $_POST["databasePassword"];

    $data = "<?php \$host=\"$host\";\$port=\"$port\";\$databaseName=\"$databaseName\";\$databaseUsername=\"$databaseUsername\";\$databasePassword=\"$databasePassword\"; ?>";

    $file = fopen("../utils/credentials.php","w") or die("File open failed. You might not have proper permissions.");
    fwrite($file,$data) or die("File write failed. You might not have proper permissions.");
    fclose($file);
?>