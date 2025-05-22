<?php

declare(strict_types=1);

namespace WebModule\Forms;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;
use WebModule\Tools\CompilationParams;

class DownloadForm extends Form
{
    public function __construct(?IContainer $parent = null, ?string $name = null)
    {
        parent::__construct($parent, $name);

        $this->addSelect("project", "Project", CompilationParams::Projects)
            ->setRequired("Select project.");

        $this->addSelect("version", "Version", CompilationParams::Versions)
            ->setRequired("Select version.");

        $options = ["all" => "All", "custom" => "Custom"];

        // Drivers.
        $control = $this["driversOption"] = new RadioList("Drivers", $options);
        $control
            ->setRequired("Select divers option.")
            ->setDefaultValue("all")
            ->addCondition(self::Equal, "custom")
                ->toggle("download-drivers")
            ->endCondition();

        $control = $this["drivers"] = new CheckboxList(items: CompilationParams::Drivers);
        $control
            ->setOption("id", "download-drivers")
            ->addConditionOn($this["driversOption"], self::Equal, "custom")
                ->setRequired("Select at least one driver.");

        // Languages.
        $control = $this["languagesOption"] = new RadioList("Languages", $options);
        $control
            ->setRequired("Select languages option.")
            ->setDefaultValue("all")
            ->addCondition(self::Equal, "custom")
                ->toggle("download-languages")
            ->endCondition();

        $languages = CompilationParams::Languages;
        asort($languages);

        $control = $this["languages"] = new CheckboxList(items: $languages);
        $control
            ->setOption("id", "download-languages")
            ->addConditionOn($this["languagesOption"], self::Equal, "custom")
                ->setRequired("Select at least one language.");

        // Themes.
        $control = $this["themesOption"] = new RadioList("Themes", $options);
        $control
            ->setRequired("Select themes option.")
            ->setDefaultValue("all")
            ->addCondition(self::Equal, "custom")
                ->toggle("download-themes")
            ->endCondition();

        $control = $this["themes"] = new CheckboxList(items: CompilationParams::getThemeVariants());
        $control
            ->setOption("id", "download-themes")
            ->addConditionOn($this["themesOption"], self::Equal, "custom")
                ->setRequired("Select at least one theme.");

        $this["submit"] = new IconSubmitButton("Download", "download");

        $this->onSuccess[] = function (DownloadForm $form): void {
            $form["themes"]->setValue(CompilationParams::normalizeThemes($form->getValues()->themes));
        };
    }
}
