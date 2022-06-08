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




    function getxnewsnews(){
        //Teh good one
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $now = new DateTime();
        $back = $now->sub(DateInterval::createFromDateString('10 days'));
        $sdate = $back->format('Y-m-d');


        //exit("https://marketdataapiv3.nse.com.ng/nsedata/api/StockPerformance?symbol=".$ccompany."&startDate=".$sdate."&endDate=" . date('Y-m-d'));


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://svcs.infowarelimited.com/IWSvcsMDPAPI/api/json/MD/NSE05EA111A921C4BCCB939A1DF88C358C7/8003?SenderIP=NSEIP",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "cache-control: no-cache",
                "postman-token: bc54a228-1f5d-c10a-4232-45bf28202d18"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            /*
               "ColumnDef": {
               "0": "ID",
               "1": "Title",
               "2": "Author",
               "3": "DATE",
               "4": "Source",
               "5": "Snippet",
               "6": "URL",
               "7": "Image",
               "8": "ImageURL"
             },
             */

            foreach($response['DataTable']['Rows'] as $val){
                $resp[] = array(
                    'id' => $val['0'],
                    'title' => $val['1'],
                    'author' => $val['2'],
                    'date' => $val['3'],
                    'source' => $val['4'],
                    'descr' => $val['5'],
                    'url' => $val['6'],
                    'image' => $val['7'],
                    'imageurl' => $val['8']
                );
            }

            echo json_encode(array('resp' => $resp));
        }

    }


    function details($comp = ""){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


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
            //"from" => 'NSE'
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

        $resp = array('status' => '1');
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

        if($this->ion_auth->register($identity, $password, $email, $additional_data)){
            $this->sendSMS('' . $post_data['mobile'], "Welcome To NSE Nigeria App. Please use code " . $ccode . " to verify your mobile number and enjoy access to the app. ");
            $this->sendMAil('' . $post_data['email'], '' . $ccode, $post_data['first_name']);

        }else{
            $resp['status'] = 0;
            $resp['message'] = 'User with existing email address already created. ';
        }

        //echo json_encode(array('message' => 'USER amekuwa created!!'));



        echo json_encode($resp);
    }

    public function nnnncreate_user(){

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

        echo json_encode(array('status' => '1'));
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
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


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







    function sendMail($to, $code, $name){

        $curl = curl_init();


        $data = array(
            'namename' => $name,
            'codecode' => $code
        );


//        $data=  "Hello ".$name.", <br /> Welcome to NSE Mobile. Use the verification code ".$code." below to verify your account";

        $code = $this->load->view('email/email', $data, true);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pesamakini.com/app/anto_send_email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"to\"\r\n\r\n".$to."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\nm".$code."t\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"title\"\r\n\r\nNigerian Stock Exchange ACCESS TOKEN\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"name\"\r\n\r\nnoreply@nse.com.ng\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 0bdd0e79-6aea-3c96-1457-51e12a3838ef"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }



    function bbbbbsendMAil($to, $code, $name){
        $curl = curl_init();

        $to = "anthony@deveint.com";

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

        echo $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

    }







    function getTopLosers(){

        $token = $this->gettokena(1);
        $token = json_decode($token, true);

        $access = $token['access_token'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/MarketMovers/Losers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access,
                "cache-control: no-cache",
                "postman-token: 8a81a058-970d-7caf-b645-c07a4dc9faaf"
            ),
        ));

        $responsexxx = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $antooo = json_decode($responsexxx, true);

            $antonew = array();
            foreach($antooo as $kkk){

                /*
                {
                  "Symbol": "AFRINSURE",
                  "CSI": "AFRINSURE",
                  "Last": 0.2,
                  "Change": 0,
                  "PerChange": 0,
                  "Volume": 8000000,
                  "Value": 1600000,
                  "Deals": null
                }
                */
                $kkk["ID"] = 2;
                $kkk["SYMBOL"] = $kkk['Symbol'];
                $kkk["LAST_CLOSE"] = $kkk['Last'];
                $kkk["TODAYS_CLOSE"] = $kkk['Last'];
                $kkk["PERCENTAGE_CHANGE"] = $kkk['PerChange'];
                $kkk["SYMBOL2"] = $kkk['Symbol'];
                $kkk['xxrealPERCENTAGE_CHANGE'] = ($kkk['PERCENTAGE_CHANGE'] / $kkk['LAST_CLOSE']) * 100;
                $antonew[] = $kkk;
            }
            $responsexxx = $antonew;
            echo json_encode(array('resp' => json_encode($responsexxx)));
        }


        /*
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
			//    echo json_encode(array('resp' => $response));
			//
			//
			$antooo = json_decode($response, true);

			$anto = array();
			foreach($antooo as $kkk){
				$kkk['xxrealPERCENTAGE_CHANGE'] = ($kkk['PERCENTAGE_CHANGE'] / $kkk['LAST_CLOSE']) * 100;
				$anto[] = $kkk;
			}

			usort($anto, function ($a, $b) {
				//return strcmp($a['xxrealPERCENTAGE_CHANGE'], $b['xxrealPERCENTAGE_CHANGE']);
				return $a['xxrealPERCENTAGE_CHANGE'] > $b['xxrealPERCENTAGE_CHANGE'];
			});
			echo json_encode(array('resp' => json_encode($anto)));

		}

        */
    }

    function getTopGainers(){
        $token = $this->gettokena(1);
        $token = json_decode($token, true);

        $access = $token['access_token'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/MarketMovers/Gainers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access,
                "cache-control: no-cache",
                "postman-token: 8a81a058-970d-7caf-b645-c07a4dc9faaf"
            ),
        ));

        $responsexxx = $ogg = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $antooo = json_decode($responsexxx, true);

            $antonew = array();
            foreach($antooo as $kkk){

                /*
                {
                  "Symbol": "AFRINSURE",
                  "CSI": "AFRINSURE",
                  "Last": 0.2,
                  "Change": 0,
                  "PerChange": 0,
                  "Volume": 8000000,
                  "Value": 1600000,
                  "Deals": null
                }
                */
                $kkk["ID"] = 2;
                $kkk["SYMBOL"] = $kkk['Symbol'];
                $kkk["LAST_CLOSE"] = $kkk['Last'];
                $kkk["TODAYS_CLOSE"] = $kkk['Last'];
                $kkk["PERCENTAGE_CHANGE"] = $kkk['PerChange'];
                $kkk["SYMBOL2"] = $kkk['Symbol'];
                $kkk['xxrealPERCENTAGE_CHANGE'] = ($kkk['PERCENTAGE_CHANGE'] / $kkk['LAST_CLOSE']) * 100;
                $antonew[] = $kkk;
            }
            $responsexxx = $antonew;
            echo json_encode(array('ogg' => $ogg, 'resp' => json_encode($responsexxx)));
        }


        /*
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
            $antooo = json_decode($response, true);

            $anto = array();
            foreach($antooo as $kkk){
                $kkk['xxrealPERCENTAGE_CHANGE'] = ($kkk['PERCENTAGE_CHANGE'] / $kkk['LAST_CLOSE']) * 100;
                $anto[] = $kkk;
            }

            usort($anto, function ($a, $b) {
                //return strcmp($a['xxrealPERCENTAGE_CHANGE'], $b['xxrealPERCENTAGE_CHANGE']);
                return $a['xxrealPERCENTAGE_CHANGE'] < $b['xxrealPERCENTAGE_CHANGE'];
            });
            echo json_encode(array('responsexxx' => json_encode($responsexxx), 'resp' => json_encode($anto)));
        }

        */
    }

    function deleteAP(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

//        let reqData = "companyID="+this.mpya+"&DateAdded="+this.user['username'];
        $post_data = $_POST;

        $this->db->delete('users', array('email' => $post_data['DateAdded']));

        $resp = array('status' => true);
        echo json_encode($resp);
    }

    function changeP(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

//        let reqData = "companyID="+this.mpya+"&DateAdded="+this.user['username'];
        $post_data = $_POST;

        $change = $this->ion_auth->change_password($post_data['DateAdded'], $post_data['companyLocation'], $post_data['companyID']);

        $resp = array('status' => true);
        if ($change) {
            $resp['message'] = 'Password changed successfully!';
        } else {
            $resp = array('status' => false, 'message' => 'Error changing password . ' . json_encode($this->ion_auth->errors()));
        }


        echo json_encode($resp);
    }

    function getUserMonitored(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;
        $user = $this->ion_auth_model->getUSer($post_data['username']);

        $resp['status'] = 0;
        $resp['message'] = "No monitored Company";
        $resp['data'] = array();

        if($user->num_rows() == 1){
            $user = $user->row();
            $sql = $this->db->select('*')->from('user_monitor')
                ->where(array('user_id' => $user->id))
                ->order_by('id', 'desc')
                ->get();

            foreach ($sql->result_array() as $row){
                $resp['data'][] = $row;
                $resp['status'] = 1;
                $resp['message'] = "";
            }
        }

        echo json_encode($resp);
    }





    function monitor(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        /*
        +--------------------+--------------+------+-----+-------------------+----------------+
        | Field              | Type         | Null | Key | Default           | Extra          |
        +--------------------+--------------+------+-----+-------------------+----------------+
        | id                 | int(11)      | NO   | PRI | NULL              | auto_increment |
        | created_date       | timestamp    | NO   |     | CURRENT_TIMESTAMP |                |
        | user_id            | int(11)      | YES  |     | NULL              |                |
        | user_email         | int(11)      | YES  |     | NULL              |                |
        | co_monitor         | int(11)      | YES  |     | NULL              |                |
        | monitor_price      | varchar(111) | YES  |     | NULL              |                |
        | monitor_price_up   | varchar(111) | YES  |     | NULL              |                |
        | monitor_price_down | varchar(111) | YES  |     | NULL              |                |
        | status             | int(11)      | NO   |     | 0                 |                |
        | metadata           | text         | NO   |     | NULL              |                |
        +--------------------+--------------+------+-----+-------------------+----------------+

         */
        $post_data = $_POST;
        $user = $this->ion_auth_model->getUSer($post_data['username']);
        if($user->num_rows() == 1){
            $user = $user->row();

            $monitored = $this->ion_auth_model->getUserMonitored($user->id, $post_data['company']);

            $data = array(
                'user_id' => $user->id,
                'user_email' => $user->email,
                'company_name' => $post_data['company'],
                'monitor_price' => $post_data['curprice'],
                'monitor_price_up' => $post_data['upper'],
                'monitor_price_down' => $post_data['lower'],
            );

            if($monitored->num_rows() == 1){
                $monitored = $monitored->row();
                $this->db->update('user_monitor', $data, array('id' => $monitored->id));
            }else{
                $this->db->insert('user_monitor', $data);
            }

        }else{
        }
        echo json_encode($this->input->post());
    }


    function getNews(){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

//CURLOPT_URL => "http://www.nse.com.ng/_api/Web/Lists/GetByTitle%28%27News%20Story%27%29/items?%24orderby=Created%20desc%2CCreated%20desc&%24top=20",

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://www.nse.com.ng/_api/web/lists/getbytitle(\'Press%20Releases\')/items?$orderby=Press_Release_Date%20desc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json;odata=verbose",
                "cache-control: no-cache",
                "postman-token: aafb9c8e-d5de-89bf-efab-42616ee8cced"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);



        $resp = json_decode($response, true);


        $nnews = array();
        foreach($resp['d']['results'] as $key=>$val){
            $nnews[] = array(
                'ddate' => $val['Created'],
                'title' => $val['URL']['Description'],
                'descr' => $val['URL']['Description'],
                'img' => '', //$val['Cover_Picture']['Url'],
                'url' => $val['URL']['Url'],
            );
        }

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo json_encode(array('resp' => $nnews));
        }


    }

    function manage_banners(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        /*
        +--------------+--------------+------+-----+-------------------+----------------+
        | Field        | Type         | Null | Key | Default           | Extra          |
        +--------------+--------------+------+-----+-------------------+----------------+
        | id           | int(11)      | NO   | PRI | NULL              | auto_increment |
        | created_date | timestamp    | NO   |     | CURRENT_TIMESTAMP |                |
        | name         | varchar(222) | YES  |     | NULL              |                |
        | descr        | varchar(222) | YES  |     | NULL              |                |
        | file_loc     | text         | NO   |     | NULL              |                |
        | priority     | int(11)      | NO   |     | 0                 |                |
        | active       | int(11)      | NO   |     | 0                 |                |
        +--------------+--------------+------+-----+-------------------+----------------+

         */

        $resp = array();
        $resp['status'] = 0;
        $resp['message'] = "No Banners Yo!!";
        $sql = $this->db->select('*')
            ->from('banners')
            ->where('active', '1')
            ->order_by('priority', 'desc')
            ->limit(3)
            ->get();

        if($sql->num_rows() >= 1){
            $resp['status'] = 1;
            $resp['message'] = "Banners";

            foreach($sql->result_array() as $row){
                $resp['banners'][] = $row;
            }
        }

        echo json_encode($resp);

    }





    function getMarketStatus(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://www.nse.com.ng/REST/api/statistics/mktstatus",
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
            $response = json_decode($response, true);
            $closedStates = ["STARTOFDAY", "STARTLTECP", "ENDOFDAY", "STOPLTECP", "CLOSE_INDEX", "NSE_CLOSE", "EOD_SESS", "NSE_CLS_MCH", "NSE_CLOSE", "NSE_MM_STP"];

            if(in_array($response[0]['MktStatus'], $closedStates)){
                echo json_encode(array('ddate' => date('M, d Y'), 'status' => 'Closed'));
            }else{
                echo json_encode(array('ddate' => date('M, d Y'), 'status' => 'Open'));
            }
        }
    }




    function getCompanyGraphSimple($coo){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://www.nse.com.ng/REST/api/stockchartdata/".$coo,
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
            echo json_encode(array('resp' =>$response));
        }
    }






    function gettokena($returnResponse = false){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=deveint&password=Dev%40eint2019&grant_type=password",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic ZGV2ZWludDpEZXZAZWludDIwMTk=",
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "postman-token: 879b9e15-89aa-3b6a-8b72-4925fc7c9cd5"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if($returnResponse){
                return $response;
            }else{
                echo $response;
            }

        }
    }







    function getDetailedQuote($token = false, $csi = false, $returnResponse = false){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $post_data = @$_POST;
        $ccompany = @$post_data['company'];
        $access = @$post_data['access'];

        $curl = curl_init();

        $curnArray = array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/Quote?symbol=" . ($csi ? $csi : $ccompany) . "&access_token=" . ($token ? $token : $access),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . ($token ? $token : $access),
                "cache-control: no-cache",
                "postman-token: e145dfa7-31d3-c257-6c07-32a28c9c6f87"
            ),
        );
        curl_setopt_array($curl, $curnArray);


        //echo json_encode($curnArray);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if($returnResponse){
                return $response;
            }else{
                echo $response;
            }
        }
    }










    function gettradingdate(){
        //Teh good one
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;
        $ccompany = $post_data['company'];
        $access = $post_data['access'];


        $now = new DateTime();
        $back = $now->sub(DateInterval::createFromDateString('10 days'));
        $sdate = $back->format('Y-m-d');


        //exit("https://marketdataapiv3.nse.com.ng/nsedata/api/StockPerformance?symbol=".$ccompany."&startDate=".$sdate."&endDate=" . date('Y-m-d'));


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/IndexValues?tradingdate=" . date('Y-m-d'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{\n  \"orderBy\": \"b.boy\", \"filterType\" : \"AND\", \"id\": 155\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access,
                "cache-control: no-cache",
                "postman-token: e145dfa7-31d3-c257-6c07-32a28c9c6f87"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo json_encode(array('data' =>json_decode($response, true)));
        }

    }





    function getStockPerformance(){
        //Teh good one
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;
        $ccompany = $post_data['company'];
        $access = $post_data['access'];


        $now = new DateTime();
        $back = $now->sub(DateInterval::createFromDateString('90 days'));
        $sdate = $back->format('Y-m-d');


        $newStart = date('Y-m-d', strtotime($post_data['eend']));
        $newEnd = date('Y-m-d', strtotime($post_data['sstart']));


        //exit("https://marketdataapiv3.nse.com.ng/nsedata/api/StockPerformance?symbol=".$ccompany."&startDate=".$sdate."&endDate=" . date('Y-m-d'));


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/StockPerformance?symbol=".$ccompany."&startDate=".$newStart."&endDate=" . $newEnd,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{\n  \"orderBy\": \"b.boy\", \"filterType\" : \"AND\", \"id\": 155\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access,
                "cache-control: no-cache",
                "postman-token: e145dfa7-31d3-c257-6c07-32a28c9c6f87"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo json_encode(array('data' =>$response));
        }

    }






    function userFeedback(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;



        $this->db->insert('user_feedback', array(
            'rating' => $_POST['rating'],
            'email' => $_POST['email'],
            'feedback' => $_POST['feedback']
        ));

        echo json_encode($_POST);
    }



    function getUserFeedback(){
        $sql = $this->db->select('*')->from('user_feedback')
            ->order_by('id', 'desc')
            ->get();

        foreach ($sql->result_array() as $row){
            $resp['data'][] = $row;
            $resp['status'] = 1;
            $resp['message'] = "";
        }

        echo json_encode($resp);
    }




    function removeMonitor(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;

        $this->db->delete('user_monitor', array('id' => $_POST['companyID']));

        echo json_encode($_POST);
    }






    function getSimulatorSummary($csi = ""){
        $resp = array();

        $token = $this->gettokena(1);
        $token = json_decode($token, true);
        // echo "<pre style='color: #b368d7'>"; print_r($token); echo "</pre>";

        $details = $this->getDetailedQuote($token['access_token'], $csi, 1);
        $details = json_decode($details, true);

        //echo "<pre style='color: #97310e'>"; print_r($details); echo "</pre>";


        $resp['details'] = array(
            "ISINNO" => $details['Symbol'],
            "COUNTERNAME" => $details['Symbol'],
            "HIGH" => $details['High'],
            "LOW" => $details['Low'],
            "HIGHEST" => $details['High52Week'],
            "LOWEST" => $details['Low52Week'],
            "prev_price" => $details['PrevClose'],
            "price" => $details['Last'],
            "difference" => $details['Change'],
        );


        $resp['ratios'] = array(
            "EPS" => $details['EPS'],
            "DPS" =>  0,
            "PE" => $details['PE'],
            "yield" => "0"
        );

        $this->db->query("set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
        $this->db->query("set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");


        $resp['prices'] = array();
        $prices = $this->db->select('trade_date, created_at, close, volume, created_at_int')
            ->from('real_time_price')
            ->where(array('symbol' => $csi))
            ->order_by('id', 'asc')
            ->group_by('created_at_int')
            ->get();
        foreach ($prices->result() as $row){
            $resp['prices'][] = array(
                "price" => $row->close,
                "price_date" => date('Y-m-d', strtotime($row->created_at)),
                "days_no" => date('z', strtotime($row->created_at)),
                "volume"=> $row->volume
            );
        }





        $resp['monthly_summary'] = array();
        $montths = $this->db->select('month(created_at) as ddate, close, volume, created_at_int, created_at')
            ->from('real_time_price')
            ->where(array('symbol' => $csi))
            ->order_by('id', 'asc')
            ->group_by('ddate')
            ->get();
        foreach ($montths->result() as $row){
            $resp['monthly_summary'][] = array(
                "price" => $row->close,
                "month" => date('M', strtotime($row->created_at)),
                "volume"=> $row->volume
            );
        }


        echo json_encode($resp);
    }




    function getAllUsers()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $thii = $this->db->select(' id, first_name , last_name , phone , created_on , email , username')
            ->from('users')
            ->get();

        $data = array();
        $data['users'] = array();

        foreach($thii->result_array() as $row){
            $data['users'][] = $row;
        }

        echo json_encode($data);
    }



    function getAbiNewNews(){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://svcs.infowarelimited.com/IWSvcsMDPAPI/api/json/MD/NSE05EA111A921C4BCCB939A1DF88C358C7/8095?SenderIP=NSEIP",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{\n \"comment\": \"antoo test\",\n \"commentable_id\": \"1\",\n \"anonymous\": false\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xNzIuMTA0LjI0NS4xNFwvdGFqaVwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTU5NzE2Mzk2OCwiZXhwIjoxNTk5NTgzMTY4LCJuYmYiOjE1OTcxNjM5NjgsImp0aSI6IjFFaTRWWloxUDhxNWhBb3AiLCJzdWIiOjEzLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.nU6yq1bLXpJFyHQ2ei1Lpg1kooGkW0q4uprYLGk6ROQ",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: d5e814f2-fce2-ca25-80f6-91610b74e50e"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo json_encode(array());
        } else {
            echo $response;
        }
    }





    function getFactSheet(){


        //Teh good one
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

        $post_data = $_POST;
        $access = $post_data['access'];


        $now = new DateTime();
        $back = $now->sub(DateInterval::createFromDateString('90 days'));
        $sdate = $back->format('Y-m-d');

        //exit("https://marketdataapiv3.nse.com.ng/nsedata/api/StockPerformance?symbol=".$ccompany."&startDate=".$sdate."&endDate=" . date('Y-m-d'));


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://marketdataapiv3.nse.com.ng/nsedata/api/factsheet?tradingdate='".date('Y-m-d')."'",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
//            CURLOPT_POSTFIELDS => "{\n  \"orderBy\": \"b.boy\", \"filterType\" : \"AND\", \"id\": 155\n}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $access,
                "cache-control: no-cache",
                "postman-token: e145dfa7-31d3-c257-6c07-32a28c9c6f87"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo json_encode(array('data' =>$response));
        }


    }


}

