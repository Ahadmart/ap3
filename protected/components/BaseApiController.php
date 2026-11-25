<?php

class BaseApiController extends CController
{
    /**
     * Pre-filter untuk semua endpoint API.
     * Memastikan API key valid sebelum action dieksekusi.
     *
     * @param CAction $action action yang akan dieksekusi
     * @return bool true jika izin diberikan, false jika response error dikirim
     */
    protected function beforeAction($action)
    {
        $this->requireApiKey();
        return parent::beforeAction($action);
    }

    /**
     * Memastikan request API memiliki key yang valid.
     *
     * Key dibaca dari `API_V1_KEY` di protected/.env dan dibandingkan dengan
     * parameter `apikey` memakai hash_equals() (constant-time). Key kosong
     * menghasilkan JSON 500, key salah menghasilkan JSON 401, dan keduanya
     * menghentikan aplikasi lewat fail().
     *
     * @return void
     */
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
            (SELECT nilai FROM config WHERE nama = 'toko.nama' LIMIT 1) AS toko")->queryRow();

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

    /**
     * Kirim respons error JSON dan berhenti aplikasi.
     *
     * @param int $statusCode kode HTTP (400, 401, 500)
     * @param string $message pesan error yang akan ditampilkan
     */
    protected function fail($statusCode, $message)
    {
        http_response_code($statusCode);
        $this->renderJSON([
            'success' => false,
            'message' => $message
        ]);
    }
}
