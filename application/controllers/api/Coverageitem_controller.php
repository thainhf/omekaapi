<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Coverageitem_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get($id=null)
    {
        $dataArray= array();
        $vconfig=$this->config;
        $vper_page= "&page=1&per_page=100";
        $vomekas_url= $vconfig->config["omekas_url"];

        $item_set_id=$id;
        $api_url=$vomekas_url."item_sets/".$item_set_id;

      //  echo $vomekas_url;
       // print_r($vconfig->config["omekas_url"]);
      //  echo "</pre>";

        $json_objekat    = cal_curl_api($api_url);
      //  echo "<pre>999=";
      //  print_r($json_objekat->o_title);
     //   echo "</pre>";
       // exit();

        if(!empty($json_objekat)) {
            $data=array(
                "id"=>$json_objekat->o_id,
                "title"=>$json_objekat->o_title,
            );
            array_push($dataArray, $data);
        }


        $this->response($dataArray, 200);
     //   $this->response($dataAll, 200);
     /*   $this->response(array(
            "status" => 1,
            "message" => "Students found99999",
            "data" => "9999"
        ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด
    }


}
