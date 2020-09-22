<!DOCTYPE html>
<html>
<?php
/**
 * https://github.com/Ahadmart/ap3/issues/24
 * oleh: https://github.com/myzahron
 */
?>

<head>
    <style type="text/css">
        @media print {
            #printable {
                display: block;
            }

            #nonPrintable {
                display: none;
            }
        }

        h1 {
            font-size: 3.5rem;
            color: #fff;
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 300;
        }

        body {
            background-color: #0D47A1;
        }
    </style>
    <script>
        function printMe() {
            window.print();
        }
        setTimeout(
            function() {
                self.close();
                window.history.go(-1);
            },
            3000);
    </script>
</head>

<body onload="printMe()">
    <div id="nonPrintable">
        <h1>printing..</h1>
    </div>
    <div style="background-color: #fff; color: #000;" id="printable">
        <div style="display: inline-block; text-align: left">
            <pre><b><?php echo $text; ?></b></pre>
        </div>
    </div>
</body>

</html>