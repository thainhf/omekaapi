<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Monthlylist_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $dataArray= array();
        $vconfig=$this->config;
        $crty = $this->input->get('crty', true);
        $crtm = $this->input->get('crtm', true);
        $limitpermonth = $this->input->get('limitpermonth', true);
        //echo $crty;
        if(!empty($limitpermonth)){
            $vlimitpermonth=$limitpermonth;
        }else{
            $vlimitpermonth=100;
        }
        $vper_page= "&page=1&per_page=".$vlimitpermonth;

        $vomekas_url= $vconfig->config["omekas_url"];
        $yyyy="";
        $crtall="";
        if(!empty($crty)){
           // $yyyy=$crty;
            $crtall=$crtall.$crty;
        }else{
           // $yyyy="";
           // $crtall=$crtall;
        }
      //  echo $crtall;
       // $mm="";
        if(!empty($crtm)){
          //  $mm=$crtm;
            $crtall=$crtall.'-'.$crtm;
        }else{
         //   $mm="";
          //  $crtall=$crtall;
        }
    //    $p_yyyy=$yyyy;
     //   $p_mm=$mm;
      //  $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04
        $YYYY_MM=$crtall;
      //  echo $crtall;

        $api_url=$vomekas_url."items?property[0][joiner]=and&property[0][property]=23&property[0][type]=in&property[0][text]=".$YYYY_MM."&sort_by=created&sort_order=desc&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][field]=created&datetime[0][type]=gte".$vper_page;

     //   echo $api_url;
     //   print_r($vconfig->config["omekas_url"]);
      //  echo "</pre>";
    //   exit();

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

//                    echo "<pre>999=";
//                    print_r($objQuote);
//                    echo "</pre>";
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
                $catesNameArray= array();
                if(isset($objQuote->o_item_set)){
                    $item_set_arr=$objQuote->o_item_set;
                    if(!empty($item_set_arr)){
                        foreach ($item_set_arr as $item_set) {
                            $vid_set=$item_set->o_id;
                            array_push($catesArray, $vid_set);
                           /* $item_set_id=$vid_set;
                            $api_url2=$vomekas_url."item_sets/".$item_set_id;
                            $json_objekat2    = cal_curl_api($api_url2);
                            if(!empty($json_objekat2)) {
                                array_push($catesNameArray, $json_objekat2->o_title);
                            }*/
                            // echo "<pre>999=";
                           //  print_r($item_set);
                            //  echo "</pre>";
                           // $api_url_item_set=$vomekas_url."item_sets/".$vid_set;
                           // $json_obj_cate=cal_curl_api($api_url_item_set);
                        }
                    }
                }


                if(isset($objQuote->dcterms_coverage)){
                    $dcterms_coverage_arr=$objQuote->dcterms_coverage;
                    if(!empty($dcterms_coverage_arr)){
                        foreach ($dcterms_coverage_arr as $item_coverage) {
                            array_push($catesNameArray, trim($item_coverage->a_value));
                        }
                    }
                }



            //วันที่ สร้างเอกสาร
                $dcterms_dateArray= array();
                $dcterms_date= $vconfig->config["nd"];
                if(isset($objQuote->dcterms_date)){
                    $dcterms_date_arr=$objQuote->dcterms_date;
                    if(!empty($dcterms_date_arr)){
                        foreach ($dcterms_date_arr as $date_set) {
                            //array_push($dcterms_dateArray, $vdcterms_date);
                            $dcterms_date = trim($date_set->a_value);
                            $dcterms_date=convert_date_yyyy_mm_dd($dcterms_date);
                        }
                    }
                }

                //ผู้สร้าง/เจ้าของผลงาน
                $dcterms_creator= $vconfig->config["nd"];
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
                    "catenames"=>$catesNameArray,
                    "title"=>$title,
                    "created"=>$dcterms_date,
                    'creator'=>$dcterms_creator
                );
                array_push($dataArray, $data);
            }
        }

        if(empty($dataArray)){
          //  echo "99";
          //  $data=array("[n.d.]");
          //  $this->response($data, 200);
          //  $status = parent::HTTP_EXPECTATION_FAILED;
            $response = ['n.d.'];
            $this->response($response,200);
            exit();
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
