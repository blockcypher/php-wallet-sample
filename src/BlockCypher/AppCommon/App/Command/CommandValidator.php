<?php
namespace BlockCypher\AppCommon\App\Command;

interface CommandValidator
{
    /**
     * @param $command
     * @return void
     */
    public function validate($command);
}