<?php

namespace WebModule;

use Nette\Application;
use Tracy\Debugger;

class ErrorPresenter extends WebModulePresenter
{
    public function renderDefault($exception): void
    {
        if ($exception instanceof Application\BadRequestException) {
            $code = $exception->getCode();

            $this->setView($code == 404 ? $code : 'error');
        } else {
            Debugger::log($exception, Debugger::ERROR);
            $code = 500;
        }

        $this->getTemplate()->code = $code;
    }
}
