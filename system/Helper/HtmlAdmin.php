<?php
namespace System\Helper;

class HtmlAdmin extends Html {

    /**
     * Képfeltöltés blokk generálása
     * Jasny Bootstrap - fileinput.js - v3.1.3 (http://jasny.github.io/bootstrap)
     *
     * A paraméter tömbnek tartalmaznia kell a: 'width', 'height', 'input_name' elemet
     * A paraméter tömbben opcionálisan megadható a: 'label', 'placeholder', 'info_content'  elem
     *
     * @param array
     * @return string
     */
    public function photoUpload(array $data)
    {
        $data['label'] = isset($data['label']) ? $data['label'] : 'Kép';
        $data['placeholder'] = isset($data['placeholder']) ? $data['placeholder'] : '';
        
        $default_info = 'Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a megjelenő módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!';
        $data['info_content'] = isset($data['info_content']) ? $data['info_content'] : $default_info;

        $html = '';
        $html .= '<label class="control-label">' . $data['label'] . '</label>' . "\r\n";
        $html .=    '<div class="form-group ">' . "\r\n";
        $html .=        '<div class="fileinput fileinput-new" data-provides="fileinput">' . "\r\n";
        $html .=            '<div class="fileinput-new thumbnail" style="width: ' . $data['width'] . 'px; height: ' . $data['height'] . 'px;">' . "\r\n";
        
        if (!empty($data['placeholder'])) {
        $html .=                '<img src="' . $data['placeholder'] . '" alt=""/>' . "\r\n";
        }
        
        $html .=            '</div>' . "\r\n";
        $html .=            '<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: ' . $data['width'] . 'px; max-height: ' . $data['height'] . 'px;"></div>' . "\r\n";
        $html .=            '<div>' . "\r\n";
        $html .=                '<span class="btn default btn-file">' . "\r\n";
        $html .=                    '<span class="fileinput-new">Kiválasztás</span>' . "\r\n";
        $html .=                    '<span class="fileinput-exists">Módosít</span>' . "\r\n";
        $html .=                    '<input class="img" type="file" name="' . $data['input_name'] . '">' . "\r\n";
        $html .=                '</span>' . "\r\n";
        $html .=                '<a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">Töröl</a>' . "\r\n";
        $html .=            '</div>' . "\r\n";
        $html .=        '</div>' . "\r\n";
        $html .=    '</div>' . "\r\n";

        $html .=    '<div class="clearfix"></div>' . "\r\n";
        $html .=    '<div class="note note-info">' . $data['info_content'] . '</div>' . "\r\n";

        return $html;
    }
}
?>