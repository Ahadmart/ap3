<?php
/* @var $this CekhargaController */

$this->breadcrumbs = array(
    'Cekharga',
);
?>
<div id="field">
    <div id="text1">
    <h1>Cek Harga</h1>
    <h5>Touch to start</h5>
    </div>
    <?php
    ?>
    <div id="text2">
        <span><?php echo $namaToko; ?></span>
    </div>
</div>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/foundation.min.js"></script>
<script>
    $(document).foundation();

    $("body").click(function () {
        $('body').css({"background-color": "#1a4f95"});
        $('body').html('<h4 style="text-align:center;font-size: 3.5rem;padding-top: 5rem;">Loading..</h4>');
        window.location.replace("<?php echo $this->createUrl('/cekharga'); ?>");
    });
</script>

<script>

  // Original JavaScript code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

  var field = document.getElementById("field");
  var text1 = document.getElementById("text1");
  var text2 = document.getElementById("text2");

  var maxX = field.clientWidth + text1.offsetWidth;
  var maxY = field.clientHeight - text1.offsetHeight;

  var duration = 15; // seconds
  var gridSize = 99; // pixels

  var start = null;

  function step(timestamp)
  {
    var progress, x, y, y2;
    if(start === null) start = timestamp;

    progress = (timestamp - start) / duration / 1000; // percent

    x = progress * maxX/gridSize; // x = ƒ(t)
    y = 2 * Math.cos(x); // y = ƒ(x)
    y2 = 2 * Math.sin(x);

    text1.style.left = text2.style.left = Math.min(maxX, gridSize * x) + "px";
    text1.style.bottom = maxY/2 + (gridSize * y) + "px";
    text2.style.bottom = maxY/2 + (gridSize * y2) + "px";

    if(progress >= 1) start = null; // reset to start position
    requestAnimationFrame(step);
  }

  requestAnimationFrame(step);

</script>
