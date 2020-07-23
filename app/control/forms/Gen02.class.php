<?php
class Gen02 extends TPage
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

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP02);
            
            $frm = new TFormDin($this,Message::GEN02_TITLE);

            $frm->addGroupField('gpx1', Message::GEN02_GPX1_TITLE);
            $html = $frm->addHtmlField('conf', '');

            $listTablesAll = TGeneratorHelper::loadTablesFromDatabase();            
            $listTablesAll = ArrayHelper::convertArrayFormDin2Adianti($listTablesAll);

            FormDinHelper::debug($listTablesAll,'$listTablesAll');
            
            $path = TGeneratorHelper::getPathNewSystem();
            TGeneratorHelper::mkDir($path);
            $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_MKDIR_SYSTEM.$path));
            TGeneratorHelper::copySystemSkeletonToNewSystem();
            $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_COPY_SYSTEM_SKELETON));
            TGeneratorHelper::createFileConstants();
            $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_CONSTANTS));
            TGeneratorHelper::createFileConfigDataBase();
            $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_CONFIG_DATABASE));
            //TGeneratorHelper::createFileAutoload();
            //$html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_AUTOLOAD));
            //TGeneratorHelper::createFileIndex();
            //$html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_INDEX));
            $html->add('<br>');
            $html->add('<br>');
            $html->add(Message::SEL_TABLES_GENERATE);              
            $frm->closeGroup();
    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');

            $this->form = $frm->show();

            $grid = new TFormDinGrid($this
                                    ,'gd'               // id do gride
                                    ,'Lista de Tabelas' // titulo do gride
                                    , $listTablesAll    // array de dados
                                );    
            //$grid->setHeight(2500);
            $grid->addColumn('idSelected',  'Code', null, 'center');
            $grid->addColumn('TABLE_SCHEMA', 'TABLE_SCHEMA');
            $grid->addColumn('TABLE_NAME', 'TABLE_NAME');
            $grid->addColumn('COLUMN_QTD', 'COLUMN_QTD');
            $grid->addColumn('TABLE_TYPE', 'TABLE_TYPE');
            $this->datagrid = $grid->show();
            $panel = $grid->getPanelGroupGrid();


/*
    $gride = new TGrid('gd'                 // id do gride
                      , 'Lista de Tabelas'  // titulo do gride
                      , $listTablesAll);    // array de dados

    $gride->setCreateDefaultEditButton(false);
    $gride->setCreateDefaultDeleteButton(false);
    $gride->addRowNumColumn();
    $gride->addColumn('TABLE_SCHEMA', 'TABLE_SCHEMA');
    $gride->addCheckColumn('idTableSelected', 'TABLE_NAME', 'idSelected', 'TABLE_NAME', true, true);
    $gride->addColumn('COLUMN_QTD', 'COLUMN_QTD');
    $gride->addColumn('TABLE_TYPE', 'TABLE_TYPE');
*/

            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add( $pagestep );
            $vbox->add( $this->form );
            $vbox->add( $panel );
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
        AdiantiCoreApplication::loadPage('Gen01');
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
            AdiantiCoreApplication::loadPage('Gen02');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

    }
}