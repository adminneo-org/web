<?php

declare(strict_types=1);

namespace WebModule\Presenters;

use Exception;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\FileResponse;
use Tracy\Debugger;
use WebModule\Forms\DownloadForm;
use WebModule\Tools\CompilationParams;

class DownloadPresenter extends WebModulePresenter
{
    public function renderDefault(): void
    {
        $this->getTemplate()->languages = CompilationParams::Languages;
        $this->getTemplate()->colorVariants = CompilationParams::ColorVariants;
    }

    public function createComponentDownloadForm(string $name): DownloadForm
    {
        $form = new DownloadForm($this, $name);
        $form->onSuccess[] = function (DownloadForm $form): never {
            $values = $form->getValues();

            $version = $values->version;
            $project = $values->project;
            $drivers = $this->encodeOptionsList($values->driversOption, $values->drivers);
            $languages = $this->encodeOptionsList($values->languagesOption, $values->languages);
            $themes = $this->encodeOptionsList($values->themesOption, $values->themes);

            $optionsDir = implode("_", [$drivers, $languages, $themes]);

            $uri = "files/v$version/$optionsDir/{$project}neo-$version.php";

            $this->redirectUrl($this->getHttpRequest()->getUrl()->getBaseUrl() . $uri);
        };

        return $form;
    }

    /**
     * @throws BadRequestException
     * @throws Exception
     */
    public function renderFile(string $project, string $version, string $version2, string $options): void
    {
        if (!isset(CompilationParams::Versions[$version]) || $version2 != $version) {
            throw new BadRequestException();
        }

        // Validate and normalize parameters.
        $parts = explode("_", $options);
        $drivers = $this->validateOptionsList($parts[0], CompilationParams::Drivers, $normalizedDrivers);
        $languages = $this->validateOptionsList($parts[1], CompilationParams::Languages, $normalizedLanguages);
        $themes = $this->validateOptionsList($parts[2], CompilationParams::getThemeVariants(), $normalizedThemes, normalizeThemes: true);

        if ($drivers === null || $languages === null || $themes === null) {
            $this->redirect("this", [
                "options" => "{$normalizedDrivers}_{$normalizedLanguages}_{$normalizedThemes}",
            ]);
        }

        // Concurrency lock.
        $lock = fopen("nette.safe://" . TEMP_DIR . "/compile.lock", "w");

        Debugger::log("Compiling $project" .
            ", drivers: " . ($drivers ?: "all") .
            ", languages: " . ($languages ?: "all") .
            ", themes: " . ($themes ?: "all")
        );

        // Prepare the destination directory.
        $destination = PUBLIC_DIR . "/files/v$version/$options";
        @mkdir($destination, 0777, true);

        // Run shell command.
        $command = "sh ../bin/compile.sh" .
            " -v '$version'" .
            " -p '$project'" .
            ($drivers ? " -d '$drivers'" : "") .
            ($languages ? " -l '$languages'" : "") .
            ($themes ? " -t '$themes'" : "");

        if (exec($command) === false) {
            throw new Exception("Error compiling AdminNeo.");
        }

        // Move compiled files.
        $filePath = "$destination/{$project}neo-$version.php";
        rename(TEMP_DIR . "/adminneo/compiled/{$project}neo.php", $filePath);

        $pluginsPath = "$destination/../plugins";
        if (!file_exists($pluginsPath)) {
            rename(TEMP_DIR . "/adminneo/compiled/adminneo-plugins", $pluginsPath);
        }

        fclose($lock);

        // Send the file.
        $this->getHttpResponse()->setExpiration("+2 years");
        $response = new FileResponse($filePath, null, "application/x-httpd-php");
        $this->sendResponse($response);
    }

    private function encodeOptionsList(string $listOption, array $values): string
    {
        if ($listOption == "all") {
            return "";
        }

        sort($values);

        return implode(".", $values);
    }

    /**
     * @throws BadRequestException
     */
    private function validateOptionsList(string $string, array $allValues, ?string &$normalized, bool $normalizeThemes = false): ?string
    {
        if ($string == "") {
            return "";
        }

        $values = explode(".", $string);

        // Check values validity.
        foreach ($values as $value) {
            if (!isset($allValues[$value])) {
                throw new BadRequestException();
            }
        }

        if ($normalizeThemes) {
            $values = CompilationParams::normalizeThemes($values);
        }

        // Check correct sorting.
        sort($values);

        $normalized = implode(".", $values);
        if ($normalized != $string) {
            return null;
        }

        return implode(",", $values);
    }
}
