<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Staffpicks_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $dataArray= array();
        $dataArrayAll= array();
        $vconfig=$this->config;

        //ย้อนหลัง จำนวนเดือน
        $revm = $this->input->get('revm', true);
        if(!empty($revm)){
            $vrevm=$revm;
        }else{
            $vrevm=3;
        }

        //จำนวนรายการต่อ เดือน หรือ ถ้า 0 คือทั้งหมด
        $limitpermonth = $this->input->get('limitpermonth', true);
        //echo $crty;
        if(!empty($limitpermonth)){
            $vlimitpermonth=$limitpermonth;
        }else{
            $vlimitpermonth=99;
        }

        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี //Date Issued contains 2023-04 OR Date Issued contains 2023-05 Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";

        $vper_page= "&page=1&per_page=".$vlimitpermonth;

        $vomekas_url= $vconfig->config["omekas_url"];

       // $curYear = date('Y'); //คศ.
       // $curMonth = date('m'); //Month
       // echo $curYear."|";
      //  echo $curMonth;


        $this_month = date("n", time());
       // echo "<br>m=".$this_month."<br>";
        $this_year = date("Y", time());
       /* $months_array = array();
        for ($i = $this_month - 1; $i > ($this_month - 13); $i--) {
            echo "$i";
            echo "<br>";
            if ($i < 1) {
                $correct_month_number = $i + 12;
             //   echo $correct_month_number."-";
               // $months_array[] = array($correct_month_number, $this_year - 1);
                $months_array[] = array($correct_month_number, $this_year - 1);
            }
            else {
              //  $months_array[] = array($i, $this_year);
                $months_array[] = array($i, $this_year);
            }
        }
        echo "<pre>";
        print_r($months_array);
        echo "</pre>";
        */

        // $revm=ย้อนหลัง เช่น ย้อนหลัง 4 เดือน
       // $revm=4;
        // ย้อนหลัง ปี-เดือน
        $months_array2 = array();
        for ($i = $this_month ; $i > ($this_month - $vrevm); $i--) {
           // echo "$i";
           // echo "<br>";
            if ($i < 1) {
                $correct_month_number = $i + 12;
                $value = str_pad($correct_month_number,2,"0",STR_PAD_LEFT);
                $months_array2[] = array($value, $this_year - 1);
            }
            else {
                $value2 = str_pad($i,2,"0",STR_PAD_LEFT);
                $months_array2[] = array($value2, $this_year);
            }
        }
//        echo "<pre>";
//        print_r($months_array2);
//        echo "</pre>";

        //$crtall=$crtall.'-'.$crtm;
        if(!empty($months_array2)) {
            foreach ($months_array2 as $month_yyyy) {
              //  echo $month_yyyy[1]."-".$month_yyyy[0]."<br>";
                $dataArray= [];
                $YYYY_MM=$month_yyyy[1]."-".$month_yyyy[0]; //yyyy-mm
                $api_url=$vomekas_url."items?property[0][joiner]=and&property[0][property]=23&property[0][type]=in&property[0][text]=".$YYYY_MM."&sort_by=created&sort_order=desc&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][field]=created&datetime[0][type]=gte".$resource_template.$vper_page;

                $json_objekat    = cal_curl_api($api_url);
                if(!empty($json_objekat)) {
                    foreach ($json_objekat as $objQuote) {
                        //=======================
                        $title=$objQuote->o_title;
                        $o_id=$objQuote->o_id;

                        $thumbnail=$vconfig->config["nd"];
                        //part รูป
                        if(isset($objQuote->thumbnail_display_urls)){
                            $thumbnail_arr=$objQuote->thumbnail_display_urls;
                            if(!empty($thumbnail_arr)){
                                $thumbnail_url=$thumbnail_arr->large;
                                if($thumbnail_url!=null && $thumbnail_url!="/application/asset/thumbnails/default.png?v=3.2.3" ){
                                    $thumbnail = $thumbnail_url;
                                }
                               // $thumbnail = $thumbnail_url;

                            }
                        }



                       /*$coverageNameArray= array();
                        if(isset($objQuote->o_item_set)){
                            $dcterms_coverage_arr=$objQuote->o_item_set;
                            if(!empty($dcterms_coverage_arr)){
                                foreach ($dcterms_coverage_arr as $item) {
                                    //array_push($coverageNameArray, trim($item->a_value));
                                    $api_url3=$vomekas_url."item_sets/".$item->o_id;
                                    $json_objekat3    = cal_curl_api($api_url3);
                                    if(!empty($json_objekat3)) {
                                        $o_id3=$json_objekat3->o_id;
                                        $title3=$json_objekat3->o_title;
                                        $data_cate=array(
                                            "id"=>$o_id3,
                                            "title"=>$title3,
                                        );
                                        array_push($coverageNameArray, $data_cate);
                                    }

                                }
                            }
                        }*/

                        $catesArray= array();
                        $catesNameArray= array();
                        if(isset($objQuote->o_item_set)){
                            $item_set_arr=$objQuote->o_item_set;
                            if(!empty($item_set_arr)){
                                foreach ($item_set_arr as $item_set) {
                                    $vid_set=$item_set->o_id;
                                    array_push($catesArray, $vid_set);
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
                        $dcterms_date=$vconfig->config["nd"];
                        if(isset($objQuote->dcterms_date)){
                            $dcterms_date_arr=$objQuote->dcterms_date;
                            if(!empty($dcterms_date_arr)){
                                foreach ($dcterms_date_arr as $date_set) {
                                    $dcterms_date=trim($date_set->a_value);
                                    $dcterms_date=convert_date_yyyy_mm_dd($dcterms_date);
                                    //array_push($dcterms_dateArray, $vdcterms_date);
                                }
                            }
                        }

                        //ผู้สร้าง/เจ้าของผลงาน
                        $dcterms_creator=$vconfig->config["nd"];
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
                          //  "cates"=>$catesNameArray,
                           // "cates"=>$coverageNameArray,
                            "cates"=>$catesArray,
                            "catenames"=>$catesNameArray,
                            "title"=>$title,
                            "created"=>$dcterms_date,
                            'creator'=>$dcterms_creator
                        );
                        array_push($dataArray, $data);

                        //=====================

                    }
                }else{
                    $data=array('n.d.');
                    array_push($dataArray, $data);
                }

                    $data2=array(
                        "month"=>$month_yyyy[0],
                        "year"=>$month_yyyy[1],
                        "data"=>$dataArray,
                    );
                    array_push($dataArrayAll, $data2);

                //===end if=json_objekat==========

            }
        }

      //  echo "<pre>";
     //   print_r($dataArray);
   //     echo "</pre>";

//        echo "<pre>";
//        print_r($dataArrayAll);
//        echo "</pre>";
        $this->response($dataArrayAll, 200);


     //   $this->response($dataAll, 200);
     /*   $this->response(array(
            "status" => 1,
            "message" => "Students found99999",
            "data" => "9999"
        ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด
    }


}
