<h1>Настройка модуля</h1>

<?
$module_id = "translit_sym_code";
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
$RIGHT = $APPLICATION->GetGroupRight($module_id);

if($RIGHT >= "R") :

$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
//    array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

CModule::IncludeModule($module_id);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");

    if(strlen($RestoreDefaults)>0) {
	COption::RemoveOption("FORM_DEFAULT_IBLOCK");
	}	
    else
    {
     /*   foreach($arAllOptions as $arOption)
        {
            $name=$arOption[0];
            $val=$_REQUEST[$name];
            // @todo: проверка безопасности должна быть тут!
            COption::SetOptionString($module_id, $name, $val);
        } */
	COption::SetOptionString($module_id, "FORM_DEFAULT_IBLOCK", $_REQUEST["CHOICE"]);	
    }
}
?>


<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();
        $arNotes = array();

	if(!CModule::IncludeModule("iblock"))
		return;


	$resCIBlock = CIBlock::GetList(
   	Array(), 
   	Array(
     	// 'TYPE'=>'catalog', 
     	// 'SITE_ID'=>SITE_ID, 
      	'ACTIVE'=>'Y', 
      	"CNT_ACTIVE"=>"Y", 
      	"!CODE"=>'my_products'
   	), true
	);


	$arrRef = array();
	$arrRef_id = array();

	while($ar_res = $resCIBlock->Fetch())
	{
   		$arrRef[]=$ar_res['NAME'];
   		$arrRef_id[]=$ar_res['ID'];

		//print_r($ar_res);

	}

	$arrCIBlock=array(
    	"REFERENCE" => // массив заголовков элементов
        	$arrRef,
    	"REFERENCE_ID" => // массив значений элементов
        	$arrRef_id
    	); 


	$iblock_id = COption::GetOptionString($module_id, "FORM_DEFAULT_IBLOCK");
        //echo "perm=".$perm."<br>";
	echo SelectBoxFromArray("CHOICE", $arrCIBlock, $iblock_id, "", "")

    ?>

    <?$tabControl->Buttons();?>
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
    <?if(strlen($_REQUEST["back_url_settings"])>0):?>
        <input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
        <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
    <?endif?>
    <input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
    <?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
</form>


<?
if(!empty($arNotes))
{
    echo BeginNote();
    foreach($arNotes as $i => $str)
    {
        ?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
    }
    echo EndNote();
}
?>
<?endif;?>
