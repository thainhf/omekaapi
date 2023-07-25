<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivegroupfilter_controller extends REST_Controller
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
        //ย้อนหลัง จำนวนเดือน
        $filter = $this->input->get('class', true);
        $filter_cates="";
        if(!empty($filter)){
            $vfilter=$filter;
            $filter_array = explode(',', $vfilter );
            $cat_no="";
            foreach ($filter_array as $key => $tag ) {
                if($tag=="Collection"){
                    $cat_no="23";
                }elseif($tag=="Dataset"){
                    $cat_no="24";
                }elseif($tag=="Event"){
                    $cat_no="25";
                }elseif($tag=="Image"){
                    $cat_no="26";
                }
                $filter_cates= $filter_cates."&resource_class_id[]=".$cat_no;
            }
        }else{
            $vfilter="";
        }



        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "item_sets?limit=0&offset=0".$filter_cates ;


        $json_objekat = cal_curl_api($api_url);

        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {
                $o_id = $objQuote->o_id;
                $o_title = $objQuote->o_title;
                $o_resource_class_arr = $objQuote->o_resource_class;
                if($o_resource_class_arr->o_id == 23){
                    $tag="Collection";
                }elseif($o_resource_class_arr->o_id == 24){
                    $tag="Dataset";
                }elseif($o_resource_class_arr->o_id == 25){
                    $tag="Event";
                }elseif($o_resource_class_arr->o_id == 26){
                    $tag="Image";
                }

//                echo "<pre>999=";
//                print_r($json_objekat);
//                echo "</pre>";

               // if($o_resource_class_arr->o_id == 23 && $o_id != 2630 ){
                    $data = array(
                        "id" => $o_id,
                        "title" => $o_title,
                        "type" => $tag,
                    );
                    array_push($dataArray, $data);
              //  }


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
