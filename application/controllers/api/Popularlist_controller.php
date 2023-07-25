<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Popularlist_controller extends REST_Controller
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

        $toplimit = $this->input->get('toplimit', true);

        if(!empty($toplimit)){
            $vtoplimit=$toplimit;
        }else{
            $vtoplimit=5;
        }
       // echo $vtoplimit;
        $vper_page = "&page=1&per_page=" . $vtoplimit;
        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "stats?sort_by=hits&sort_order=desc&resource_type=items&type=resource" . $vper_page;
        $json_objekat = cal_curl_api($api_url);


        if (!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

                // echo "<pre>999=";
                // print_r($objQuote);
                // echo "</pre>";
                // $title=$objQuote->o_title;
                $o_entity_id = $objQuote->o_entity_id;
                $o_total_hits = $objQuote->o_total_hits;


                $data = array(
                    "id" => $o_entity_id,
                    "total_hits" => $o_total_hits,
                );
                array_push($dataArray, $data);
            }
        }
        //loop หารายการ รายละเอียด


        if (!empty($dataArray)) {
            foreach ($dataArray as $item) {
                //===============================
                $item_set_id = $item["id"];
                $item_set_total_hits = $item["total_hits"];

                // หารายการ รายละเอียด
                // $item_set_id=$o_entity_id;
                $api_url2 = $vomekas_url . "items/" . $item_set_id;
                //echo $api_url2."<br>";
                $json_objekat2 = cal_curl_api($api_url2);
                if (!empty($json_objekat2)) {
                    //             echo "<pre>999=";
                    //                    print_r($json_objekat2);
                    //                    echo "</pre>";
                    $title = $json_objekat2->o_title;
                    $o_id = $json_objekat2->o_id;

                    $thumbnail = "";
                    //part รูป
                    if (isset($json_objekat2->thumbnail_display_urls)) {
                        $thumbnail_arr = $json_objekat2->thumbnail_display_urls;
                        if (!empty($thumbnail_arr)) {
                            $thumbnail_url = $thumbnail_arr->large;
                            $thumbnail = $thumbnail_url;
                        }
                    }


                    //cat Collection

                    $catesArray = array();
                    $catesNameArray = array();
                    if (isset($json_objekat2->o_item_set)) {
                        $item_set_arr = $json_objekat2->o_item_set;
                        if (!empty($item_set_arr)) {
                            foreach ($item_set_arr as $item_set) {
                                $vid_set = $item_set->o_id;
                                array_push($catesArray, $vid_set);
                            }
                        }
                    }

                    if (isset($json_objekat2->dcterms_coverage)) {
                        $dcterms_coverage_arr = $json_objekat2->dcterms_coverage;
                        if (!empty($dcterms_coverage_arr)) {
                            foreach ($dcterms_coverage_arr as $item_coverage) {
                                array_push($catesNameArray, trim($item_coverage->a_value));
                            }
                        }
                    }


                    //วันที่ สร้างเอกสาร
                    $dcterms_dateArray = array();
                    $dcterms_date = "n.d.";
                    if (isset($json_objekat2->dcterms_date)) {
                        $dcterms_date_arr = $json_objekat2->dcterms_date;
                        if (!empty($dcterms_date_arr)) {
                            foreach ($dcterms_date_arr as $date_set) {
                                $dcterms_date = trim($date_set->a_value);
                                $dcterms_date=convert_date_yyyy_mm_dd($dcterms_date);
                             //   $created=$created_date;
                                //array_push($dcterms_dateArray, $vdcterms_date);
                            }
                        }
                    }

                    //ผู้สร้าง/เจ้าของผลงาน
                    $dcterms_creator = "";
                    if (isset($json_objekat2->dcterms_creator)) {
                        $dcterms_creator_arr = $json_objekat2->dcterms_creator;
                        if (!empty($dcterms_creator_arr)) {
                            foreach ($dcterms_creator_arr as $dcterms_set) {
                                $dcterms_creator = trim($dcterms_set->a_value);
                                //array_push($dcterms_dateArray, $vdcterms_date);
                            }
                        }
                    }

                    //=========================

                    $data2 = array(
                        "id" => $o_id,
                        "total_hits" => $item_set_total_hits,
                        "thumbnail" => $thumbnail,
                        "cates" => $catesArray,
                        "catenames" => $catesNameArray,
                        "title" => $title,
                        "created" => $dcterms_date,
                        'creator' => $dcterms_creator
                    );
                    array_push($dataArray2, $data2);

                }
            }
        }


        $this->response($dataArray2, 200);


    }

}
