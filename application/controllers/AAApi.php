<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller
{

    var $data;

    function __construct()
    {
        parent::__construct();

        header('Access-Control-Allow-Origin: *');
        $this->lang->load('auth');
        $this->load->library('ion_auth');
        $this->load->model('ion_auth_model', 'auth');
    }


    function index(){
        exit('-');
    }



    function details($comp = ""){
        header('Access-Control-Allow-Origin: *');

        $sql = $this->db->select('created_at_long, volume')
            ->from('real_time_price')
            ->where(array('csi' => $comp))
            ->order_by('id', 'asc')
            ->get();

        $data = array();
        foreach($sql->result() as $row){
            $data[] = [$row->created_at_long, $row->volume];
        }

        echo json_encode(array('data' => $data));
    }



    function snapshot($comp = ""){

        if($comp != ""){
            $this->db->where('ccode', $comp);
        }


        $sql = $this->db->select('name,price,prev_price,co_initials,ccode,last_update')->from('snapshot')->order_by('id', 'desc')->group_by('co_initials')->get();

        $resp = array();

        $resp['date'] = date('Y-m-d H:i:s');
        foreach($sql->result_array() as $row){
            unset($row['id']);
            unset($row['created_date']);
            $resp['data'][] = $row;
        }

        exit(json_encode($resp));
    }

    function recorddata(){

        $nameName = '{"ARM":{"name":"ARM Cement","initials":"ARM","catt":"Industrials","ccode":"KE0000000034"},"BOC":{"name":"B O C Kenya","initials":"BOC","catt":"Basic Materials","ccode":"KE0000000042"},"BAMB":{"name":"Bamburi Cement","initials":"BAMB","catt":"Industrials","ccode":"KE0000000059"},"BBK":{"name":"Barclays Bank of Kenya","initials":"BBK","catt":"Financials","ccode":"KE0000000067"},"BKG":{"name":"BK Group","initials":"BKG","catt":"Financials","ccode":"RW000A1JCYA5"},"BRIT":{"name":"Britam (Kenya)","initials":"BRIT","catt":"Financials","ccode":"KE2000002192"},"BATK":{"name":"BAT Kenya","initials":"BATK","catt":"Consumer Goods","ccode":"KE0000000075"},"CG":{"name":"Car &amp; General (K)","initials":"CG","catt":"Consumer Services","ccode":"KE0000000109"},"CARB":{"name":"Carbacid Investments","initials":"CARB","catt":"Basic Materials","ccode":"KE0000000117"},"ICDC":{"name":"Centum Investment","initials":"ICDC","catt":"Financials","ccode":"KE0000000265"},"CIC":{"name":"CIC Insurance Group","initials":"CIC","catt":"Financials","ccode":"KE2000002317"},"COOP":{"name":"Co-operative Bank of Kenya","initials":"COOP","catt":"Financials","ccode":"KE1000001568"},"BERG":{"name":"Crown Paints Kenya","initials":"BERG","catt":"Basic Materials","ccode":"KE0000000141"},"DCON":{"name":"Deacons (East Africa)","initials":"DCON","catt":"Consumer Services","ccode":"KE5000005438"},"DTK":{"name":"Diamond Trust Bank Kenya","initials":"DTK","catt":"Financials","ccode":"KE0000000638"},"EGAD":{"name":"Eaagads","initials":"EGAD","catt":"Consumer Goods","ccode":"KE0000000208"},"EABL":{"name":"East African Breweries","initials":"EABL","catt":"Consumer Goods","ccode":"KE0000000216"},"CABL":{"name":"East African Cables","initials":"CABL","catt":"Industrials","ccode":"KE0000000174"},"EAPC":{"name":"East African Portland Cement","initials":"EAPC","catt":"Industrials","ccode":"KE0000000190"},"EQTY":{"name":"Equity Group Holdings","initials":"EQTY","catt":"Financials","ccode":"KE0000000554"},"EVRD":{"name":"Eveready East Africa","initials":"EVRD","catt":"Consumer Goods","ccode":"KE0000000588"},"XPRS":{"name":"Express Kenya","initials":"XPRS","catt":"Consumer Services","ccode":"KE0000000224"},"FTGH":{"name":"Flame Tree Group Holdings","initials":"FTGH","catt":"Basic Materials","ccode":"KE4000001323"},"HFCK":{"name":"HF Group","initials":"HFCK","catt":"Financials","ccode":"KE1000001451"},"HAFR":{"name":"Home Afrika","initials":"HAFR","catt":"Financials","ccode":"KE2000007258"},"IM":{"name":"I&amp;M Holdings","initials":"IM","catt":"Financials","ccode":"KE0000000125"},"JUB":{"name":"Jubilee Holdings","initials":"JUB","catt":"Financials","ccode":"KE0000000273"},"KUKZ":{"name":"Kakuzi","initials":"KUKZ","catt":"Consumer Goods","ccode":"KE0000000281"},"KAPC":{"name":"Kapchorua Tea Kenya","initials":"KAPC","catt":"Consumer Goods","ccode":"KE4000001760"},"KCB":{"name":"KCB Group","initials":"KCB","catt":"Financials","ccode":"KE0000000315"},"KEGN":{"name":"KenGen Company","initials":"KEGN","catt":"Utilities","ccode":"KE0000000547"},"KENO":{"name":"KenolKobil","initials":"KENO","catt":"Oil &amp; Gas","ccode":"KE0000000323"},"KQ":{"name":"Kenya Airways","initials":"KQ","catt":"Consumer Services","ccode":"KE0000000307"},"ORCH":{"name":"Kenya Orchards","initials":"ORCH","catt":"Consumer Goods","ccode":"KE0000000331"},"KPLC":{"name":"Kenya Power &amp; Lighting","initials":"KPLC","catt":"Utilities","ccode":"KE4000002982"},"KNRE":{"name":"Kenya Re-Insurance Corporation","initials":"KNRE","catt":"Financials","ccode":"KE0000000604"},"KURV":{"name":"Kurwitu Ventures","initials":"KURV","catt":"Financials","ccode":"KE4000001216"},"CFCI":{"name":"Liberty Kenya Holdings","initials":"CFCI","catt":"Financials","ccode":"KE2000002168"},"LIMT":{"name":"Limuru Tea","initials":"LIMT","catt":"Consumer Goods","ccode":"KE0000000356"},"LKL":{"name":"Longhorn Publishers","initials":"LKL","catt":"Consumer Services","ccode":"KE2000002275"},"MSC":{"name":"Mumias Sugar Co","initials":"MSC","catt":"Consumer Goods","ccode":"KE0000000372"},"NBV":{"name":"Nairobi Business Ventures","initials":"NBV","catt":"Consumer Services","ccode":"KE5000000090"},"NSE":{"name":"Nairobi Securities Exchange","initials":"NSE","catt":"Financials","ccode":"KE3000009674"},"NIC":{"name":"NIC Bank","initials":"NIC","catt":"Financials","ccode":"KE0000000406"},"NMG":{"name":"Nation Media Group","initials":"NMG","catt":"Consumer Services","ccode":"KE0000000380"},"NBK":{"name":"National Bank of Kenya","initials":"NBK","catt":"Financials","ccode":"KE0000000398"},"NICB":{"name":"NIC Group","initials":"NICB","catt":"Financials","ccode":"KE3000009898"},"OCH":{"name":"Olympia Capital Holdings","initials":"OCH","catt":"Industrials","ccode":"KE0000000166"},"SCOM":{"name":"Safaricom","initials":"SCOM","catt":"Telecommunications","ccode":"KE1000001402"},"FIRE":{"name":"Sameer Africa","initials":"FIRE","catt":"Consumer Goods","ccode":"KE0000000232"},"PAFR":{"name":"Sanlam Kenya","initials":"PAFR","catt":"Financials","ccode":"KE0000000414"},"SASN":{"name":"Sasini","initials":"SASN","catt":"Consumer Goods","ccode":"KE0000000430"},"CFC":{"name":"Stanbic Holdings","initials":"CFC","catt":"Financials","ccode":"KE0000000091"},"SCBK":{"name":"Standard Chartered Bank Kenya","initials":"SCBK","catt":"Financials","ccode":"KE1000001819"},"SGL":{"name":"Standard Group","initials":"SGL","catt":"Consumer Services","ccode":"KE0000000455"},"FAHR":{"name":"Stanlib Fahari I-REIT","initials":"FAHR","catt":"Financials","ccode":"KE5000003656"},"TOTL":{"name":"Total Kenya","initials":"TOTL","catt":"Oil &amp; Gas","ccode":"KE0000000463"},"TPSE":{"name":"TPS Eastern Africa","initials":"TPSE","catt":"Consumer Services","ccode":"KE0000000539"},"TCL":{"name":"TransCentury","initials":"TCL","catt":"Industrials","ccode":"KE2000002184"},"UCHM":{"name":"Uchumi Supermarkets","initials":"UCHM","catt":"Consumer Services","ccode":"KE0000000513"},"UMME":{"name":"Umeme","initials":"UMME","catt":"Utilities","ccode":"KE2000005815"},"UNGA":{"name":"Unga Group","initials":"UNGA","catt":"Consumer Goods","ccode":"KE0000000497"},"WTK":{"name":"Williamson Tea Kenya","initials":"WTK","catt":"Consumer Goods","ccode":"KE0000000505"},"SCAN":{"name":"WPP Scangroup","initials":"SCAN","catt":"Consumer Services","ccode":"KE0000000562"}}';
        $nameName = json_decode($nameName, true);

        echo "<pre style='color: #b368d7; font-size: 10px;'>"; print_r($nameName); echo "</pre>";

        $sql = $this->db->select('*')->from('antoo')->order_by('id', 'asc')->limit(100)->get();

        //$sql = $this->db->query("select * from anto where data2 = '3333'");

        $nnum = 1;
        foreach($sql->result_array() as $row){
            echo "<br /><br /><br />".$nnum."::<pre style='color: #0d579a; font-size: 10px;'>";
            print_r(json_decode($row['data'], true));
            echo "</pre>";

            $rowItem = json_decode($row['data'], true);

            if(isset($rowItem['44']) && isset($rowItem['48']) && $rowItem['35'] == "U1" && $rowItem['6000'] == "NORMAL"){
                echo "<p style='color: #0d9a21'>Name: ".$rowItem['48']."  -  Price: ".$rowItem['44']."</p>";

                $this->db->query("DELETE FROM snapshot WHERE co_initials = '".str_replace('.O0000', '', $rowItem['48'])."' AND date(created_date) != '".date('Y-m-d')."'");

                $data = $this->db->select('*')
                    ->from('snapshot')
                    ->where(array(
                        'co_initials' => str_replace('.O0000', '', $rowItem['48']),
                        'today_date' => date('Y-m-d')))
                    ->order_by('id', 'asc')
                    ->limit(1)
                    ->get();

                echo "<p>".$this->db->last_query()."</p>";

                if($data->num_rows() == 1){
                    $data = $data->row();



                    $iuData = array(
                        'price' => $rowItem['44'],
                        'last_update' => date('Y-m-d H:i:s'),
                        'today_close' => $rowItem['44'],
                        'prev_price' => $data->price,
                        'today_high' => ($rowItem['44'] > $data->price ? $rowItem['44'] : $data->price),
                        'today_low' => ($rowItem['44'] < $data->price ? $rowItem['44'] : $data->price)
                    );

                    $this->db->update('snapshot', $iuData, array('id' => $data->id));
                }else{
                    $iuData = array(
                        'today_date' => date('Y-m-d'),
                        'last_update' => date('Y-m-d H:i:s'),
                        'price' => $rowItem['44'],
                        'today_open' => $rowItem['44'],
                        'today_high' => $rowItem['44'],
                        'today_low' => $rowItem['44'],
                        'co_initials' => str_replace('.O0000', '', $rowItem['48']),
                        'ccode' => (isset($nameName[str_replace('.O0000', '', $rowItem['48'])]) ? $nameName[str_replace('.O0000', '', $rowItem['48'])]['ccode'] : 0),
                        'name' => (isset($nameName[str_replace('.O0000', '', $rowItem['48'])]) ? $nameName[str_replace('.O0000', '', $rowItem['48'])]['name'] : str_replace('.O0000', '', $rowItem['48']))
                    );

                    $this->db->insert('snapshot', $iuData);
                }

                echo "<p style='color: #2427f2; font-weight: 600;'>QUERY:::: ".$this->db->last_query()."</p>";


            }else{
                echo "<p style='color: #b70f2b'>No for :: ".$row['data']."</p>";
            }

            if(isset($rowItem['5200']) && isset($rowItem['106'])){
                $this->db->update('snapshot', array('ccode' => $rowItem['5200']), array('co_initials' => str_replace('.O0000', '', $rowItem['106'])));
                echo "<p style='color: #b76f0f; font-size: 12px;'>".$this->db->last_query()."</p>";
            }

            $this->db->query("DELETE from antoo where id = " . $row['id']);

            $nnum++;
        }
    }

    function sendSMS($mobile, $sms, $code = false) {

        $url = 'http://api.infobip.com/sms/1/text/single';

        $jsonData = array(
            "to" => $mobile,
            "text" => $sms,
            "from" => 'NSE'
        );


        $this->db->insert('smses', array('mobile' => $mobile, 'message' => $sms));

        $jsonDataEncoded = json_encode($jsonData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accept: application/json',
            'Content-Length: ' . strlen($jsonDataEncoded),//
            'authorization: Basic ' . base64_encode("NSE_Marketing:Esther1983") //YW11dGlzeWE6UEFTU3dvcmRBdDIxSGFzaA==
        ));
        $result = curl_exec($ch);

//        echo $result; exit;
    }



    public function create_user(){

        header('Access-Control-Allow-Origin: *');
        $post_data = $_POST;

        $email    = $post_data['email'];
        $identity = $post_data['email'];
        $password = $this->input->post('password');

        $ccode = rand(111111, 999999);
        $additional_data = array(
            'first_name' => $post_data['first_name'],
            'last_name'  => $post_data['first_name'],
            'company'    => '',
            'phone'      => $post_data['mobile'],
            'ccode' => $ccode
        );

        $this->ion_auth->register($identity, $password, $email, $additional_data);

        //echo json_encode(array('message' => 'USER amekuwa created!!'));

        $this->sendSMS('' . $post_data['mobile'], "Welcome To NSE Nigeria App. Please use code " . $ccode . " to verify your mobile number and enjoy access to the app. ");
        $this->sendMAil('' . $post_data['email'], '' . $ccode, $post_data['first_name']);
    }



    public function login(){

        header('Access-Control-Allow-Origin: *');
        $post_data = $_POST;

        $data = array();

        $data['status'] = 0;
        $data['message'] = 'Invalid Credentials';

        if ($this->ion_auth->login($post_data['useruser'], $post_data['passpass'])) {
            $data['status'] = 1;
            $data['message'] = 'Valid Credentials, Welcome';
        }

        echo json_encode($data);
    }



    function verifytokentoken(){
        header('Access-Control-Allow-Origin: *');
        $post_data = $_POST;

        $data = array();
        $data['status'] = 0;
        $data['message'] = "Invalid Token";

        $sql = $this->db->select('*')->from('users')
            ->where(array(
                'ccode' => $post_data['tokentoken'],
                'email' => $post_data['username'],
            ))
            ->limit(1)
            ->get();

        if($sql->num_rows() == 1) {
            $data['status'] = 1;
            $data['message'] = "Valid Token, Welcome!";
        }

        echo json_encode($data);
    }



    function getCompanyDetails($co = false){
        header('Access-Control-Allow-Origin: *');


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/v3/api/quote/stockquotes.json?s=".$co."&_t=1b105e18ee6d464ea2e8f5bc6f66a0a6",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 9e39bd5d-9125-7637-8f41-54fd64dad2f5"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "{}";
        } else {
            echo json_encode(array(
                'data' => json_decode($response, true)
            ));
        }


    }


    function sendMAil($to, $code, $name){
        $curl = curl_init();


        $data = array(
            'namename' => $name,
            'codecode' => $code
        );

        $code = $this->load->view('email/email', $data, true);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pesamakini.com/app/anto_send_email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"to\"\r\n\r\n".$to."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"title\"\r\n\r\nNSE MOBILE ACCOUNT CREATION VERIFICATION CODE\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n".$code."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"from\"\r\n\r\nnoreply@nse.com.ng\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 4e5e69d3-0c5a-41da-b882-d7a6468e044b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

    }







    function getTopLosers(){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://www.nse.com.ng/REST/api/mrkstat/bottomsymbols",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 8a81a058-970d-7caf-b645-c07a4dc9faaf"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
           echo json_encode(array('resp' => $response));
        }
    }

    function getTopGainers(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://www.nse.com.ng/REST/api/mrkstat/topsymbols",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 8a81a058-970d-7caf-b645-c07a4dc9faaf"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
	} else {
	    echo json_encode(array('resp' => $response));
        }
    }



    function monitor(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        echo json_encode($this->input->post());
    }

}

