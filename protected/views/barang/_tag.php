<h4><small>Update</small> Tags</h4>
<hr />
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/select2.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/select2.full.min.js', CClientScript::POS_HEAD);
?>

<?php
echo CHtml::dropDownList('tags', $curTags, CHtml::listData(Tag::model()->findAll(), 'id', 'nama'), ['class' => 'js-tags form-control', 'multiple' => 'multiple']);
?>

<script>
    $("select.js-tags").select2({
        tags: true
    });

    $('.js-tags').on('change', function (evt) {
        console.log("tags ganti!");
        console.log("data:" + $(".js-tags").val());
        var dataUrl = '<?= $this->createUrl('updatetags', ['id' => $barang->id]) ?>';
        var tags = $('.js-tags').val();
        $.ajax({
            url: dataUrl,
            type: 'post',
            data: {tags},
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        }).done(function (data) {
            //
        });
    });
</script>