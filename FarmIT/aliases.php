<?php

/**
 * FarmIT namespace aliases.
 *
 * Maps FarmIT\ClassName to Tina4\ClassName so both namespaces
 * work during the migration period. Existing Tina4\ imports are
 * unaffected. New code may use FarmIT\ imports.
 */

$__t4 = 'Tina4\\Env';
if (class_exists($__t4, false) || interface_exists($__t4, false) || trait_exists($__t4, false)) {
    class_alias($__t4, 'FarmIT\\Env');
}
unset($__t4);
