<?php
use Phalcon\Escaper;

/**
 * This class file contains core functions that will be used for plugin nature validations.
 */
class My_Escaper {

    public function sanitize( $input = '' ) {
        $escaper = new Escaper();
        return $escaper->escapeHtmlAttr($input);
    }
}
