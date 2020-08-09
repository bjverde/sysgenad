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
            }


            foreach ($listTables['TABLE_NAME'] as $key=>$table){
                $tableSchema = $listTables['TABLE_SCHEMA'][$key];
                $tableType   = $listTables['TABLE_TYPE'][$key];
                $listFieldsTable = TGeneratorHelper::loadFieldsTablesSelectedWithFormDin($table,$tableType,$tableSchema);
                
                $tableType = strtoupper($listTables['TABLE_TYPE'][$key]);
                $key = $key + 1;
                if($tableType == TableInfo::TB_TYPE_TABLE){
                    TGeneratorHelper::createFilesFormControllerModelFromTable($table, $listFieldsTable ,$tableSchema ,$tableType);
                    $html->add('<br>'.$key.Message::CREATED_TABLE_ITEN.$table);
                }else{
                    TGeneratorHelper::createFilesFormControllerModelFromTable($table, $listFieldsTable ,$tableSchema ,$tableType);
                    $html->add('<br>'.$key.Message::CREATED_VIEW_ITEN.$table);
                }
                
                /*
                $grid = new TFormDinGrid($this
                                   ,'gd.$table'      // id do gride
                                   ,$key.Message::FIELDS_TABLE_VIEW.$table   // titulo do gride
                                   ,$listFieldsTable 	      // array de dados
                                   );

                $this->datagrid = $grid->show();
                $panelGrid = $grid->getPanelGroupGrid();
                $this->form->addContent([$panelGrid]);
                */
            }


    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');

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
        AdiantiCoreApplication::loadPage('Gen03');
    }
    
    /**
     * Clear filters
     */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
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