<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivelist_controller extends REST_Controller
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
        $page = $this->input->get('page', true);
        if(!empty($page)){
            $vpage=$page;
        }else{
            $vpage=1;
        }

        $limitperpage = $this->input->get('limitperpage', true);

        if(!empty($limitperpage)){
            $vlimitperpage=$limitperpage;
        }else{
            $vlimitperpage=10;
        }

        //$vper_page=$per_page;
        $vper_page = "&page=".$vpage."&per_page=" . $vlimitperpage;

        $vomekas_url = $vconfig->config["omekas_url"];

        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี //Date Issued contains 2023-04 OR Date Issued contains 2023-05 Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";

        $api_url = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0".$resource_template . $vper_page;
        $api_url_info = $vomekas_url . "infos?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0".$resource_template;
      //  echo $api_url;

        //Data
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0&page=1&per_page=10

       // http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id%5B%5D=23&datetime%5B0%5D%5Bjoiner%5D=and&datetime%5B0%5D%5Bfield%5D=created&datetime%5B0%5D%5Btype%5D=gte&datetime%5B0%5D%5Bvalue%5D=&datetime%5B0%5D%5Bjoiner%5D=and&datetime%5B0%5D%5Bfield%5D=created&datetime%5B0%5D%5Btype%5D=gte&datetime%5B0%5D%5Bvalue%5D=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0&per_page=1000

        //Total
        // http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/infos?resource_class_id%5B%5D=23&datetime%5B0%5D%5Bjoiner%5D=and&datetime%5B0%5D%5Bfield%5D=created&datetime%5B0%5D%5Btype%5D=gte&datetime%5B0%5D%5Bvalue%5D=&datetime%5B0%5D%5Bjoiner%5D=and&datetime%5B0%5D%5Bfield%5D=created&datetime%5B0%5D%5Btype%5D=gte&datetime%5B0%5D%5Bvalue%5D=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0&per_page=1000

        //Total
       // http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/infos?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0

        $json_objekat = cal_curl_api($api_url);

        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

//                    echo "<pre>999=";
//                    print_r($objQuote);
//                    echo "</pre>";
                $title=$objQuote->o_title;
                $o_id=$objQuote->o_id;
                //  $dcterms_date=$objQuote->dcterms_date; //วันที่สร้างเอกสาร

                $thumbnail=$vconfig->config["nd"];
                //part รูป
                if(isset($objQuote->thumbnail_display_urls)){
                    $thumbnail_arr=$objQuote->thumbnail_display_urls;
                    if(!empty($thumbnail_arr)){
                        $thumbnail_url=$thumbnail_arr->large;
                        if($thumbnail_url!=null){
                            $thumbnail = $thumbnail_url;
                        }

                    }
                }



                //cat Collection

                //cate

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

/*
                if(isset($objQuote->dcterms_coverage)){
                    $dcterms_coverage_arr=$objQuote->dcterms_coverage;
                    if(!empty($dcterms_coverage_arr)){
                        foreach ($dcterms_coverage_arr as $item_coverage) {
                            array_push($catesNameArray, trim($item_coverage->a_value));
                        }
                    }
                }
                */



                //วันที่ สร้างเอกสาร
                $dcterms_dateArray= array();
                $dcterms_date=$vconfig->config["nd"];;
                $find = '/';
                $replacement = '-';
                if(isset($objQuote->dcterms_date)){
                    $dcterms_date_arr=$objQuote->dcterms_date;
                    if(!empty($dcterms_date_arr)){
                        foreach ($dcterms_date_arr as $date_set) {
                            $vdate = trim($date_set->a_value);
                          //  $vdate = str_replace($find, $replacement, $vdate);
                            $dcterms_date=convert_date_yyyy_mm_dd($vdate);
                          //  $date_set = str_replace($find, $replacement, $date_set->a_value);
                          //  $dcterms_date=trim($date_set);
                        //    array_push($dcterms_dateArray, $dcterms_date);
                        }
                    }
                }

                //ผู้สร้าง/เจ้าของผลงาน
                $dcterms_creator=$vconfig->config["nd"];;
                if(isset($objQuote->dcterms_creator)){
                    $dcterms_creator_arr=$objQuote->dcterms_creator;
                    if(!empty($dcterms_creator_arr)){
                        foreach ($dcterms_creator_arr as $date_set) {
                            $dcterms_creator=trim($date_set->a_value);
                            //array_push($dcterms_dateArray, $vdcterms_date);
                        }
                    }
                }

                //ประเภทของ รายการ
                $typeArray= array();
                if(isset($objQuote->dcterms_type)){
                    $type_arr=$objQuote->dcterms_type;
                    if(!empty($type_arr)){
                        foreach ($type_arr as $item) {
                            array_push($typeArray, trim($item->a_value));
                        }
                    }
                }

                $data=array(
                    "title"=>$title,
                    "creator"=>$dcterms_creator,
                    "type"=>$typeArray,
                    "id"=>$o_id,
                    "thumbnail"=>$thumbnail,
                   // "cates"=>$coverageNameArray,
                    "cates"=>$catesArray,
                    "catenames"=>$catesNameArray,
                    "created"=>$dcterms_date,

                );
                array_push($dataArray, $data);
            }
        }

     //   exit();

        //หาจำนวน รายการทั้งหมด
        $json_objekat_info = cal_curl_api($api_url_info);
        $total=0;
        if(!empty($json_objekat_info)){


                //part รูป
                if(isset($json_objekat_info->items)){
                    $itemsall_arr=$json_objekat_info->items;
                    if(!empty($itemsall_arr)){
                        $total=$itemsall_arr->total;
                    }
                }
//
//            echo "<pre>999=";
//                    print_r($json_objekat_info);
//                    echo "</pre>";


        }
        $dataAll=array(
            "data"=>$dataArray,
            "page"=>$vpage,
	        "page_size"=>$vlimitperpage,
	        "total"=>$total,
        );

       // $this->response($dataArray, 200);
           $this->response($dataAll, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
