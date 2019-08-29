<?php
/**
 * Javaria Project
 * Copyright © 2019
 * Michel Noel
 * Datalight Analytics
 * http://www.datalightanalytics.com/
 *
 * Creative Commons Attribution-ShareAlike 4.0 International Public License
 * By exercising the Licensed Rights (defined below), You accept and agree to be bound by the terms and conditions of
 * this Creative Commons Attribution-ShareAlike 4.0 International Public License ("Public License"). To the extent this
 * Public License may be interpreted as a contract, You are granted the Licensed Rights in consideration of Your
 * acceptance of these terms and conditions, and the Licensor grants You such rights in consideration of benefits the
 * Licensor receives from making the Licensed Material available under these terms and conditions.
 *
 * File: htmlwriter.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class htmlwriter {

    public $modalid;
    public $labelsize;
    public $inputsize;
    public $title;
    public $footercancel;
    public $footeraccept;
    public $modalsize;
    public $acceptfunction;
    public $acceptfunctioncontents;
    public $acceptfunctionsingleval;
    public $acceptcustomfunction;
    public $includescript;
    public $usemodalid;
    public $usenameattribs;

    public function tag($tagtype, $tagid, $tagclass ='', $tagcontents ='', array $tagattributes = null ) {
        $val  = self::begintag($tagtype,$tagid, $tagclass, $tagattributes ) . $tagcontents . self::endtag($tagtype);
        return $val;
    }

    public function begintag($tagtype, $tagid ='', $tagclass = '', array $tagattributes = null) {
        $ztagid = $tagid != '' ? ' id="' . $tagid . '"' : '';
        $ztagclass = $tagclass != '' ? ' class="' . $tagclass . '"' : '';

        $ztagattributes = '';

        foreach ((array)$tagattributes as $attrib => $value) {

            if($value !== null && $value !== '') {
                $ztagattributes .= ' ' . $attrib . '="' . $value . '"';
            }
            else{
                $ztagattributes .= ' ' . $attrib;
            }
        }
        return  '<' . $tagtype . $ztagid . $ztagclass . $ztagattributes . '>';
    }

    public function endtag($tagtype){
        return '</' . $tagtype . '>';
    }

    public function addinlinetag(&$beginningcontent, &$endingcontent,$tagtype, $tagid ='', $tagclass = '', array $tagattributes = null,$tagcontent = ''){
        $beginningcontent = $beginningcontent . self::begintag($tagtype,$tagid,$tagclass,$tagattributes) . $tagcontent;
        $endingcontent = self::endtag($tagtype) . $endingcontent;
        return $beginningcontent . $endingcontent;
    }

    public function addtag(&$beginningcontent,$tagtype, $tagid ='', $tagclass = '', array $tagattributes = null,$tagcontent = ''){
        $beginningcontent = $beginningcontent . self::begintag($tagtype,$tagid,$tagclass,$tagattributes) . $tagcontent;
        return $beginningcontent;
    }

    public function cleanupHTML($html){

        $format = new format;
        $newhtml = $format->HTML($html);

        // HTML formatting breaks greater than signs in quoted javascript html calls
        $newhtml = str_replace('~+', '>',$newhtml);
        return $newhtml;
    }

    public function createModal(&$buildpagebegin,&$endcontent,$contents,$modalclass = '', array $modalattributes = null) {

        $allmodalattributes = array_merge(array('role' => 'dialog', 'data-backdrop' => 'static', 'aria-hidden' => 'true', 'z-index' => '-1', 'tabindex' => '-1'), (array)$modalattributes)     ;

        // MODAL STRUCTURE
        $this->addinlinetag($buildpagebegin, $endcontent, 'div', $this->modalid, 'modal fade ' . $modalclass, $allmodalattributes);
        $this->addinlinetag($buildpagebegin, $endcontent, 'div', '', 'modal-dialog modal-dialog-centered ' . $this->modalsize );
        $this->addinlinetag($buildpagebegin, $endcontent, 'div', '', 'modal-content');

        // HEADER
        $header          = $this->tag('button','','close', '&times;', array('data-dismiss'=>'modal','type'=>'button'));
        $header         .= $this->tag('h4',$this->modalid . '__title','modal-title', $this->title );
        $buildpagebegin .= $this->tag('div','','modal-header',$header);

        // BODY
        $this->addinlinetag($bodybegin, $bodyend,'div','','modal-body');
        $this->addinlinetag($bodybegin, $bodyend,'form','','form-horizontal',array('data-toggle'=>'validator'));
        $this->addinlinetag($bodybegin, $bodyend,'fieldset','','');
        $this->addinlinetag($bodybegin, $bodyend,'div','','form-group');

        $buildpagebegin .= $bodybegin . $contents;

        // FOOTER

        $footer = '';
        if(!empty($this->footercancel))  $footer .= $this->tag('button',$this->modalid . '__btn_decline','btn btn-default btn-info',$this->footercancel, array('type'=>'button', 'data-dismiss'=>'modal'));
        if(!empty($this->footeraccept))  $footer .= $this->tag('a', $this->modalid . '__btn_accept', 'btn btn-large btn-info', $this->footeraccept, array('type' => 'submit', 'data-dismiss' => 'modal','tabindex'=>'0'));

        $endcontent      = $this->tag('div','','modal-footer', $footer) . $endcontent;

        // INCLUDE JS SCRIPT URL
        if(!empty($this->includescript)) $endcontent .= $this->includeScript($this->includescript);

        $endcontent = $bodyend . $endcontent;

        if(!empty($this->footeraccept) && !empty($this->acceptfunction) ) {

            $buildfunction = '$("#' . $this->modalid . '__btn_accept' . '").click( function() {' . PHP_EOL;
            $buildfunction .= 'var tableVals = {};' . PHP_EOL;
            $buildfunction .= '$("[id^=' . $this->modalid . '__inp_]' . ($this->usenameattribs ? '[name]' :'') . ',';
            $buildfunction .= '[id^=' . $this->modalid . '__sel_]' . ($this->usenameattribs ? '[name]' :'') . ',';
            $buildfunction .= '[id^=' . $this->modalid . '__chk_]' . ($this->usenameattribs ? '[name]' : '');
            $buildfunction .= '").each(function(){';
            $buildfunction .= $this->usenameattribs ? 'tableVals[$(this).attr("name")]' : 'tableVals[$(this).attr("id")]';
            $buildfunction .= '= $(this).is(":checkbox") ? $(this).is(":checked") : $(this).val(); });';

            $addmodalid = $this->usemodalid ? ', modalval: $("#' . $this->modalid  . '").val()' : '';

            $buildfunction .= '$.post("ajaxfunctions.php", {action:"' . $this->acceptfunction . '", tableVals: tableVals' . $addmodalid . '} , function(data, status){ ' . $this->acceptfunctioncontents . '  }); });';

            global $document_ready;
            $document_ready .= $this->cleanupHTML($buildfunction);
        }elseif(!empty($this->footeraccept) && !empty($this->acceptfunctionsingleval) ) {

                $buildfunction  = '$("#' . $this->modalid . '__btn_accept' . '").click( function() {' . PHP_EOL;
                $buildfunction .=  '$.post("ajaxfunctions.php", {action:"' . $this->acceptfunctionsingleval . '", singleVal: $("#' . $this->modalid  . '").val() } , function(data, status){ ' . $this->acceptfunctioncontents . '  }); });';

                global $document_ready;
                $document_ready .= $this->cleanupHTML($buildfunction);

        }elseif(!empty($this->footeraccept) && !empty($this->acceptcustomfunction) ) {

            $buildfunction  = '$("#' . $this->modalid . '__btn_accept' . '").click( function(){  '. $this->acceptcustomfunction .'; });';

            global $document_ready;
            $document_ready .= $this->cleanupHTML($buildfunction);
        }
    }

    public function createFormTextInput($langstring, $inputname, array $inputattributes = null, array $tagattributes = null, array $otheroptions = null){

        $beforecontents = !empty($otheroptions) && array_key_exists ('beforecontents', $otheroptions) ? $otheroptions['beforecontents'] : '';
        $aftercontents =  !empty($otheroptions) && array_key_exists ('aftercontents', $otheroptions) ? $otheroptions['aftercontents'] : '';
        $labelclass = !empty($otheroptions) && array_key_exists ('labelclass', $otheroptions) ? $otheroptions['labelclass'] : '';
        $inputclass = !empty($otheroptions) && array_key_exists ('inputclass', $otheroptions) ? $otheroptions['inputclass'] : '';

        $labelsize = !empty($otheroptions) && array_key_exists ('labelsize', $otheroptions) ? $otheroptions['labelsize'] : $this->labelsize;
        $inputsize = !empty($otheroptions) && array_key_exists ('inputsize', $otheroptions) ? $otheroptions['inputsize'] : $this->inputsize;

        $tag  = !empty($inputattributes) && array_key_exists ('tag', $inputattributes) ? $inputattributes['tag'] : 'input';

        $tagattributes['type'] = !empty($tagattributes) && array_key_exists ('type', $tagattributes) ? $tagattributes['type'] : 'text';
        $tagattributes['required'] = '';

        $namelabel  = $this->tag('label', '', $labelsize . ' control-label ' . $labelclass, mlang_str($langstring, true), array('for' => $this->modalid . '__inp_' . $inputname));
        $input      = $this->tag($tag,$this->modalid . '__inp_' . $inputname, $inputsize .' form-control input-md ' . $inputclass, '', $tagattributes);
        $outdiv     = $this->tag('div', '', $inputsize, $input);
        $formg1     = $this->tag('div', '', 'form-group', $beforecontents . $namelabel . $outdiv . $aftercontents);
        return $formg1;
    }

    public function createFormInput($inputname, array $inputattributes = null, array $tagattributes = null, array $otheroptions = null){

        $beforecontents = !empty($otheroptions) && array_key_exists ('beforecontents', $otheroptions) ? $otheroptions['beforecontents'] : '';
        $aftercontents =  !empty($otheroptions) && array_key_exists ('aftercontents', $otheroptions) ? $otheroptions['aftercontents'] : '';
        $inputclass = !empty($otheroptions) && array_key_exists ('inputclass', $otheroptions) ? $otheroptions['inputclass'] : '';

        $inputsize = !empty($otheroptions) && array_key_exists ('inputsize', $otheroptions) ? $otheroptions['inputsize'] : $this->inputsize;

        $tag  = !empty($inputattributes) && array_key_exists ('tag', $inputattributes) ? $inputattributes['tag'] : 'input';

        $tagattributes['type'] = !empty($tagattributes) && array_key_exists ('type', $tagattributes) ? $tagattributes['type'] : 'text';
        $tagattributes['required'] = '';

        $input      = $this->tag($tag,$this->modalid . '__inp_' . $inputname, $inputsize .' form-control input-md ' . $inputclass, '', $tagattributes);
        $outdiv     = $this->tag('div', '', $inputsize, $input);
        $formg1     = $this->tag('div', '', 'form-group', $beforecontents . $outdiv . $aftercontents);
        return $formg1;
    }

    public function createFormSelectBasic($langstring, $inputname, array $selectionoptions = null, array $selectattribs = null, $class = '', array $otheroptions = null){

        $labelsize = !empty($otheroptions) && array_key_exists ('labelsize', $otheroptions) ? $otheroptions['labelsize'] : $this->labelsize;
        $inputsize = !empty($otheroptions) && array_key_exists ('inputsize', $otheroptions) ? $otheroptions['inputsize'] : $this->inputsize;

        $namelabel  = $this->tag('label', '', $labelsize . ' control-label', mlang_str($langstring, true), array('for' => $this->modalid . '__sel_' . $inputname));

        $select     = $this->createSelectBasic($inputname,$selectionoptions,$selectattribs,$class,$otheroptions);
        $outdiv     = $this->tag('div', '', $inputsize, $select);

        $formg1     = $this->tag('div', '', 'form-group', $namelabel . $outdiv);
        return $formg1;
    }

    public function createSelectBasic($inputname, array $selectionoptions = null, array $selectattribs = null, $class = '', array $otheroptions = null){

        $inputsize = !empty($otheroptions) && array_key_exists ('inputsize', $otheroptions) ? $otheroptions['inputsize'] : $this->inputsize;

        $options = '';
        foreach ((array)$selectionoptions as $key => $value) {
            $options .= $this->tag('option', '', $inputsize .' form-control input-md', $value, array('value' => $key) );
        }

        $outdiv      = $this->tag('select', $this->modalid . '__sel_' . $inputname, $inputsize .' form-control input-md ' . $class, $options, $selectattribs );
        return $outdiv;
    }

    public function createUserlist($inputname, $class = '', array $selectattribs = null, $contents = '' ){
        $outdiv = $this->tag('ul', $this->modalid . '__ul_' . $inputname, $class, $contents, $selectattribs );
        return $outdiv;
    }

    public function createCheckbox($langstring, $inputname, $labelclass ='', array $otheroptions = null, array $checkboxattribs = array() ){

        $beforecontents = !empty($otheroptions) && array_key_exists ('beforecontents', $otheroptions) ? $otheroptions['beforecontents'] : '';
        $aftercontents =  !empty($otheroptions) && array_key_exists ('aftercontents', $otheroptions) ? $otheroptions['aftercontents'] : '';
        $labelsize = !empty($otheroptions) && array_key_exists ('labelsize', $otheroptions) ? $otheroptions['labelsize'] : $this->labelsize;
        $inputsize = !empty($otheroptions) && array_key_exists ('inputsize', $otheroptions) ? $otheroptions['inputsize'] : $this->inputsize;

        $checkboxattribs = array_merge(array('type'=>'checkbox'),$checkboxattribs);

        $namelabel  = $this->tag('label', '', $labelsize . ' control-label ' . $labelclass, mlang_str($langstring, true), array('for' => $this->modalid . '__chk_' . $inputname));

        $input      = $this->tag('input', $this->modalid . '__chk_' . $inputname, '','', $checkboxattribs );
        $outdiv     = $this->tag('div', '',  $inputsize, $input);
        $formg1     = $this->tag('div', '', 'form-group',  $beforecontents . $namelabel . $outdiv . $aftercontents);
        return $formg1;
    }

    public function createJustText($langstring, $textid ='', $textclass = '', array $otheroptions = null, array $textattributes = null ){

        $textid = !empty($textid) ? $this->modalid . '__spn_' . $textid : '';

        $beforecontents = !empty($otheroptions) && array_key_exists ('beforecontents', $otheroptions) ? $otheroptions['beforecontents'] : '';
        $aftercontents =  !empty($otheroptions) && array_key_exists ('aftercontents', $otheroptions) ? $otheroptions['aftercontents'] : '';

        $textdiv  = $this->tag('span', $textid, $textclass, $beforecontents . mlang_str($langstring, true) . $aftercontents, $textattributes);
        return $textdiv;
    }

    public function newline(){
        $textdiv  = $this->tag('br','','');
        return $textdiv;
    }

    public function createLine($class ='', $attribs = null){
        $line  = $this->tag('hr', '',  $class, '',  $attribs);
        return $line;
    }

    public function createDiv($divid ='', $class ='', $contents = '', array $attribs = null){

        $id = !empty($divid) ? $this->modalid . '__div_' . $divid : '';

        $newDiv  = $this->tag('div', $id,  $class, $contents,  $attribs);
        return $newDiv;
    }

    public function createSpan($spanid ='', $class ='', $contents = '', array $attribs = null){

        $id = !empty($spanid) ? $this->modalid . '__spn_' . $spanid : '';

        $newSpan  = $this->tag('span', $id,  $class, $contents,  $attribs);
        return $newSpan;
    }

    public function createList($spanid ='', $class ='', $contents = '', array $attribs = null){

        $id = !empty($spanid) ? $this->modalid . '__spn_' . $spanid : '';

        $newSpan  = $this->tag('span', $id,  $class, $contents,  $attribs);
        return $newSpan;
    }

    public function includeScript($url){
        $script  = $this->tag('script', '',  '', '',  array('src'=>$url));
        return $script;
    }

    public function createButton($langstring, $inputname, $buttonclass ='', array $buttonattribs = null, $buttononclick = ''){

        $buttonattribs = array_merge(array('type'=>'button', 'onclick'=>$buttononclick), $buttonattribs);
        $button = $this->tag('button', $this->modalid . '__btn_' . $inputname, $buttonclass,mlang_str($langstring, true), $buttonattribs );
        return $button;
    }

    public function createLabel($langstring, $class = '', array $otheroptions = null, array $labelattributes = null){

        $labelsize = !empty($otheroptions) && array_key_exists ('labelsize', $otheroptions) ? $otheroptions['labelsize'] : $this->labelsize;
        $namelabel  = $this->tag('label', '', $labelsize . ' control-label ' . $class, mlang_str($langstring, true) , $labelattributes );

        return $namelabel;
    }

}


class Format
{
    private $input = '';
    private $output = '';
    private $tabs = 0;
    private $in_tag = FALSE;
    private $in_comment = FALSE;
    private $in_content = FALSE;
    private $inline_tag = FALSE;
    private $input_index = 0;

    public function HTML($input)
    {
        $this->input = $input;
        $this->output = '';

        $starting_index = 0;

        if (preg_match('/<\!doctype/i', $this->input)) {
            $starting_index = strpos($this->input, '>') + 1;
            $this->output .= substr($this->input, 0, $starting_index);
        }

        for ($this->input_index = $starting_index; $this->input_index < strlen($this->input); $this->input_index++) {
            if ($this->in_comment) {
                $this->parse_comment();
            } elseif ($this->in_tag) {
                $this->parse_inner_tag();
            } elseif ($this->inline_tag) {
                $this->parse_inner_inline_tag();
            } else {
                if (preg_match('/[\r\n\t]/', $this->input[$this->input_index])) {
                    continue;
                } elseif ($this->input[$this->input_index] == '<') {
                    if ( ! $this->is_inline_tag()) {
                        $this->in_content = FALSE;
                    }
                    $this->parse_tag();
                } elseif ( ! $this->in_content) {
                    if ( ! $this->inline_tag) {
                        $this->output .= "\n" . str_repeat("\t", $this->tabs);
                    }
                    $this->in_content = TRUE;
                }
                $this->output .= $this->input[$this->input_index];
            }
        }

        return $this->output;
    }

    private function parse_comment()
    {
        if ($this->is_end_comment()) {
            $this->in_comment = FALSE;
            $this->output .= '-->';
            $this->input_index += 3;
        } else {
            $this->output .= $this->input[$this->input_index];
        }
    }

    private function parse_inner_tag()
    {
        if ($this->input[$this->input_index] == '>') {
            $this->in_tag = FALSE;
            $this->output .= '>';
        } else {
            $this->output .= $this->input[$this->input_index];
        }
    }

    private function parse_inner_inline_tag()
    {
        if ($this->input[$this->input_index] == '>') {
            $this->inline_tag = FALSE;
            $this->decrement_tabs();
            $this->output .= '>';
        } else {
            $this->output .= $this->input[$this->input_index];
        }
    }

    private function parse_tag()
    {
        if ($this->is_comment()) {
            $this->output .= "\n" . str_repeat("\t", $this->tabs);
            $this->in_comment = TRUE;
        } elseif ($this->is_end_tag()) {
            $this->in_tag = TRUE;
            $this->inline_tag = FALSE;
            $this->decrement_tabs();
            if ( ! $this->is_inline_tag() AND ! $this->is_tag_empty()) {
                $this->output .= "\n" . str_repeat("\t", $this->tabs);
            }
        } else {
            $this->in_tag = TRUE;
            if ( ! $this->in_content AND ! $this->inline_tag) {
                $this->output .= "\n" . str_repeat("\t", $this->tabs);
            }
            if ( ! $this->is_closed_tag()) {
                $this->tabs++;
            }
            if ($this->is_inline_tag()) {
                $this->inline_tag = TRUE;
            }
        }
    }

    private function is_end_tag()
    {
        for ($input_index = $this->input_index; $input_index < strlen($this->input); $input_index++) {
            if ($this->input[$input_index] == '<' AND $this->input[$input_index + 1] == '/') {
                return true;
            } elseif ($this->input[$input_index] == '<' AND $this->input[$input_index + 1] == '!') {
                return true;
            } elseif ($this->input[$input_index] == '>') {
                return false;
            }
        }
        return false;
    }

    private function decrement_tabs()
    {
        $this->tabs--;
        if ($this->tabs < 0) {
            $this->tabs = 0;
        }
    }

    private function is_comment()
    {
        if ($this->input[$this->input_index] == '<'
            AND $this->input[$this->input_index + 1] == '!'
            AND $this->input[$this->input_index + 2] == '-'
            AND $this->input[$this->input_index + 3] == '-') {
            return true;
        } else {
            return false;
        }
    }

    private function is_end_comment()
    {
        if ($this->input[$this->input_index] == '-'
            AND $this->input[$this->input_index + 1] == '-'
            AND $this->input[$this->input_index + 2] == '>') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function is_tag_empty()
    {
        $current_tag = $this->get_current_tag($this->input_index + 2);
        $in_tag = FALSE;

        for ($input_index = $this->input_index - 1; $input_index >= 0; $input_index--) {
            if ( ! $in_tag) {
                if ($this->input[$input_index] == '>') {
                    $in_tag = TRUE;
                } elseif ( ! preg_match('/\s/', $this->input[$input_index])) {
                    return FALSE;
                }
            } else {
                if ($this->input[$input_index] == '<') {
                    if ($current_tag == $this->get_current_tag($input_index + 1)) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                }
            }
        }
        return TRUE;
    }

    private function get_current_tag($input_index)
    {
        $current_tag = '';

        for ($input_index; $input_index < strlen($this->input); $input_index++) {
            if ($this->input[$input_index] == '<') {
                continue;
            } elseif ($this->input[$input_index] == '>' OR preg_match('/\s/', $this->input[$input_index])) {
                return $current_tag;
            } else {
                $current_tag .= $this->input[$input_index];
            }
        }
        return $current_tag;
    }

    private function is_closed_tag()
    {
        $closed_tags = array(
            'meta', 'link', 'img', 'hr', 'br', 'input',
        );

        $current_tag = '';

        for ($input_index = $this->input_index; $input_index < strlen($this->input); $input_index++) {
            if ($this->input[$input_index] == '<') {
                continue;
            } elseif (preg_match('/\s/', $this->input[$input_index])) {
                break;
            } else {
                $current_tag .= $this->input[$input_index];
            }
        }

        if (in_array($current_tag, $closed_tags)) {
            return true;
        } else {
            return false;
        }
    }

    private function is_inline_tag()
    {
        $inline_tags = array(
            'title', 'a', 'span', 'abbr', 'acronym', 'b', 'basefont', 'bdo', 'big', 'cite', 'code', 'dfn', 'em', 'font', 'i', 'kbd', 'q', 's', 'samp', 'small', 'strike', 'strong', 'sub', 'sup', 'textarea', 'tt', 'u', 'var', 'del', 'pre',
        );

        $current_tag = '';

        for ($input_index = $this->input_index; $input_index < strlen($this->input); $input_index++) {
            if ($this->input[$input_index] == '<' OR $this->input[$input_index] == '/') {
                continue;
            } elseif (preg_match('/\s/', $this->input[$input_index]) OR $this->input[$input_index] == '>') {
                break;
            } else {
                $current_tag .= $this->input[$input_index];
            }
        }

        if (in_array($current_tag, $inline_tags)) {
            return true;
        } else {
            return false;
        }
    }
}
