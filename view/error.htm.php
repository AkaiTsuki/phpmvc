<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Error</title>
        <style>
            .error{
                background:#FFFF99; 
                color:red;
                border:1px solid red;
                width:50%;
                margin: 100px auto;
                padding: 1em;
            }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>Error!</h1>
        <?php
            echo $errorMsg;
        ?>
            <p><a href="<?php echo SITE_ROOT_URL."/".$backUrl ?>">Back</a></p>
        </div>
        
    </body>
</html>
