<?php

/**
 * Tina4 - This is not a 4ramework.
 * Copy-right 2007 - current Tina4
 * License: MIT https://opensource.org/licenses/MIT
 */

namespace Tina4;

/**
 * Reads a .env file or .env.{environment} file for settings that should not be committed up with the repository
 * @package Tina4
 */
class Env
{
    /**
     * Env constructor.
     * @param string|null $forceEnvironment
     */
    public function __construct(?string $forceEnvironment = "")
    {
        if (!empty(getenv("ENVIRONMENT"))) {
            $environment = getenv("ENVIRONMENT");
        }

        if (empty($environment)) {
            $environment = $forceEnvironment;
        }

        $this->readParams($environment);
    }

    /**
     * Parses a line into variables
     * @param $line
     */
    private function parseLine($line): void
    {
        if (empty($line)) {
            return;
        }
        if ($line[0] === "#" || empty($line) || ($line[0] === "[" && $line[strlen($line) - 1] === "]")) {
            return;
        }
        $variables = explode("=", $line, 2);
        if (isset($variables[0], $variables[1])) {
            $key = trim($variables[0]);
            $osValue = getenv($key);
            if ($osValue !== false) {
                $_ENV[$key] = $osValue;
                if (!defined($key)) {
                    define($key, $osValue);
                }
                return;
            }
            if (!defined($key)) {
                $variable = $key;

                if (isset($variables[1]) && is_string($variables[1])) {
                    $trimmed = trim($variables[1]);
                    if ((count($variables) > 0 && $trimmed === "false") || $trimmed === "true" ||
                        ($trimmed !== '' && ($trimmed[0] === '[' || $trimmed[0] === '"' || $trimmed[0] === '\''))) {
                        $code = "try {\n    \${$variable} = {$trimmed};\n} catch(\\Exception \$e) {\n    //Could not set.\n}";
                        if (false === @eval($code)) {
                           extract([$variable => $trimmed], EXTR_OVERWRITE);
                        }
                    } else {
                        extract([$variable => $trimmed], EXTR_OVERWRITE);
                    }
                } else {
                    extract([$variable => ''], EXTR_OVERWRITE); // Fallback to empty string
                }

                $_ENV[$key] = ${$variable};
                define($key, ${$variable});
            }
        }
    }

    /**
     * The readEnvParams reads the environment variables from the .env.{ENVIRONMENT} file
     * @param string|null $environment
     * @tests tina4
     *   assert ("test") === null,"Parsing the environment"
     *   assert file_exists(".env.test") === true,"File does not exist .env.test"
     */
    final public function readParams(?string $environment): void
    {
        if (defined("TINA4_DOCUMENT_ROOT")) {
            $rootFolder = TINA4_DOCUMENT_ROOT;
        } else {
            $rootFolder = "./";
        }
        $fileName =  $rootFolder. ".env";

        if (!empty($environment)) {
            $fileName .= ".{$environment}";
        }

        if (file_exists($fileName)) {
            $fileContents = file_get_contents($fileName);
            if (strpos($fileContents, "\r")) {
                $fileContents = explode("\r\n", $fileContents);
            } else {
                $fileContents = explode("\n", $fileContents);
            }

            foreach ($fileContents as $id => $line) {
                $this->parseLine($line);
            }
        }
    }
}