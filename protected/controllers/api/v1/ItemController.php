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

        $data = Barang::model()->getApiList($from, $to);
        $info = $this->storeInfo();
        $data = [
            'sukses' => true,
            'toko' => $info['toko'],
            'database' => $info['database'],
            'engine' => $info['engine'],
            'from' => $from,
            'to' => $to,
            'jumlah' => count($data),
            'data' => $data,
        ];
        $this->renderJSON($data);
    }
}
