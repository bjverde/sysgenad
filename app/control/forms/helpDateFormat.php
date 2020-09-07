<?php
class helpDateFormat extends TPage
{

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        $this->adianti_target_container = 'adianti_right_panel';

        TPage::include_css('app/resources/styles.css');
        $this->html = new THtmlRenderer('app/resources/sysgen_dateformat_pt-br.html');        
        $replace = array(); // define replacements for the main section        
        $this->html->enableSection('main', $replace); // replace the main section variables

        $frm = new TFormDin($this,Message::BUTTON_LABEL_HELP_DATEFORMAT);
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

}