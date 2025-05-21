<?php

namespace WebModule\Forms;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;

class QrCodeForm extends Form
{
    public function __construct(?IContainer $parent = null, ?string $name = null)
    {
        parent::__construct($parent, $name);

        $this->addProtection("The form has expired, please submit the form again.");

        $this->addText("issuer", "Issuer")
            ->setHtmlAttribute("class", "input")
            ->setRequired("Enter issuer.");

        $this->addText("username", "Username")
            ->setHtmlAttribute("class", "input")
            ->setRequired("Enter username.");

        $this->addSubmit("submit", "Generate")
            ->setHtmlAttribute("class", "default");
    }
}
