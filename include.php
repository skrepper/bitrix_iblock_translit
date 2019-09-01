<?php
CModule::IncludeModule("translit_sym_code");
global $DBType;

$arClasses=array(
    'cMainRPJ'=>'classes/general/cMainRPJ.php'
);

CModule::AddAutoloadClasses("translit_sym_code",$arClasses);
