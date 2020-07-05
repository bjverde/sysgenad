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

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP01);
            
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
                        $frm->addTextField('host', 'Host:'    , 20, true, 20, '127.0.0.1'   , true, null, null, true);
                        $frm->addTextField('name'  , 'Database:', 20, true, 20, 'form_exemplo',false, null, null, true);
                        
                        $frm->addTextField('user', 'User:'    , 40, true, 20, 'form_exemplo', true, null, null, true);
                        $frm->addTextField('pass', 'Password:', 40, true, 20, '123456'      ,false, null, null, true);
                        $frm->addTextField('port', 'Porta:'   , 6 ,false, 6 , '3306'        ,false, null, null, true, false);
                        //$frm->addButton(Message::BUTTON_LABEL_TEST_CONNECT, null, 'btnTestarmy', 'testarConexao("my")', null, true, false);
                        $frm->addHtmlField('myGride', '');
                    }elseif($DBMS_TYPE == FormDinHelper::DBMS_SQLITE){
                        $frm->addHiddenField('host');
                        $frm->addHiddenField('port');
                        $value = __DIR__.DS.'..'.DS.'..'.DS.'database'.DS.'bdApoio.s3db';
                        $frm->addTextField('name', 'Database:', 80, true, 80, $value);
                        $frm->addHiddenField('user');
                        $frm->addHiddenField('pass');
                    }
                $frm->closeGroup();
                $frm->addGroupField('gpx3');
                    $frm->addButton($this,Message::BUTTON_LABEL_TEST_CONNECT,null,'testConnection',null,null,true,false,'fas:fa-plug green');
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

    public function testConnection($param)
    {
        try {
            //$data = $this->form->getData();
            //$this->form->setData($data);
    
            //Função do FormDin para Debug
            FormDinHelper::debug($param,'$param');
            //FormDinHelper::debug($data,'$data');
            //FormDinHelper::debug($_REQUEST,'$_REQUEST');
            //FormDinHelper::debug($_SESSION,'$_SESSION');

            $connectOK = ManualConnection::testConnection($param);

            if ($connectOK instanceof PDO) {
                $_SESSION[APPLICATION_NAME]['DBMS']['USER'] = $param['user'];
                $_SESSION[APPLICATION_NAME]['DBMS']['PASSWORD'] = $param['pass'];
                $_SESSION[APPLICATION_NAME]['DBMS']['DATABASE'] = $param['name'];
                $_SESSION[APPLICATION_NAME]['DBMS']['HOST']     = $param['host'];
                $_SESSION[APPLICATION_NAME]['DBMS']['PORT']     = $param['port'];
                //$_SESSION[APPLICATION_NAME]['DBMS']['SCHEMA']   = $param['name'];
                //$_SESSION[APPLICATION_NAME]['DBMS']['VERSION']  = $param['name'];

                //AdiantiCoreApplication::loadPage('Gen02');
                $text[] = Message::MSG_TEST_CONNECT;
                $text = TFormDinMessage::messageTransform($text);
                new TMessage(TFormDinMessage::TYPE_INFO, $text);
            }
        } catch (Exception $e) {
            $text[] = $e->getMessage();
            $text = TFormDinMessage::messageTransform($text);
            new TMessage(TFormDinMessage::TYPE_ERROR, $text);
        }

    }

    public function next()
    {
        if (!ArrayHelper::has('USER', $_SESSION[APPLICATION_NAME]['DBMS'])) {
            $text[] = Message::GEN02_NOT_READY;
            $text = TFormDinMessage::messageTransform($text);
            new TMessage(TFormDinMessage::TYPE_ERROR, $text);
        }else{
            $dbType   = $_SESSION[APPLICATION_NAME]['DBMS']['TYPE'];
            if( TableInfo::getDbmsWithVersion($dbType) ){
                $banco    = TableInfo::getPreDBMS($dbType);
                $dbversion= PostHelper::get($banco.'DbVersion');
                if (empty($dbversion)){
                    $text[] = Message::WARNING_NO_DBMS_VER;
                    $text = TFormDinMessage::messageTransform($text);
                    new TMessage(TFormDinMessage::TYPE_WARING, $text);
                }else{
                    $_SESSION[APPLICATION_NAME]['DBMS']['VERSION']  =  $dbversion;
                    AdiantiCoreApplication::loadPage('Gen02');
                }
            } else {
                $frm->redirect('gen02.php', null, true);
            }
        }//Fim test user
    }
}