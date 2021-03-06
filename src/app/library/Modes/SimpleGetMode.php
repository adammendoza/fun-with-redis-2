<?php

namespace Modes;

class SimpleGetMode extends BaseMode
{
    public function masterSetup()
    {
        $predis = \PredisManager::GetMasterPredis();
        $predis->set('simple_get.value', rand(1,9999999999));
    }

    public function masterTeardown()
    {
        $predis = \PredisManager::GetMasterPredis();
        $predis->del('simple_get.value');
    }

    public function clientSetup()
    {
        // Do nothing
    }

    public function clientTeardown()
    {
        // Do nothing
    }

    public function clientWork()
    {
        $predis = \PredisManager::GetClientPredis();
        $count = 0;
        $limit = 500;
        while($count < $limit)
        {
            $count++;
            $predis->get('simple_get.value');
        }

    }

    /* Templates */

    public function templateControlPanel()
    {
        return <<<HTML
<h1>Better World!</h1>
HTML;

    }
}