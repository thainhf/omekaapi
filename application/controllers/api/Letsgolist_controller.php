<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Letsgolist_controller extends REST_Controller
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
        $limit = $this->input->get('limit', true);
        if(!empty($limit)){
            $vlimit=$limit;
        }else{
            $vlimit=20;
        }

        if(!empty($cyear)){
            $year_count=$cyear;
        }else{
            $year_count=5;
        }

        $cur_year = date("Y");

      //  echo $cur_year."<br>";

        function dateRange($first, $last, $step = '+1 year', $format = 'Y-m-d' ) {
            $dates = array();
            $current = strtotime($first);
            $last = strtotime($last);

            while( $current <= $last ) {
                $dates[] = date($format, $current);
                $current = strtotime($step, $current);
            }
            return $dates;
        }

        $cur_year_back=$cur_year-$year_count+1;
        $startTime  = $cur_year_back.'-01-01';
        $endTime = $cur_year.'-01-01';

       // echo $startTime."-".$endTime."<br>";

        $year_arr = dateRange($startTime, $endTime, "+1 year", "Y");
        rsort($year_arr); //เรียงจาก มากไปน้อย
     //   print_r($year_arr);


      //  $begin = new DateTime( "2015-07-03" );
       // $end   = new DateTime( "2015-07-09" );
      //  $year
     //   echo "<br>";
        $vomekas_url = $vconfig->config["omekas_url"];
      //  $catesNameArray= array();

        if(!empty($year_arr)){
            // property[0][property]=7 คือ ค้นหา Dublin Core : date
            $vurl="";
            $i=0;
            $j=0;
            foreach ($year_arr as $yyyy) {
                $i=$i+1;
                if($i==1) {
                    $vurl = "&property[0][property]=7&property[0][type]=in&property[0][text]=" . $yyyy;
                }else{
                    $vurl=$vurl."&property[".$j."][joiner]=or&property[".$j."][property]=7&property[".$j."][type]=in&property[".$j."][text]=".$yyyy;
                }
                $j=$j+1;

            }

            //คลังดิจิทัลเอกสารผลงานของศาสตราจารย์นายแพทย์ประเวศ วะสี

            //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1
            //&has_media=&has_original=&has_thumbnails=&has_tags=0
            //&page=1&per_page=200

            // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
            $resource_template="&resource_template_id[]=2";

            $vurl=$vurl."&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0".$resource_template;
            $api_url_count = $vomekas_url."infos?property[0][joiner]=and" .$vurl;


         //   echo $api_url_count."<br>";

//https://omeka.p-set.org/api/infos?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=2023&property[1][joiner]=or&property[0][property]=7&property[0][type]=in&property[0][text]=2022&property[1][joiner]=or&property[0][property]=7&property[0][type]=in&property[0][text]=2021&property[1][joiner]=or&property[0][property]=7&property[0][type]=in&property[0][text]=2020&property[1][joiner]=or&property[0][property]=7&property[0][type]=in&property[0][text]=2019&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0

            //187
//https://omeka.p-set.org/api/infos?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=2023&property[1][joiner]=or&property[1][property]=7&property[1][type]=in&property[1][text]=2022&property[2][joiner]=or&property[2][property]=7&property[2][type]=in&property[2][text]=2021&property[3][joiner]=or&property[3][property]=7&property[3][type]=in&property[3][text]=2020&property[4][joiner]=or&property[4][property]=7&property[4][type]=in&property[4][text]=2019&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0

            $json_objcount = cal_curl_api($api_url_count);
//            echo "<pre>";
//            print_r($json_objcount->items->total);
//            echo "</pre>";

            $vcount=$json_objcount->items->total;
            $api_url = $vomekas_url."items?property[0][joiner]=and" .$vurl."&page=1&per_page=".$vcount;
          //  echo $api_url."<br>";
            $json_objekat1 = cal_curl_api($api_url);


            if(!empty($json_objekat1)){

//                 echo "<pre>";
//                 print_r($json_objekat1);
//                 echo "</pre>";
                $random_numbers = [];
                $random_number2 = 0;
                while(count($random_numbers) < $vlimit){
                    do  {
                        $random_number = mt_rand(0,$vcount-1);
                        $random_number2 = $random_number;
                    } while (in_array($random_number2, $random_numbers));
                // $random_numbers[] = $random_number;
                    if($random_number2 != 0){
                        $random_numbers[] = $random_number2;
                    }
                }
//                echo "<pre>";
//                print_r($random_numbers);
//                echo "</pre>";





                foreach ($random_numbers as $item) {


                    $title=$json_objekat1[$item]->o_title;
                    $o_id=$json_objekat1[$item]->o_id;

                    $catesNameArray= array();
                    if(isset($json_objekat1[$item]->dcterms_coverage)){
                        $dcterms_coverage_arr=$json_objekat1[$item]->dcterms_coverage;
                        if(!empty($dcterms_coverage_arr)){
                            foreach ($dcterms_coverage_arr as $item_coverage) {
                                array_push($catesNameArray, trim($item_coverage->a_value));
                            }
                        }
                    }

                    $data=array(
                        "id"=>$o_id,
                        "title"=>$title,
                        "catenames"=>$catesNameArray,
                    );
                    array_push($dataArray, $data);

                }



            }



        }


        $this->response($dataArray, 200);

      //  exit();



      //  $p_yyyy=$curYear-$p_n;

      //  echo $skipy;

      //  if($p_mm == "00"){
       //     $YYYY_MM=$p_yyyy; //2023-04
      //  }else{
       //     $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04
      //  }

//http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?per_page=100
        //https://omeka.p-set.org/api/items?resource_class_id[]=23&sort_by=created&sort_order=desc&per_page=100

        //https://omeka.p-set.org/api/items?site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&has_media=&has_original=&has_thumbnails=&has_tags=0

        //ค้นหา
        //ปีที่ผลิตเอกสาร
        //dcterms:date = 2023 //Date contains 2023 OR Date contains 2022 2021 2020 2019 | Site คลังดิจิทัลเอกสารผลงานของศาสตราจารย์นายแพทย์ประเวศ วะสี

        // ค้นหา จาก omeka s
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=2023&property[1][joiner]=or&property[1][property]=7&property[1][type]=in&property[1][text]=2022&property[2][joiner]=or&property[2][property]=7&property[2][type]=in&property[2][text]=2021&property[3][joiner]=or&property[3][property]=7&property[3][type]=in&property[3][text]=2020&property[4][joiner]=or&property[4][property]=7&property[4][type]=in&property[4][text]=2019&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0&page=7

        //api ok ตามปี ค้นหา 5 ปี
        //https://omeka.p-set.org/api/items?property[0][joiner]=and

        //&property[0][property]=7&property[0][type]=in&property[0][text]=2023

        //&property[1][joiner]=or&property[1][property]=7&property[1][type]=in&property[1][text]=2022

        //&property[2][joiner]=or
        //&property[2][property]=7
        //&property[2][type]=in
        //&property[2][text]=2021

        //&property[3][joiner]=or
        //&property[3][property]=7
        //&property[3][type]=in
        //&property[3][text]=2020

        //&property[4][joiner]=or
        //&property[4][property]=7
        //&property[4][type]=in
        //&property[4][text]=2019

        //คลังดิจิทัลเอกสารผลงานของศาสตราจารย์นายแพทย์ประเวศ วะสี

        //&site_id=1
        //&sort_by=created
        //&sort_order=desc
        //&datetime[0][joiner]=and
        //&datetime[0][field]=created
        //&datetime[0][type]=gte
        //&datetime[0][value]=
        //&is_public=1
        //&has_media=
        //&has_original=
        //&has_thumbnails=
        //&has_tags=0
        //&page=1
        //&per_page=200



        //รายการทั้งหมดใน collection
     //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id[]=23&sort_by=created&sort_order=desc&per_page=1000
      //  $vomekas_url = $vconfig->config["omekas_url"];
      //  $api_url = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc" . $vper_page;
      //  $vper_page2 = "page=1&per_page=".$vlimit2;
      //  $api_url2 = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc" . $vper_page2;
      //  $api_url1 = $vomekas_url . "items?resource_class_id[]=23&id=";
      //  echo $api_url2;

/*

        $random_numbers = [];
      //  $random_numbers2 = [];
        $random_number2 = 0;
        while(count($random_numbers) < $vlimit){

            do  {
                $random_number = mt_rand(200,3000);
                $api_url3=$api_url1.$random_number;
                //http://localhost/omeka-s323/api/items?resource_class_id[]=23&id=127
               // echo $api_url3.'<br><br>';
                $json_objekat3 = cal_curl_api($api_url3);
                if(!empty($json_objekat3)){
                    //ที่ไม่มี error หรือ มีข้อมูล
                  //  if(empty( $json_objekat3->errors)){
                       // echo "<pre>";
                       // print_r($json_objekat3);
                       // echo "</pre>";
                      //  $random_numbers2[] = $random_number;

                        $title=$json_objekat3[0]->o_title;
                        $o_id=$json_objekat3[0]->o_id;

                    $catesNameArray= array();
                    if(isset($json_objekat3[0]->dcterms_coverage)){
                        $dcterms_coverage_arr=$json_objekat3[0]->dcterms_coverage;
                        if(!empty($dcterms_coverage_arr)){
                            foreach ($dcterms_coverage_arr as $item_coverage) {
                                array_push($catesNameArray, trim($item_coverage->a_value));
                            }
                        }
                    }

                        $data=array(
                            "id"=>$o_id,
                           "title"=>$title,
                            "catenames"=>$catesNameArray,
                        );
                        array_push($dataArray, $data);
                        $random_number2 = $random_number;
                  //  }
                }

           // } while (in_array($random_number, $random_numbers));
            } while (in_array($random_number2, $random_numbers));
           // $random_numbers[] = $random_number;
            if($random_number2 != 0){
                $random_numbers[] = $random_number2;
            }

        }
*/

//        echo "<pre>";
//        print_r($random_numbers);
//        echo "</pre>";

     //   $this->response($dataArray, 200);

        //   $this->response($dataAll, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
