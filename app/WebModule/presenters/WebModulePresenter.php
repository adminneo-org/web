<?php

namespace WebModule;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use WebLoader\Compiler;
use WebLoader\FileCollection;
use WebLoader\Filter\JsMinFilter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

abstract class WebModulePresenter extends Presenter
{
	protected function createTemplate(?string $class = null): Template
    {
        $template = parent::createTemplate();

        $template->getLatte()->addFilterLoader([new Helpers, "load"]);

        return $template;
    }

    protected function createComponentCss(): CssLoader
    {
	    $files = new FileCollection(APP_DIR . "/WebModule/styles");
	    $files->addFiles(["main.less",]);
	    $files->addWatchFiles(glob(APP_DIR . "/WebModule/styles/*.less"));

	    $compiler = Compiler::createCssCompiler($files, PUBLIC_DIR . "/compiled");
	    $compiler->addFileFilter(new LessFilter());

		$basePath = $this->getHttpRequest()->getUrl()->getBasePath();
	    $control = new CssLoader($compiler, "{$basePath}compiled", ENVIRONMENT_NAME == "development");
	    $control->setMedia('screen');

	    return $control;
    }

	protected function createComponentJs(): JavaScriptLoader
	{
		$files = new FileCollection(APP_DIR . "/WebModule/scripts");
		$files->addFiles([
			COMPONENTS_DIR . "/nette.ajax.js/nette.ajax.js",
			"main.js",
		]);

		$compiler = Compiler::createJsCompiler($files, PUBLIC_DIR . "/compiled");
		$compiler->addFilter(new JsMinFilter());

		$basePath = $this->getHttpRequest()->getUrl()->getBasePath();
		$control = new JavaScriptLoader($compiler, "{$basePath}compiled", ENVIRONMENT_NAME == "development");

		return $control;
	}
}
