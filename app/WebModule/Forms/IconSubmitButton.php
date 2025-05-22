<?php

namespace WebModule\Forms;

use Nette;
use Nette\Http\RequestFactory;
use Stringable;

class IconSubmitButton extends \Nette\Forms\Controls\SubmitButton
{
    private ?string $icon;

    public function __construct(string|Stringable|null $caption = null, string $icon = null)
    {
        parent::__construct($caption);

        $this->icon = $icon;

        $this->renderAsButton();
        $this->setHtmlAttribute("class", "button default");
    }

    public function getControl($caption = null): Nette\Utils\Html
    {
        $control = parent::getControl($caption);

        if ($this->icon) {
            $icon = htmlspecialchars($this->icon);
            $basePath = (new RequestFactory())->fromGlobals()->getUrl()->getBasePath();

            $control->setHtml("<svg class='icon ic-$icon'><use href='$basePath/images/icons.svg#$icon'/></svg> ");
            $control->addText($this->getCaption());
        }

        return $control;
    }
}
