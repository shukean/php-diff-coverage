<?php

include __DIR__.'/ParseCodeDiff.php';
include __DIR__.'/ParsePhpUnitHtmls.php';

/**
 * @param array $files
 *      like:
 *        [
 *          [cur_file, new_file],
 *          [cur_file, new_file],
 *        ]
 * @param array $phpunit_dir
 *      like:
 *          [
 *              'path1', 'path2'
 *          ]
 */
function coverage(array $files, array $phpunit_dir, $file_dir){
    $diff_result = [];
    foreach ($files as list($cur_file, $change_file)){
        $diff_result[$change_file] = ParseCodeDiff::run($cur_file, $change_file);
    }

    $diff_ret = [];
    foreach ($diff_result as $file => $diff_lines){
        foreach ($phpunit_dir as $dir){
            $coverage_file = $dir.substr($file, strlen($file_dir)).'.html';
//             echo $coverage_file;
            if (!file_exists($coverage_file)){
                continue;
            }

            $uncoverage_lines = ParsePhpUnitHtmls::run($coverage_file);
            $diff_lines = array_intersect_key($diff_lines, $uncoverage_lines);
        }
        if (!empty($diff_lines)){
            $diff_ret[$file] = $diff_lines;
        }
    }
    return $diff_ret;
}