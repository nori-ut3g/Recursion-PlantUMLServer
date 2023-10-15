<?php

namespace Models;

class UMLConvertor
{
    static string $jar_path = __DIR__ . '/../resources/plantuml.1.2023.7.jar';
    static string $tmp_path = __DIR__ . '/../tmp';
    static function convertUML(string $uml_code): string
    {

        $u_id = uniqid();
        $tmp_txt_file_name = 'uml_' . $u_id . '.txt';
        $tmp_svg_file_name = 'uml_' . $u_id . '.svg';
        $tmp_txt_path = self::$tmp_path . "/" . $tmp_txt_file_name;
        $output_svg_path = self::$tmp_path . "/" . $tmp_svg_file_name;

        file_put_contents($tmp_txt_path, $uml_code);
        $command = "java -Dfile.encoding=UTF-8 -jar " . self::$jar_path . " -tsvg {$tmp_txt_path} -o .";
        shell_exec($command);

        if (!file_exists($output_svg_path)) {
            unlink($tmp_txt_path);
            return '<?xml version="1.0" encoding="UTF-8"?><error>No UML</error>';
        }

        $svg_content = file_get_contents($output_svg_path);

        unlink($tmp_txt_path);
        unlink($output_svg_path);

        return $svg_content;
    }
}