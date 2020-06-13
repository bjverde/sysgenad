<?php
class Gen01 extends TPage
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
            $DBMS = TSession::getValue('DBMS');
            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP01);
            
            $frm = new TFormDin(Message::GEN01_TITLE);

            $frm->addGroupField('gpx1', Message::GEN01_GPX1_TITLE);
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br><b>Extensões PHP necessárias para o correto funcionamento:</b><br>');
                $DBMS = TSession::getValue('DBMS');
                $validoPDOAndDBMS = TGeneratorHelper::validatePDOAndDBMS($DBMS['TYPE'], $html);
            $frm->closeGroup();
        
            $frm->addGroupField('gpxHelp', Message::GEN00_GPX3_TITLE);
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br>'.Message::INFO_CONNECT.'<br>');
            $frm->closeGroup();
    
    
            //$formDin->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType, null, null, null, null, null, null, ' ', 0);
            $dbType = FormDinHelper::getListDBMS();
            $frm->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType);
            $frm->addTextField('GEN_SYSTEM_ACRONYM','Sigla do Sistema', 50, true);
            $frm->addMaskField('GEN_SYSTEM_VERSION', 'Versão do sistema',true,'9.9.9');
            $frm->addTextField('GEN_SYSTEM_NAME', 'Nome do sistem', 50, true);
    
            // creates a frame
            $frame = new TFrame;
            $frame->oid = 'frame-measures';
            $frame->setLegend('Measures');
    
            $filed1      = new TEntry('entry');
            $filed1Label = new TLabel('TEntry');
    
            $filed2      = new TEntry('entry');
            $filed2Label = new TLabel('TEntry');
    
            //$frame->add([ $filed1Label ],   [ $filed1 ]);
            $frame->add( $filed1Label );
            $frame->add( $filed1 );
            $frame->add( '<br>' );
            $frame->add( $filed2Label );
            $frame->add( $filed2 );
            
    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',$this,false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',$this,false,'fa:eraser','red');
            $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'onSave',$this,false,'fa:chevron-circle-right','green');
    
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
        AdiantiCoreApplication::loadPage('Gen00');
    }
    
    /**
     * Clear filters
     */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }

    public function onSave($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);

        //Função do FormDin para Debug
        FormDinHelper::d($param,'$param');
        FormDinHelper::debug($data,'$data');
        FormDinHelper::debug($_REQUEST,'$_REQUEST');

        TSession::setValue('registration_course', ['course_id' => $param['code'],
        'course_description' => $param['description']] );

        AdiantiCoreApplication::loadPage('MultiStepRegistration3View');
    }
}