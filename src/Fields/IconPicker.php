<?php
/******************************************************************************
 * Copyright (c) 2017. Kitrix Team                                            *
 * Kitrix is open source project, available under MIT license.                *
 *                                                                            *
 * @author: Konstantin Perov <fe3dback@yandex.ru>                             *
 * Documentation:                                                             *
 * @see https://kitrix-org.github.io/docs                                     *
 *                                                                            *
 *                                                                            *
 ******************************************************************************/

namespace Kitrix\Builder\Fields;

use Kitrix\Config\Admin\FieldRepresentation;
use Kitrix\Config\Admin\FieldType;

final class IconPicker extends FieldType
{
    private function getUniqueId($vars)
    {
        return "ktrx-gen-fa-set_".$vars[FieldRepresentation::ATTR_ID];
    }

    public function renderLabel($value, $vars)
    {
        $uniqId = $this->getUniqueId($vars);

        ob_start();
        ?>
        <label for="<?=$vars['id']?>">
            <?=$vars['title']?>
        </label>
        <div class="ktrx-gen-selected-icon">
            <div id="<?=$uniqId?>-icon" class="fa"></div>
        </div>

        <?
        return ob_get_clean();
    }

    public function renderWidget($value, $vars)
    {
        $uniqId = $this->getUniqueId($vars);

        ob_start();
        ?>
        <input
            <?=$vars[FieldRepresentation::ATTR_ATTRIBUTES_LINE]?>
            class="<?=$uniqId?>"
            type="text"
            value="<?=$value?>"
        >
        <script type="text/javascript">
            $(function() {
                var $inp = $(".<?=$uniqId?>");
                $inp.iconpicker({
                    placement: "inline",
                    title: false,
                    animation: false
                });
                $inp.off('iconpickerSelected').on('iconpickerSelected', function() {
                    $icon = $("#<?=$uniqId?>-icon");
                    $icon
                        .removeClass()
                        .addClass('fa')
                        .addClass($inp.val());
                });
            });
        </script>
        <?
        return ob_get_clean();
    }
}