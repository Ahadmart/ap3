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
