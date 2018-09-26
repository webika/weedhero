<?php
class EFax
{
    private $url='https://www.faxage.com/httpsfax.php';
    public $account_id;
    public $response;
    public $xml_str;
    public $user_name;
    public $password;
    public $recipient_company_name;
    public $recipient_fax;
    public $message;
    public $code;
    public $status;
    public $error_level;
    public $error_message;

    public function setParams($name,$password,$account_id,$fax_number,$company_name){
        $this->account_id=$account_id;
        $this->user_name=$name;
        $this->password=$password;
        $this->recipient_fax=$fax_number;
        $this->recipient_company_name=$company_name;
        $this->code='';
        $this->status='';
        $this->error_level='';
        $this->error_message='';
    }

    private function buildXML(){
        YiiBase::import('application.extensions.efax.XmlGenerator.php');
        //-----------------------------------------------
        $xmlw=new XmlGenerator();
		$xmlw->XmlWriter();
        $xmlw->push('OutboundRequest');
            $xmlw->push('AccessControl');
                $xmlw->element('UserName', $this->user_name);
                $xmlw->element('Password', $this->password);
            $xmlw->pop();
            $xmlw->push('Transmission');
                $xmlw->push('TransmissionControl');
                    $xmlw->element('Resolution', 'STANDARD');
                    $xmlw->element('FaxHeader', '@DATE1 @TIME3 @ROUTETO{45} %P/@SPAGES');
                $xmlw->pop();
                $xmlw->push('DispositionControl');
                    $xmlw->element('DispositionLevel', 'NONE');
                $xmlw->pop();
                $xmlw->push('Recipients');
                    $xmlw->push('Recipient');
                        $xmlw->element('RecipientCompany', $this->recipient_company_name);
                        $xmlw->element('RecipientFax', $this->recipient_fax);
                    $xmlw->pop();
                $xmlw->pop();
                $xmlw->push('Files');
                    $xmlw->push('File');
                        $xmlw->element('FileContents', base64_encode($this->message));
                        $xmlw->element('FileType', 'html');
                    $xmlw->pop();
                $xmlw->pop();
            $xmlw->pop();
        $xmlw->pop();
        //-----------------------------------------------
        $this->xml_str=$xmlw->getXml();
    }

    public function sendFax($message)
    {
        $this->message=$message;
        //$this->buildXML();

        $post_data = array('username'=>$this->user_name,'company' => $this->account_id,'password'=>$this->password,'recipname'=>$this->recipient_company_name,
            'faxno'=>$this->recipient_fax,'operation'=>'sendfax','faxfilenames[0]'=>'Invoice.htm','faxfiledata[0]'=>base64_encode($this->message));

        $curl_request = curl_init($this->url);

        curl_setopt($curl_request, CURLOPT_POST, true);
        curl_setopt($curl_request, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($curl_request, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($curl_request, CURLOPT_HEADER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_TIMEOUT, 45);

        $this->response = curl_exec($curl_request);

        curl_close($curl_request);
    }
}
?>
