<?php

class ItemController extends BaseApiController
{
    public function actionIndex()
    {
        require __DIR__ . '/../../../vendor/autoload.php';

        $from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT);
        $to = filter_input(INPUT_GET, 'to', FILTER_VALIDATE_INT);

        if ($from === false || $to === false || $from === null || $to === null || $from < 1 || $to < $from) {
            $this->fail(400, 'Parameter from/to tidak valid');
        }

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');  // Yii::getPathOfAlias('application');
        $dotenv->load();
        $expectedKey = $_ENV['API_V1_KEY'];

        $givenKey = (string) ($_GET['apikey'] ?? '');
        if ($expectedKey === '' || !hash_equals($expectedKey, $givenKey)) {
            $this->fail(401, 'API key tidak valid');
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
