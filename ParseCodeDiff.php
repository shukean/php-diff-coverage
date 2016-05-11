<?php

class ParseCodeDiff{

    public static function run($cur_file, $change_file){

        if (!file_exists($cur_file)){
            $lines = file($change_file, FILE_IGNORE_NEW_LINES);
            $diff_lines = [];
            foreach ($lines as $line => $text){
                if (!empty($text)){
                    $diff_lines[$line + 1] = $text;
                }
            }
            return $diff_lines;
        }

        exec("diff -b $cur_file $change_file", $output);
        if (empty($output)){
            return [];
        }

        $diff_lines = [];
//         print_r($output); exit;
        for ($i=0, $len=count($output); $i<$len; $i++){
            $line = $output[$i];
            if (preg_match('/^(\d+)(?:,(\d+))?([acd])(\d+)(?:,(\d+))?$/', $line, $matchs)){
                $cur_df_start = intval($matchs[1]);
                $cur_df_end = intval($matchs[2]);
                $cg_df_start = intval($matchs[4]);
                $cg_df_end = isset($matchs[5]) ? intval($matchs[5]) : 0;
                switch ($matchs[3]){
                    case 'a':
                        $line_nums = $cg_df_end ? range($cg_df_start, $cg_df_end) : [$cg_df_start];
                        foreach ($line_nums as $line){
                            $i++;
                            $diff_lines[$line] = substr($output[$i], 1);
                        }
                        break;
                    case 'd':
                        //$line_nums = $cg_df_end ? range($cg_df_start, $cg_df_end) : [$cg_df_start];
                        break;
                    case 'c':
                        $tmp_a = $cur_df_end ? range($cur_df_start, $cur_df_end) : [$cur_df_start];
                        $i += count($tmp_a);
                        $i ++;  // ---
                        $line_nums = $cg_df_end ? range($cg_df_start, $cg_df_end) : [$cg_df_start];
                        foreach ($line_nums as $line){
                            $i++;
                            $diff_lines[$line] = substr($output[$i], 1);
                        }
//                         print_r($diff_lines);
                        break;
                    default:
                        throw new Exception('operation '.$matchs[3].' not defined');
                }
            }
        }
        return $diff_lines;
    }



}