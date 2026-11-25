<?php

class BaseApiController extends CController
{
    protected function beforeAction($action)
    {
        $this->requireApiKey();
        return parent::beforeAction($action);
    }

    protected function requireApiKey()
    {
        require Yii::getPathOfAlias('application') . '/vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(Yii::getPathOfAlias('application'));
        $dotenv->safeLoad();
        $expectedKey = isset($_ENV['API_V1_KEY']) ? $_ENV['API_V1_KEY'] : '';
        if ($expectedKey === '') {
            $this->fail(500, 'API key belum dikonfigurasi');
        }
        $givenKey = (string) Yii::app()->request->getQuery('apikey', '');
        if (!hash_equals($expectedKey, $givenKey)) {
            $this->fail(401, 'API key tidak valid');
        }
    }

    /**
     * Info sumber data: nama database, engine, dan nama toko.
     *
     * @return array
     */
    protected function storeInfo()
    {
        $row = Yii::app()->db->createCommand("
        SELECT
            DATABASE() AS `database`,
            VERSION() AS version,
            @@version_comment AS comment,
            (SELECT nilai FROM config WHERE nama = 'toko.nama' LIMIT 1) AS toko
    ")->queryRow();

        $haystack = strtolower(($row['version'] ?? '') . ' ' . ($row['comment'] ?? ''));

        if (strpos($haystack, 'mariadb') !== false) {
            $engine = 'MariaDB';
        } elseif (strpos($haystack, 'percona') !== false) {
            $engine = 'Percona Server';
        } elseif (strpos($haystack, 'aurora') !== false) {
            $engine = 'Amazon Aurora';
        } else {
            $engine = 'MySQL';
        }

        return [
            'database' => $row['database'],
            'engine' => $engine,
            'toko' => $row['toko'] !== null ? $row['toko'] : Yii::app()->name,
        ];
    }

    /**
     * Return data to browser as JSON and end application.
     * @param array $data
     */
    protected function renderJSON($data)
    {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        foreach (Yii::app()->log->routes as $route) {
            if ($route instanceof CWebLogRoute) {
                $route->enabled = false;  // disable any weblogroutes
            }
        }
        Yii::app()->end();
    }

    protected function fail($statusCode, $message)
    {
        http_response_code($statusCode);
        $this->renderJSON([
            'success' => false,
            'message' => $message
        ]);
    }
}
