<?php

class BaseApiController extends CController
{

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
                $route->enabled = false; // disable any weblogroutes
            }
        }
        Yii::app()->end();
    }

    protected function fail($statusCode, $message){
        http_response_code($statusCode);
        $this->renderJSON([
            'success' => false,
            'message' => $message
        ]);
    }

}
