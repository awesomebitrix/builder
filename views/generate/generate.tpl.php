<?
/**
 * @var array $tabs
 * @var string $post_url
 * @var \CAdminMessage $error
 */
use Kitrix\Config\Admin\FormHelper;

?>

<?/** =============== BITRIX CODE ==================== */?>

<form method="POST" Action="<?=$post_url?>" ENCTYPE="multipart/form-data" name="post_form">

<?=bitrix_sessid_post();?>

<?
// prepare tabs
$adminTabs = [];
foreach ($tabs as $key => $tab)
{
    $adminTabs[] = [
        "DIV" => $key,
        "TAB" => $tab['title'],
        "TITLE"=> $tab['title'],
        "ICON"=>"ktrx_gen_".$key,
    ];
}

$tabControl = new CAdminTabControl("tabControl", $adminTabs);
$tabControl->Begin();

// render all tabs
foreach ($tabs as $key => $tab)
{
    $tabControl->BeginNextTab();
    FormHelper::renderForm($tab['widgets']);
}
?>

<?
$tabControl->Buttons(
    array(
        "btnCancel" => false,
        "btnApply" => false,
    )
);
$tabControl->End();
$tabControl->ShowWarnings("post_form", $error);

?>