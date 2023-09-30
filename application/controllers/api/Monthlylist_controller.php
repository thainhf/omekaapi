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
        //เนื้อหาคัดสรร (ปี crty = 2023 , เดือน เมษายน crtm=04, จำนวนรายการต่อหน้า limitpermonth=10) [crty=yyyy,crtm=mm,limitpermonth=n]
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
            $yyyy=$crty;
           // $crtall=$crtall.$crty;
        }else{
            $yyyy="";
           // $crtall=$crtall;
        }
      //  echo $crtall;
       // $mm="";
        if(!empty($crtm)){
            $mm=$crtm;
         //   $crtall=$crtall.'-'.$crtm;
        }else{
            $mm="";
          //  $crtall=$crtall;
        }
    //    $p_yyyy=$yyyy;
     //   $p_mm=$mm;
      //  $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04
      //  $YYYY_MM=$crtall;
      //  echo $crtall;

       // $str = "Hello world";
        $mmArr = explode(",", $mm);
        $i=0;
        $j=0;
        $vurl="";
        foreach ($mmArr as $mm1) {
          //  echo "<li>$mm</li>";
            //2023-04
            $yyyy_mm=$yyyy."-".$mm1;
            $i=$i+1;
            if($i==1) {
                $vurl = "&property[0][property]=23&property[0][type]=in&&property[0][text]=" . $yyyy_mm;
            }else{
                $vurl=$vurl."&property[".$j."][joiner]=or&property[".$j."][property]=23&property[".$j."][type]=in&property[".$j."][text]=".$yyyy_mm;
            }
            $j=$j+1;
        }


        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?property[0][joiner]=and&property[0][property]=23



        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?property[0][joiner]=and

        //&property[0][property]=23 // ค้นหาจาก วันที่เผยแพร่ dcterms:issued

        //&property[0][type]=in
        //&property[0][text]=2023-04

        //&property[1][joiner]=or
        //&property[1][property]=23
        //&property[1][type]=in
        //&property[1][text]=2023-05

        //&property[2][joiner]=or
        //&property[2][property]=23
        //&property[2][type]=in
        //&property[2][text]=2023-06

        //&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0

        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี //Date Issued contains 2023-04 OR Date Issued contains 2023-05 Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";
        $vurl=$vurl."&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0".$resource_template;

       // $api_url_count = $vomekas_url."infos?property[0][joiner]=and" .$vurl;

        $api_url = $vomekas_url."items?property[0][joiner]=and" .$vurl."&page=1&per_page=".$vlimitpermonth;
   //     echo $api_url;
     //   exit();

      //  $api_url=$vomekas_url."items?property[0][joiner]=and&property[0][property]=23&property[0][type]=in&property[0][text]=".$YYYY_MM."&sort_by=created&sort_order=desc&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][field]=created&datetime[0][type]=gte".$vper_page;


        $json_objekat    = cal_curl_api($api_url);

//        echo "<pre>999=";
//    print_r($json_objekat);
//    echo "</pre>";


        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

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
