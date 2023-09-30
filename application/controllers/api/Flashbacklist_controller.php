<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Flashbacklist_controller extends REST_Controller
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
        $skipy = $this->input->get('skipy', true);
        if(!empty($skipy)){
            $vskipy=$skipy;
        }else{
            $vskipy=0;
        }
       // skipm=04
        $skipm = $this->input->get('skipm', true);

        if(!empty($skipm)){
            $vskipm=$skipm;
        }else{
            $vskipm=00;
        }

        $limitperpage = $this->input->get('limitperpage', true);

        if(!empty($limitperpage)){
            $vlimitperpage=$limitperpage;
        }else{
            $vlimitperpage=100;
        }

        //$vper_page=$per_page;
        $vper_page = "&page=1&per_page=" . $vlimitperpage;
        $p_n=$vskipy; //เลข ปี ย้อนหลัง
        $p_mm=$vskipm;
        $curYear = date('Y'); //คศ.
        $p_yyyy=$curYear-$p_n;

      //  echo $skipy;

        if($p_mm == "00"){
            $YYYY_MM=$p_yyyy; //2023-04
        }else{
            $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04
        }


        $vomekas_url = $vconfig->config["omekas_url"];

        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=2018&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0

        //property[0][joiner]=and
        //&property[0][property]=7
        //&property[0][type]=in
        //&property[0][text]=2018
        //&site_id=1

        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";


        $vurl="property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=".$YYYY_MM."&site_id=1&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&has_media=&has_original=&has_thumbnails=&has_tags=0".$resource_template;

        $api_url = $vomekas_url . "items?".$vurl . $vper_page;

        $api_url_count = $vomekas_url."infos?" .$vurl;




       // $api_url = $vomekas_url . "items?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=".$YYYY_MM."&resource_class_id[]=23&sort_by=title&sort_order=asc&datetime[0][type]=gte" . $vper_page;
       // echo $api_url."<br>";


        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?property[0][joiner]=and&property[0][property]=7&property[0][type]=in&property[0][text]=2003&resource_class_id[]=23&sort_by=title&sort_order=asc&datetime[0][type]=gte&page=1&per_page=100

        $json_objekat = cal_curl_api($api_url);

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
             //   $dcterms_date="n.d.";
                $dcterms_date = $vconfig->config["nd"];
                if(isset($objQuote->dcterms_date)){
                    $dcterms_date_arr=$objQuote->dcterms_date;
                    if(!empty($dcterms_date_arr)){
                        foreach ($dcterms_date_arr as $date_set) {
                            $dcterms_date = trim($date_set->a_value);
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
                    "cates"=>$catesArray,
                    "catenames"=>$catesNameArray,
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
