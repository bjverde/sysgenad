<?php
class Gen04 extends TPage
{
    protected $form; // registration form
    protected $datagrid; // listing
    protected $pageNavigation;

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        try
        {   
            
            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP04);
            
            $frm = new TFormDin($this,Message::GEN04_TITLE);

            $frm->addGroupField('gpx1',Message::GEN02_GPX1_TITLE);
                $html = $frm->addHtmlField('conf','');
            $frm->closeGroup();

            $listTables = TGeneratorHelper::loadTablesSelected();
            $tpSystem   = TSysgenSession::getValue(TableInfo::TP_SYSTEM);

            FormDinHelper::debug($listTables);
            
            if( $tpSystem != TGeneratorHelper::TP_SYSTEM_FORM ){
                TGeneratorHelper::createApiIndexAndRouter($listTables);
                $html->add(TGeneratorHelper::showMsg(true, Message::CREATED_API_INDEX));
                
                $html->add('<a href="'.TGeneratorHelper::getUrlNewSystem().'/api" target="_blank">'.TGeneratorHelper::getUrlNewSystem().'/api </a>');
                $html->add('<br>');
            }

            if( $tpSystem != TGeneratorHelper::TP_SYSTEM_REST ){
                TGeneratorHelper::createFileMenu($listTables);
                $html->add(TGeneratorHelper::showMsg(true, Message::CREATED_MENU));
                
                $html->add(TGeneratorHelper::showMsg(true,Message::NEW_SYSTEM_OK));
                $html->add('<a href="'.TGeneratorHelper::getUrlNewSystem().'" target="_blank">'.TGeneratorHelper::getUrlNewSystem().'</a>');
                $html->add('<br>');

                TGeneratorHelper::copyAdiantiToNewSystem('cmd.php');
                TGeneratorHelper::copyAdiantiToNewSystem('vendor');
            }
    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');
            $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'next',false,'fa:chevron-circle-right','green');

            $this->form = $frm->show();

            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add( $pagestep );
            $vbox->add( $this->form );
            parent::add($vbox);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            FormDinHelper::debug($e,'$e');
        }
    }

    public function back()
    {
        AdiantiCoreApplication::loadPage('Gen02');
    }
    
    /**
     * Clear filters
     */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }

    public function next($param)
    {
        try {
            $data = $this->form->getData(); // optional parameter: active record class
            $this->form->setData($data);    // put the data back to the form
            AdiantiCoreApplication::loadPage('Gen04'); //POG para recarregar a pagina
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {

    }

    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}