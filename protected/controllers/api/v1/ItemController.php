<?php

class ItemController extends BaseApiController
{
    public function actionIndex()
    {
        $from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT);
        $to = filter_input(INPUT_GET, 'to', FILTER_VALIDATE_INT);

        if ($from === false || $to === false || $from === null || $to === null || $from < 1 || $to < $from) {
            $this->fail(400, 'Parameter from/to tidak valid');
        }
        // $givenKey = (string) Yii::app()->request->getQuery('apikey', '');
        $data = Barang::model()->getApiList($from, $to);
        $data = [
            'sukses' => true,
            'from' => $from,
            'to' => $to,
            'jumlah' => count($data),
            'data' => $data,
        ];
        $this->renderJSON($data);
    }
}
