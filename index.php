<?php
/**function __autoload( $className ) {
    $className = str_replace( "..", "", $className );
    require_once( "classes/$className.php" );
    echo "Loaded classes/$className.php<br>";
}*/
$loader = require_once __DIR__.'/vendor/autoload.php';
use MyClasses\db_connection;
db_connection::connect_default();
?>
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <title>ShortLink</title>
    </head>
    <body>
        <div class="container">
            <div class="panel panel-default">
                <form method="POST" action="http://wlp.icescroll.ru/store.php" accept-charset="UTF-8">
                    <div class="form-group">
                        <label for="link">Пожалуйста, введите Вашу ссылку:</label>
                        <input class="form-control" name="link" type="text" id="link">
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary form-control" type="submit" value="Получить короткую ссылку">
                    </div>
                </form>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </body>
</html>
