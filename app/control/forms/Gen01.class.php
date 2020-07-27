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
            $DBMS = TSession::getValue('DBMS');
            $DBMS_TYPE  = $DBMS['TYPE'];

            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP01);
            
            $frm = new TFormDin($this,Message::GEN01_TITLE);

            $frm->addGroupField('gpx1', Message::GEN01_GPX1_TITLE);
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br><b>Extensões PHP necessárias para o correto funcionamento:</b><br>');
                $validoPDOAndDBMS = TGeneratorHelper::validatePDOAndDBMS($DBMS_TYPE, $html);
            $frm->closeGroup();

            if ($validoPDOAndDBMS) {

                $frm->addGroupField('gpxHelp', Message::GEN00_GPX3_TITLE);
                    $html = $frm->addHtmlField('conf', '');
                    $html->add('<br>'.Message::INFO_CONNECT.'<br>');
                $frm->closeGroup();

                $frm->addGroupField('gpx2', Message::GEN01_GPX2_TITLE);
                    $frm->addHiddenField('TYPE', $DBMS_TYPE);
                    $frm->addHiddenField('PREP', 1);
                    if($DBMS_TYPE == TFormDinPdoConnection::DBMS_MYSQL){
                        $listMyDbVersion = array(TableInfo::DBMS_VERSION_MYSQL_8_GTE=>TableInfo::DBMS_VERSION_MYSQL_8_GTE_LABEL
                                                ,TableInfo::DBMS_VERSION_MYSQL_8_LT =>TableInfo::DBMS_VERSION_MYSQL_8_LT_LABEL
                                            );
                        //$frm->addSelectField('myDbVersion', 'Escolha a versão do DBMS:', true, $listMyDbVersion, null, null, null, null, null, null, ' ');    
                        $frm->addTextField('HOST', 'Host:'    , 20, true, 20, '127.0.0.1'   , true, null, null, true);
                        $frm->addTextField('DATABASE'  , 'Database:', 20, true, 20, 'form_exemplo',false, null, null, true);
                        
                        $frm->addTextField('USER', 'User:'    , 40, true, 20, 'form_exemplo', true, null, null, true);
                        $frm->addTextField('PASSWORD', 'Password:', 40, true, 20, '123456'      ,false, null, null, true);
                        $frm->addTextField('PORT', 'Porta:'   , 6 ,false, 6 , '3306'        ,false, null, null, true, false);
                        $frm->addHiddenField('SCHEMA');
                        $frm->addHiddenField('VERSION');
                        //$frm->addButton(Message::BUTTON_LABEL_TEST_CONNECT, null, 'btnTestarmy', 'testarConexao("my")', null, true, false);
                        $frm->addHtmlField('myGride', '');
                    }elseif($DBMS_TYPE == TFormDinPdoConnection::DBMS_SQLITE){
                        $frm->addHiddenField('HOST');
                        $frm->addHiddenField('PORT');
                        $value = __DIR__.DS.'..'.DS.'..'.DS.'database'.DS.'bdApoio.s3db';
                        $frm->addTextField('DATABASE', 'Database:', 80, true, 80, $value);
                        $frm->addHiddenField('USER');
                        $frm->addHiddenField('PASSWORD');
                        $frm->addHiddenField('SCHEMA');
                        $frm->addHiddenField('VERSION');
                    }
                $frm->closeGroup();
                $frm->addGroupField('gpx3');
                    $frm->addButton(Message::BUTTON_LABEL_TEST_CONNECT,null,'testConnection',null,null,true,false,'fas:fa-plug green');
                $frm->closeGroup();
            }
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');
            
            if ($validoPDOAndDBMS && ArrayHelper::has('USER', $DBMS) ) {
                $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'next',false,'fa:chevron-circle-right','green');
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
            $data = $this->form->getData(); // obtém os dados do formulário
            $this->form->setData($data);// mantém o form preenchido

            $connectOK = ManualConnection::testConnection($param);

            if ($connectOK instanceof PDO) {
                $_SESSION[APPLICATION_NAME]['DBMS']['USER'] = $param['USER'];
                $_SESSION[APPLICATION_NAME]['DBMS']['PASSWORD'] = $param['PASSWORD'];
                $_SESSION[APPLICATION_NAME]['DBMS']['DATABASE'] = $param['DATABASE'];
                $_SESSION[APPLICATION_NAME]['DBMS']['HOST']     = $param['HOST'];
                $_SESSION[APPLICATION_NAME]['DBMS']['PORT']     = $param['PORT'];
                $_SESSION[APPLICATION_NAME]['DBMS']['SCHEMA']   = $param['SCHEMA'];
                $_SESSION[APPLICATION_NAME]['DBMS']['VERSION']  = $param['VERSION'];

                //MSG depois do loadPage para evitar o carregando 2x
                $text[] = Message::MSG_TEST_CONNECT;
                $text = TFormDinMessage::messageTransform($text);
                new TMessage(TFormDinMessage::TYPE_INFO, $text);
                AdiantiCoreApplication::loadPage('Gen01','onLoadFromSession'); //POG para recarregar a pagina
            }
        } catch (Exception $e) {
            $text[] = $e->getMessage();
            $text = TFormDinMessage::messageTransform($text);
            new TMessage(TFormDinMessage::TYPE_ERROR, $text);
        }

    }

    public function onLoadFromSession()
    {
        $data = TSession::getValue('DBMS');
        
        // monta um objeto para enviar dados após o post
        $obj = new StdClass;
        $obj->TYPE = $data['TYPE'];
        $obj->USER = $data['USER'];
        $obj->PASSWORD = $data['PASSWORD'];
        $obj->DATABASE = $data['DATABASE'];
        $obj->HOST = $data['HOST'];
        $obj->PORT = $data['PORT'];
        $obj->SCHEMA = $data['SCHEMA'];
        $obj->VERSION = $data['VERSION'];

        $this->form->setData($obj);// mantém o form preenchido
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
                AdiantiCoreApplication::loadPage('Gen02');
            }
        }//Fim test user
    }
}