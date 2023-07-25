<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivegroup_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $dataArray = array();
        $dataArray2 = array();
        $vconfig = $this->config;

        //$vper_page=$per_page;
      //  $vper_page = "&page=".$vpage."&per_page=" . $vlimitperpage;

        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "item_sets" ;


        $json_objekat = cal_curl_api($api_url);

        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {
                $o_id = $objQuote->o_id;
                $o_title = $objQuote->o_title;
                $o_resource_class_arr = $objQuote->o_resource_class;

//                echo "<pre>999=";
//                print_r($json_objekat);
//                echo "</pre>";

                if($o_resource_class_arr->o_id == 23 && $o_id != 2630 ){
                    $data = array(
                        "id" => $o_id,
                        "title" => $o_title,
                    );
                    array_push($dataArray, $data);
                }


            }
        }

//        echo "<pre>999=";
//        print_r($dataArray);
//        echo "</pre>";




        $this->response($dataArray, 200);
         //  $this->response($dataAll, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
