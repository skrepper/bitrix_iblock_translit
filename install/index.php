<?
Class translit_sym_code extends CModule
{
    var $MODULE_ID = "translit_sym_code";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function translit_sym_code()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "Транслитерация символьного кода";
        $this->MODULE_DESCRIPTION = "Транслитерация символьного кода";
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;



CAgent::AddAgent(
    "cMainRPJ::agentDeleteOldRecs();", // имя функции
    "translit_sym_code",                          // идентификатор модуля
    "N",                                  // агент не критичен к кол-ву запусков
    86400,                                // интервал запуска - 1 сутки
    /*"01.09.2019 21:56:00"*/"",                // дата первой проверки на запуск
    "Y",                                  // агент активен
    /*"01.09.2019 21:56:00"*/"",                // дата первого запуска
    30);

//Аналог функции в новом ядре: Bitrix\Main\EventManager::registerEventHandler .
        // Install events
        RegisterModuleDependences("iblock","OnBeforeIBlockElementAdd",$this->MODULE_ID,"cMainRPJ","OnBeforeIBlockElementAddHandler");
        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Установка модуля ".$this->MODULE_ID, $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
        return true;
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;


CAgent::RemoveAgent(
    "cMainRPJ::agentDeleteOldRecs();", // имя функции
    "translit_sym_code");                          // идентификатор модуля



        UnRegisterModuleDependences("iblock","OnBeforeIBlockElementAdd",$this->MODULE_ID,"cMainRPJ","OnBeforeIBlockElementAddHandler");
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля ".$this->MODULE_ID, $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
        return true;
    }
}