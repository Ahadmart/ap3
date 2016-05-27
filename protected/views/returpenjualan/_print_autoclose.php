<!DOCTYPE html>
<html>
    <head>
        <script>
            setTimeout(
                    function () {
                        self.close();
                        window.history.go(-1);
                    },
                    5000
                    );
        </script>
    </head>
    <body style="background-color : #0D47A1;">
        <h1 style="
            margin-top: 10px;
            font-size: 3.5rem;
            text-align: center;
            color: #fff;
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 300;">printing..</h1>
        <div style="background-color: #fff; color: #000; text-align: center; padding: 5px 0;">
            <div style="display: inline-block;text-align: left">
                <pre><?php echo $text; ?></pre>
            </div>
        </div>
    </body>
</html>
