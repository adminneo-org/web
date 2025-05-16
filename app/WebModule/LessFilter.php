<?php
declare(strict_types=1);

namespace WebModule;

use Exception;
use Less_Exception_Parser;
use Less_Parser;
use WebLoader\Compiler;

class LessFilter
{
	/**
	 * @throws Less_Exception_Parser
	 * @throws Exception
	 */
	public function __invoke(string $code, Compiler $loader, string $file): string
	{
		$parser = new Less_Parser(["strictMath" => true, "compress" => true]);
		$parser->parseFile($file);

		return $parser->getCss();
	}
}
