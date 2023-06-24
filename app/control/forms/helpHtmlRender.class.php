<?php
class helpHtmlRender extends TPage
{
    const HTML_EASY = 'html_easy';
    const HTML_DATE_FORMAT = 'html_data_format';
    const HTML_TP_GRID = 'html_grid';

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Constructor method
     */
    public function __construct($param = null)
    {
        parent::__construct();
        $this->adianti_target_container = 'adianti_right_panel';

        TPage::include_css('app/resources/styles.css');
        $this->html = new THtmlRenderer($this->getHtmlResource());
        $replace = array(); // define replacements for the main section        
        $this->html->enableSection('main', $replace); // replace the main section variables

        $frm = new TFormDin($this,$this->getFormTitle());
        $frm->addHiddenField('id'); //POG para evitar problema de noticie

        // O Adianti permite a Internacionalização - A função _t('string') serve
        //para traduzir termos no sistema. Veja ApplicationTranslator escrevendo
        //primeiro em ingles e depois traduzindo
        //$frm->setActionLink( _t('Close'), 'onClose', null, 'fa:times', 'red');
        $frm->setActionHeaderLink( _t('Close'), 'onClose', null, 'fa:times', 'red');

        $this->form = $frm->show();
        
        // add the table inside the page
        parent::add($this->form);
        parent::add($this->html);
    }    

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }    
    /**
     * Clear filters
     */
    public function onClear()
    {
        $this->clearFilters();
        $this->onReload();
    }
    public function loadEasyLabel()
    {
        TSession::setValue('HELP_HTML',self::HTML_EASY);
        $this->onReload();
    }
    public function loadDataFormt()
    {
        TSession::setValue('HELP_HTML',self::HTML_DATE_FORMAT);
        $this->onReload();
    }
    public function loadGrid()
    {
        TSession::setValue('HELP_HTML',self::HTML_TP_GRID);
        $this->onReload();
    }
    public function getFormTitle()
    {
        $result = 'app/resources/sysgen_easylabel_pt-br.html';
        $html = TSession::getValue('HELP_HTML');
        if ($html == self::HTML_EASY) {
            $result = 'app/resources/sysgen_easylabel_pt-br.html';
        }elseif($html == self::HTML_DATE_FORMAT){
            $result = Message::BUTTON_LABEL_HELP_DATEFORMAT;
        }elseif($html == self::HTML_TP_GRID){
            $result = Message::BUTTON_LABEL_HELP_DATEFORMAT;
        }
        return $result;
    }
    public function getHtmlResource()
    {
        $result = 'app/resources/sysgen_easylabel_pt-br.html';
        $html = TSession::getValue('HELP_HTML');
        if ($html == self::HTML_EASY) {
            $result = 'app/resources/sysgen_easylabel_pt-br.html';
        }elseif($html == self::HTML_DATE_FORMAT){
            $result = 'app/resources/sysgen_dateformat_pt-br.html';
        }elseif($html == self::HTML_TP_GRID){
            $result = 'app/resources/sysgen_tdgrid_pt-br.html';
        }
        return $result;
    }
}