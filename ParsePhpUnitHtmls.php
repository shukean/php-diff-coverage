<?php

class ParsePhpUnitHtmls{

    public static function run($coverage_file){

        $dom = new DOMDocument();
        @$dom->loadHTMLFile($coverage_file);

        $codes = $dom->getElementById('code');
        $trs = $codes->getElementsByTagName('tr');

        $unconverage_lines = [];

        foreach ($trs as $tr){
            $attr_node = $tr->attributes;
            $class_node = $attr_node->getNamedItem('class');
            if (!$class_node || $class_node->nodeValue != 'danger'){
                continue;
            }
            $td = $tr->getElementsByTagName('td');
            if ($td->length != 2){
                throw new Exception('tr contents td number more than 2');
            }
            $line_node = $td->item(0);
            $code_node = $td->item(1);

//             echo $line_node->nodeValue, PHP_EOL;
//             echo $code_node->nodeValue, PHP_EOL;
//             echo $tr->nodeValue, PHP_EOL;
            $unconverage_lines[$line_node->nodeValue] = $code_node->nodeValue;
        }

        return $unconverage_lines;
    }

}