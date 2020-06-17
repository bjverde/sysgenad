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
            $DBMS_TYPE  = $DBMS['TYPE'];

            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP02);
            
            $frm = new TFormDin(Message::GEN01_TITLE);

            $frm->addGroupField('gpx1', Message::GEN01_GPX1_TITLE);
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br><b>Extensões PHP necessárias para o correto funcionamento:</b><br>');
                $validoPDOAndDBMS = TGeneratorHelper::validatePDOAndDBMS($DBMS_TYPE, $html);
            $frm->closeGroup();
        
            $frm->addGroupField('gpxHelp', Message::GEN00_GPX3_TITLE);
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br>'.Message::INFO_CONNECT.'<br>');
            $frm->closeGroup();
            if ($validoPDOAndDBMS) {
                $frm->addGroupField('gpx2', Message::GEN01_GPX2_TITLE);
                    $frm->addHiddenField('type', $DBMS_TYPE);
                    $frm->addHiddenField('prep', 1);
                    if($DBMS_TYPE == FormDinHelper::DBMS_MYSQL){
                        $frm->addHiddenField('myDbType', FormDinHelper::DBMS_MYSQL);
                        $listMyDbVersion = array(TableInfo::DBMS_VERSION_MYSQL_8_GTE=>TableInfo::DBMS_VERSION_MYSQL_8_GTE_LABEL
                                                ,TableInfo::DBMS_VERSION_MYSQL_8_LT =>TableInfo::DBMS_VERSION_MYSQL_8_LT_LABEL
                                            );
                        //$frm->addSelectField('myDbVersion', 'Escolha a versão do DBMS:', true, $listMyDbVersion, null, null, null, null, null, null, ' ');    
                        $frm->addTextField('myHost', 'Host:'    , 20, true, 20, '127.0.0.1'   , true, null, null, true);
                        $frm->addTextField('myDb'  , 'Database:', 20, true, 20, 'form_exemplo',false, null, null, true);
                        
                        $frm->addTextField('myUser', 'User:'    , 40, true, 20, 'form_exemplo', true, null, null, true);
                        $frm->addTextField('myPass', 'Password:', 40, true, 20, '123456'      ,false, null, null, true);
                        $frm->addTextField('myPort', 'Porta:'   , 6 ,false, 6 , '3306'        ,false, null, null, true, false);
                        //$frm->addButton(Message::BUTTON_LABEL_TEST_CONNECT, null, 'btnTestarmy', 'testarConexao("my")', null, true, false);
                        $frm->addHtmlField('myGride', '');
                    }elseif($DBMS_TYPE == FormDinHelper::DBMS_SQLITE){
                        $frm->addHiddenField('host');
                        $frm->addHiddenField('port');
                        $frm->addTextField('name', 'Database:', 80, true, 80, __DIR__.DS.'..'.DS.'..'.DS.'database'.DS.'bdApoio.s3db', false, null, null, false);
                        $frm->addHiddenField('user');
                        $frm->addHiddenField('pass');
                    }
                $frm->closeGroup();                
            }
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',$this,false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',$this,false,'fa:eraser','red');
            
            if ($validoPDOAndDBMS) {
                $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'next',$this,false,'fa:chevron-circle-right','green');
            }
    
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

    public function next($param)
    {
        try {
            //$data = $this->form->getData();
            //$this->form->setData($data);
    
            //Função do FormDin para Debug
            //FormDinHelper::d($param,'$param');
            //FormDinHelper::debug($data,'$data');
            //FormDinHelper::debug($_REQUEST,'$_REQUEST');
            //FormDinHelper::debug($_SESSION,'$_SESSION');

            ManualConnection::testConnection($param);

            TSession::setValue('DBMS',['TYPE'=>RequestHelper::get('DBMS')]);
            TSession::setValue('GEN_SYSTEM_ACRONYM',$GEN_SYSTEM_ACRONYM);
            TSession::setValue('GEN_SYSTEM_VERSION',RequestHelper::get('GEN_SYSTEM_VERSION'));
            TSession::setValue('GEN_SYSTEM_NAME',RequestHelper::get('GEN_SYSTEM_NAME'));
            //TSession::setValue(TableInfo::TP_SYSTEM,RequestHelper::get(TableInfo::TP_SYSTEM));
            //TSession::setValue('EASYLABEL',RequestHelper::get('EASYLABEL'));
            AdiantiCoreApplication::loadPage('Gen02');
        } catch (Exception $e) {
            $frm->setMessage($e->getMessage());
        }

    }
}