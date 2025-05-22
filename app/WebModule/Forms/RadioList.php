<?php

namespace WebModule\Forms;

use Nette\Utils\Html;
use Stringable;

class RadioList extends \Nette\Forms\Controls\RadioList
{
    public function __construct(Stringable|string|null $label = null, ?array $items = null)
    {
        parent::__construct($label, $items);

        $this->container = Html::el("div")->setAttribute("class", "options-list");
        $this->separator = Html::el();
    }
}
