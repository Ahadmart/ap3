<!DOCTYPE html>
<html>
    <head>
        <style>
            h1 {
                margin-top: 10px;
                font-size: 3.5rem;
                text-align: center;
                color: #fff;
                font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-style: normal;
                font-weight: 300;;
            }
            body {
                background-color : #0D47A1;
            }
        </style>
        <script type="text/javascript">
            function printStruk(text) {
                var printWindow = window.open();
                printWindow.document.open('text/plain')
                printWindow.document.write(text);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
            setTimeout(
                    function () {
                        self.close();
                        window.history.go(-1);
                    },
                    3000);
        </script>
    </head>
    <body onload="printStruk(document.getElementsByTagName ('pre')[0].firstChild.data)">        
        <h1>printing..</h1> 
        <div style="background-color: #fff; color: #000; text-align: center; padding: 5px 0;">
            <div style="display: inline-block; text-align: left">
                <pre><?php echo $text; ?></pre>
            </div>
        </div>
    </body>
</html>