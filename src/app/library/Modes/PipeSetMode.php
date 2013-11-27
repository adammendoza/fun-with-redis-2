<?php

namespace Modes;

class PipeSetMode extends BaseMode
{
    public function masterSetup()
    {

    }

    public function masterTeardown()
    {
        $predis = \PredisManager::GetMasterPredis();
        $predis->del('pipe_set.value');
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
        $limit = 1000;
        $pipe = $predis->pipeline();
        while($count < $limit)
        {
            $count++;
            $pipe->set('pipe_set.value', rand(0,1000));
        }
        $pipe->execute();
        $predis->incrby('pipe_set.incr', $limit);
    }

    /* Templates */

    public function templateControlPanel()
    {
        $predis = \PredisManager::GetMasterPredis();
        $val = $predis->get('pipe_set.value');
        $incr = $predis->get('pipe_set.incr');
        $incr = number_format($incr, 0);

        return <<<HTML
<h1>Pipe Set</h1>
<p>We're piping in commands 1,000 at a time. Last set value: {$val} Number of sets called: {$incr} </p>
HTML;

    }
}