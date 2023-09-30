<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Popularall_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $dataArray = array();
        $dataArray2 = array();
        $dataArray3 = array();
        $data_yyyy_arr=array();
        $vconfig = $this->config;

        //ย้อนหลัง จำนวนเดือน
        $revm = $this->input->get('revm', true);
        if(!empty($limitpermonth)){
            $vrevm=$revm;
        }else{
            $vrevm=3;
        }

        // $revm=ย้อนหลัง เช่น ย้อนหลัง 4 เดือน
        // $revm=4;
        // ย้อนหลัง ปี-เดือน
        $this_month = date("n", time());
        $this_year = date("Y", time());
        $months_array2 = array();
        for ($i = $this_year ; $i > ($this_year - $vrevm); $i--) {
            // echo "$i";
            // echo "<br>";
          //  if ($i < 1) {
              //  $correct_month_number = $i + 12;
              //  $value = str_pad($correct_month_number,2,"0",STR_PAD_LEFT);
           //     $months_array2[] = array($this_year - 1);
            $months_array2[] = array($i);
          //  }
          //  else {
             //   $value2 = str_pad($i,2,"0",STR_PAD_LEFT);
           //     $months_array2[] = array($this_year);
           // }
        }

        $toplimit = $this->input->get('toplimit', true);

        if(!empty($toplimit)){
            $vtoplimit=$toplimit;
        }else{
            $vtoplimit=999;
        }

//        echo "<pre>";
//        print_r($months_array2);
//        echo "</pre>";
        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี //Date Issued contains 2023-04 OR Date Issued contains 2023-05 Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";

        $vper_page = "&page=1&per_page=" . $vtoplimit;
        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "stats?resource_type=items&sort_by=hits&sort_order=desc&resource_type=items&type=resource".$resource_template . $vper_page;
        $json_objekat = cal_curl_api($api_url);

      //  http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/stats?resource_type=items&sort_by=hits&sort_order=desc&year=2022&by_period=year&page=1&per_page=1000
//         echo "<pre>999=";
//         print_r($json_objekat);
//         echo "</pre>";

        if (!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

                // echo "<pre>999=";
                // print_r($objQuote);
                // echo "</pre>";
                // $title=$objQuote->o_title;
                $o_entity_id = $objQuote->o_entity_id;
                $o_total_hits = $objQuote->o_total_hits;

                $o_created = $objQuote->o_created;
                $yyyy_created= $o_created->a_value;
                $yyyy_created1 = explode('-', $yyyy_created)[0];

//                echo "<pre>1=";
//                print_r($yyyy_created1);
//                echo "</pre>";


                $o_modified = $objQuote->o_modified;
                $yyyy_modified= $o_modified->a_value;
                $yyyy_modified1 = explode('-', $yyyy_modified)[0];

//                echo "<pre>2=";
//                print_r($yyyy_modified1);
//                echo "</pre>";

                $yyyy_pop=0;

                //มีปีเดียว
                $yyyy_arr = array();
                $yyyy_arr_check = array();
                //$yyyy_arr[] = 2022;
                if($yyyy_created1==$yyyy_modified1){
                        $yyyy_pop=$yyyy_created1;

                    if(!empty($months_array2)) {
                        foreach ($months_array2 as $month_yyyy) {
                          //  echo $month_yyyy[0].'=='.$yyyy_pop."<br>";
                          //  if($month_yyyy[0]==$yyyy_pop){
                               // $data_yyyy_arr=$month_yyyy;
                               //array_push($data_yyyy_arr,$yyyy_pop);
                                $yyyy_arr[] = $yyyy_pop;
                          //  }
                        }

//                        echo "<pre>aaa=";
//                        print_r($yyyy_arr);
//                        echo "</pre>";
                    }

                }else{ //ปีไม่ตรงกัน
                    if(!empty($months_array2)) {
                        foreach ($months_array2 as $month_yyyy) {
                            //  echo $month_yyyy[0].'=='.$yyyy_pop."<br>";
                           if($month_yyyy[0]==$yyyy_created1) {
                               // $data_yyyy_arr=$month_yyyy;
                               //array_push($data_yyyy_arr,$yyyy_pop);
                               $yyyy_arr[] = $yyyy_created1;
                           }else if($month_yyyy[0]==$yyyy_modified1){
                               $yyyy_arr[] = $yyyy_modified1;
                           }else{
                               if($yyyy_created1 > $yyyy_modified1){
                                   $yyyy_arr[] = $yyyy_modified1;
                               }else{
                                   $yyyy_arr[] = $yyyy_created1;
                               }

                           }
                        }

                    }

                }

                $data = array(
                    "id" => $o_entity_id,
                    "total_hits" => $o_total_hits,
                    "year"=>$yyyy_arr,
                );
                array_push($dataArray, $data);
            }
        }


       // if(!empty($months_array2)) {
          //  foreach ($months_array2 as $yyyy) {
               // $dataArray= [];
              //  $YYYY=$yyyy[0];

         //   }
     //   }

//         echo "<pre>";
//         print_r($dataArray);
//         echo "</pre>";

        if(!empty($months_array2)) {
            $i=0;
            foreach ($months_array2 as $month_yyyy) {
                $dataArray2=[];
                $month_yyyy = intval($month_yyyy[0]);
                 // echo $month_yyyy[0]."<br>";
                 if(!empty($dataArray)) {
                     foreach ($dataArray as $data_item) {
                       //  $dataitem1 = $data_item;
                         $item_id = $data_item['id'];
                         $total_hits = $data_item['total_hits'];
                         $year_arr = $data_item['year'];

                         $vyear= $year_arr[$i];
                         //หารายการที่ปีตรงกัน

                        if($month_yyyy == $vyear ) {
                            $api_url2 = $vomekas_url . "items/" . $item_id;
                            $json_objekat2    = cal_curl_api($api_url2);

                            if(!empty($json_objekat2)) {
                                if(empty( $json_objekat2->errors)){

                                    //thumbnail
                                    $thumbnail=$vconfig->config["nd"];
                                    if(isset($json_objekat2->thumbnail_display_urls)){
                                        $thumbnail_display_urls=$json_objekat2->thumbnail_display_urls;
                                        $thumbnail=$thumbnail_display_urls->large;
                                    }

                                    $dateArray= array();
                                    $dcterms_date= $vconfig->config["nd"];
                                    if(isset($json_objekat2->dcterms_date)){
                                        $dcterms_date_arr=$json_objekat2->dcterms_date;
                                        if(!empty($dcterms_date_arr)){
                                            foreach ($dcterms_date_arr as $item) {
                                               // array_push($dateArray, trim($item->a_value));
                                                $dcterms_date = trim($item->a_value);
                                                $dcterms_date=convert_date_yyyy_mm_dd($dcterms_date);
                                            }
                                        }
                                    }
                                    //else{
                                      //  array_push($dateArray, $vconfig->config["nd"]);
                                   // }

                                  /*  $coverageNameArray= array();
                                    if(isset($json_objekat2->o_item_set)){
                                        $dcterms_coverage_arr=$json_objekat2->o_item_set;
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
                                    $coverageNameArray= array();
                                    if(isset($json_objekat2->dcterms_coverage)){
                                        $dcterms_coverage_arr=$json_objekat2->dcterms_coverage;
                                        if(!empty($dcterms_coverage_arr)){
                                            foreach ($dcterms_coverage_arr as $item) {
                                                array_push($coverageNameArray, trim($item->a_value));
                                            }
                                        }
                                    }else{
                                    array_push($coverageNameArray, $vconfig->config["nd"]);
                                    }

                                    $data2 = array(
                                        "id"=>$json_objekat2->o_id,
                                        "title"=>$json_objekat2->o_title,
                                        "total_hits" => $total_hits,
                                        "thumbnail" => $thumbnail,
                                        //"date" => $dateArray,
                                        "date" => $dcterms_date,
                                        "cates" => $coverageNameArray,
                                        // "year"=>$yyyy_arr,
                                    );
                                    array_push($dataArray2, $data2);
                                }
                            }

                        }


                     }
                 }else{
                         $data2 = array();
                         array_push($data2, $vconfig->config["nd"]);
                         array_push($dataArray2, $data2);

                 }
                $i=$i+1;

                $data3 = array(
                    "year" => $month_yyyy,
                    "data" => $dataArray2,
                    // "year"=>$yyyy_arr,
                );
                array_push($dataArray3, $data3);

            }
        }



//        echo "<pre>999=";
//        print_r($dataArray3);
//        echo "</pre>";
//
//        exit();





        $this->response($dataArray3, 200);


    }

}
