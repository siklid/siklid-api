<?php

declare(strict_types=1);

namespace App\Foundation\Service;

use App\Foundation\Util\Json;
use Symfony\Component\Yaml\Parser;

final class Yaml
{
    private Parser $parser;
    private Json $json;

    public function __construct(Parser $parser, Json $json)
    {
        $this->parser = $parser;
        $this->json = $json;
    }

    /**
     * Converts YAML to JSON.
     *
     * @param string $yaml         The YAML content to convert
     * @param string $yamlLocation The location of the YAML file
     * @param int    $yamlIndent   The YAML indentation
     *
     * @return string The JSON content to export
     */
    public function yamlToJson(string $yaml, string $yamlLocation = '', int $yamlIndent = 4): string
    {
        $yaml = $this->render($yaml, $yamlLocation, $yamlIndent);

        return $this->json->arrayToJson((array)$this->parser->parse($yaml));
    }

    /**
     * Renders the YAML content into a single YAML string.
     *
     * @param string $yaml         The YAML content to prepare
     * @param string $yamlLocation The location of the YAML file
     * @param int    $yamlIndent   The YAML indentation
     *
     * @return string The prepared YAML content
     */
    public function render(string $yaml, string $yamlLocation = '', int $yamlIndent = 4): string
    {
        // match all import statements '%import(path/to/file.yaml)%'
        preg_match_all('/%import\((.+)\)%/', $yaml, $matches);

        // replace all import statements with the content of the imported file
        foreach ($matches[0] as $match) {
            $path = str_replace(['%import(', ')%'], '', $match);
            $importedYaml = rtrim(file_get_contents($yamlLocation.'/'.$path));
            $importedYaml = $this->fixIndentation($importedYaml, $yamlIndent, $path);

            $importedYaml = $this->render($importedYaml, $yamlLocation, $yamlIndent);
            $yaml = str_replace("'$match'", $importedYaml, $yaml);
        }

        return rtrim($yaml);
    }

    private function fixIndentation(string $importedYaml, int $yamlIndent, string $path): string
    {
        // add spaces for each underscore in the beginning of the filename
        if (str_starts_with(basename($path), '_')) {
            preg_match('/^_*/', basename($path), $matches);
            $underscore_count = strlen($matches[0]);
            $importedYaml = preg_replace('/^/m', str_repeat(' ', $underscore_count), $importedYaml);
        }

        // auto indent all lines
        $importedYaml = preg_replace('/^/m', str_repeat(' ', $yamlIndent), $importedYaml);

        // remove extra indent from first line
        return ltrim($importedYaml);
    }
}
