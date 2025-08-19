<?php
/**
 * Tina4 - This is not a 4ramework.
 * Copy-right 2007 - current Tina4
 * License: MIT https://opensource.org/licenses/MIT
 */

(new \Tina4\Env());

//We only want to fiddle with the defaults if we are developing
if (defined("TINA4_DEBUG") && TINA4_DEBUG) {

    if (function_exists("tina4_exception_handler")) {
        set_exception_handler("tina4_exception_handler");
    }

    if (function_exists("tina4_error_handler")) {
        set_error_handler("tina4_error_handler");
    }
}
