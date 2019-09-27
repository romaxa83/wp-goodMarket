<?php

namespace console\controllers;

use yii\console\Controller;
use yii2tech\crontab\CronTab;

class CronController extends Controller {

    /**
     * Run all cron's tasks
     */
    public function actionStart() {
        $cronTab = new CronTab();
        $cronTab->headLines = [
            'SHELL=/bin/sh',
            'PATH=/usr/bin:/usr/sbin',
        ];
        $cronTab->setJobs([
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'save-products')
            ],
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'update-products')
            ],
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'edit-products')
            ]
        ]);
        $cronTab->apply();
    }

    /**
     * Stop all cron's tasks
     */
    public function actionStop() {
        $cronTab = new CronTab();
        $cronTab->setJobs([
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'save-products')
            ],
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'update-products')
            ],
            [
                'min' => '*/1',
                'command' => $this->getCommand('save-import-products', 'edit-products')
            ]
        ]);
        $cronTab->remove();
    }

    public function actionProcessError($nameProcess) {
        $service = new \common\service\ImportService();
        if ($nameProcess == 'save-products_down') {
            unlink(Yii::getAlias('@root') . '/console/runtime/create');
        } elseif ($nameProcess == 'update-products_down') {
            unlink(Yii::getAlias('@root') . '/console/runtime/update');
        } else {
            unlink(Yii::getAlias('@root') . '/console/runtime/edit');
        }
        $service->writeLogs($nameProcess, 'CronError.txt');
    }

    private function getCommand($controller, $action) {
        $path = \Yii::getAlias('@root');
        $routeAction = $controller . '/' . $action;
        return "php7.1 ${path}/yii ${routeAction} || php7.1 ${path}/yii cron/process-error ${action}_down";
    }

}
