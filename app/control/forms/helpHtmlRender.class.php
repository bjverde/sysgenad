<?php
class helpHtmlRender extends TPage
{
    const HTML_EASY = 'html_easy';
    const HTML_DATE_FORMAT = 'html_data_format';
    const HTML_TP_GRID = 'html_grid';

    private static $formId = 'form_helpHtmlRender';
    protected $form; //Registration form Adianti
    protected $frm;  //Registration component FormDin 5
    protected $html;
    protected $adianti_target_container;

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

        $frm = new TFormDin($this,$this->getFormTitle(),null,null,self::$formId);
        $frm->addHiddenField('id');  //POG para evitar problema de noticie
        $frm->addHiddenField('html');

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
        TSession::setValue('HELP_HTML',self::HTML_TP_GRID);        
        $obj = new StdClass;
        $obj->html = self::HTML_EASY;
        TForm::sendData(self::$formId, $obj);
        $this->onReload();      
    }
    public function loadDataFormt()
    {
        TSession::setValue('HELP_HTML',self::HTML_TP_GRID);        
        $obj = new StdClass;
        $obj->html = self::HTML_DATE_FORMAT;
        TForm::sendData(self::$formId, $obj);
        $this->onReload();
    }
    public function loadGrid()
    {
        TSession::setValue('HELP_HTML',self::HTML_TP_GRID);        
        $obj = new StdClass;
        $obj->html = self::HTML_TP_GRID;
        TForm::sendData(self::$formId, $obj);
        $this->onReload();
    }
    public function getFormTitle()
    {
        FormDinHelper::debug($this->form,'form');
        //$data = $this->form->getData();
        //FormDinHelper::debug($data,'getFormTitle');
        $result = 'app/resources/sysgen_easylabel_pt-br.html';
        //$html = TSession::getValue('HELP_HTML');
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
        //FormDinHelper::debug($html,'Resource');
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