<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Coveragelist_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get($yyyy=null,$mm=null)
    {
        $dataArray= array();
        $vconfig=$this->config;
        $vper_page= "&page=1&per_page=100";
        $vomekas_url= $vconfig->config["omekas_url"];

        $p_yyyy=$yyyy;
        $p_mm=$mm;
        $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04

        $api_url=$vomekas_url."items?property[0][joiner]=and&property[0][property]=23&property[0][type]=in&property[0][text]=".$YYYY_MM."&sort_by=created&sort_order=desc&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][field]=created&datetime[0][type]=gte".$vper_page;

      //  echo $vomekas_url;
       // print_r($vconfig->config["omekas_url"]);
      //  echo "</pre>";

        $json_objekat    = cal_curl_api($api_url);
/*
        $curl = curl_init($api_url);
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "REST-API-Key: 1111",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        //var_dump($resp);
        $words_f = ["o:", "dcterms:", ":tag", "o-module-folksonomy", "@value"];
        $words_r   = ["o_", "dcterms_", "_tag", "o_module_folksonomy", "a_value"];
        $resp = str_replace($words_f,$words_r,$resp);
        $json_objekat    = json_decode($resp);
        */
//        echo "<pre>999=";
//    print_r($json_objekat);
//    echo "</pre>";

        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

                //    echo "<pre>999=";
                //    print_r($objQuote);
               //     echo "</pre>";
                $title=$objQuote->o_title;
                $o_id=$objQuote->o_id;
              //  $dcterms_date=$objQuote->dcterms_date; //วันที่สร้างเอกสาร

                $thumbnail="";
                //part รูป
                if(isset($objQuote->thumbnail_display_urls)){
                    $thumbnail_arr=$objQuote->thumbnail_display_urls;
                    if(!empty($thumbnail_arr)){
                        $thumbnail_url=$thumbnail_arr->large;
                        $thumbnail = $thumbnail_url;
                    }
                }



                //cat Collection

                $catesArray= array();
               // $catesNameArray= array();
                if(isset($objQuote->o_item_set)){
                    $item_set_arr=$objQuote->o_item_set;
                    if(!empty($item_set_arr)){
                        foreach ($item_set_arr as $item_set) {
                            $vid_set=$item_set->o_id;
                            array_push($catesArray, $vid_set);
                            // echo "<pre>999=";
                           //  print_r($item_set);
                            //  echo "</pre>";
                           // $api_url_item_set=$vomekas_url."item_sets/".$vid_set;
                           // $json_obj_cate=cal_curl_api($api_url_item_set);
                        }
                    }
                }

            //วันที่ สร้างเอกสาร
                $dcterms_dateArray= array();
                $dcterms_date="";
                if(isset($objQuote->dcterms_date)){
                    $dcterms_date_arr=$objQuote->dcterms_date;
                    if(!empty($dcterms_date_arr)){
                        foreach ($dcterms_date_arr as $date_set) {
                            $dcterms_date=trim($date_set->a_value);
                            //array_push($dcterms_dateArray, $vdcterms_date);
                        }
                    }
                }

                //ผู้สร้าง/เจ้าของผลงาน
                $dcterms_creator="";
                if(isset($objQuote->dcterms_creator)){
                    $dcterms_creator_arr=$objQuote->dcterms_creator;
                    if(!empty($dcterms_creator_arr)){
                        foreach ($dcterms_creator_arr as $date_set) {
                            $dcterms_creator=trim($date_set->a_value);
                            //array_push($dcterms_dateArray, $vdcterms_date);
                        }
                    }
                }

                $data=array(
                    "id"=>$o_id,
                    "thumbnail"=>$thumbnail,
                    "cates"=>$catesArray,
                    "title"=>$title,
                    "created"=>$dcterms_date,
                    'creator'=>$dcterms_creator
                );
                array_push($dataArray, $data);
            }
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
