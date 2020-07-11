<?php
class Gen02 extends TPage
{

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
            FormDinHelper::debug($_SESSION,'$_SESSION');

            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP02);
            
            $frm = new TFormDin(Message::GEN02_TITLE);

            $frm->addGroupField('gpx1', Message::GEN02_GPX1_TITLE);
                $html = $frm->addHtmlField('conf', '');

                try {
                    $listTablesAll = TGeneratorHelper::loadTablesFromDatabase();
                    
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
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $frm->setMessage($e->getMessage(),TFormDinMessage::TYPE_ERROR);
                }                
            $frm->closeGroup();
    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',$this,false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',$this,false,'fa:eraser','red');

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