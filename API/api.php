<?php
require_once '../Models/UMLConvertor.php';

use \Models\UMLConvertor;

if (isset($_POST['uml'])) {
    $uml_code = $_POST["uml"];
    header("Content-Type: image/svg+xml");
    echo UMLConvertor::convertUML($uml_code);
}
