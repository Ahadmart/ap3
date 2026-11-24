<?php

class ItemController extends BaseApiController
{
    public function actionIndex()
    {
        require __DIR__ . '/../../../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(Yii::getPathOfAlias('application'));
        $dotenv->safeLoad();
        $expectedKey = isset($_ENV['API_V1_KEY']) ? $_ENV['API_V1_KEY'] : '';
        if ($expectedKey === '') {
            $this->fail(500, 'API key belum dikonfigurasi');
        }
        $givenKey = (string) ($_GET['apikey'] ?? '');
        if (!hash_equals($expectedKey, $givenKey)) {
            $this->fail(401, 'API key tidak valid');
        }

        $from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT);
        $to = filter_input(INPUT_GET, 'to', FILTER_VALIDATE_INT);

        if ($from === false || $to === false || $from === null || $to === null || $from < 1 || $to < $from) {
            $this->fail(400, 'Parameter from/to tidak valid');
        }

        $data = [
            'sukses' => true,
            'from' => $from,
            'to' => $to,
            'givenKey' => $givenKey,
            'expectedKey' => $expectedKey
        ];
        $this->renderJSON($data);
    }
}
