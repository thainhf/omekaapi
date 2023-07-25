<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivelistall_controller extends REST_Controller
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
            $vlimitperpage=9999;
        }

        //$vper_page=$per_page;
        $vper_page = "&page=".$vpage."&per_page=" . $vlimitperpage;


        //ย้อนหลัง จำนวนเดือน
        $filter = $this->input->get('Class', true);
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

        $search = $this->input->get('search', true);
        $search_txt="";
        if(!empty($search)){
            $search_txt="&fulltext_search=".$search;
        }else{
            $search_txt="";
        }

        ////=====collection=============
        $collection = $this->input->get('collection', true);
        $collection_search="";
        if(!empty($collection)){

            $vcollection=$collection;
            $collection_array = explode(',', $vcollection );
            foreach ($collection_array as $key => $tag ) {
                $collection_search= $collection_search."&item_set_id[]=".$tag;
            }

        }else{
            $collection_search="";
        }

        //=====property=============
        $property = $this->input->get('property', true);


        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "items?".$search_txt.$filter_cates.$collection_search."&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0" . $vper_page;
        $api_url_info = $vomekas_url . "infos?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0";
      //  echo $api_url;

        //Data
        //fulltext_search=สสส&resource_class_id[]=23&resource_class_id[]=24&item_set_id[]=10&item_set_id[]=12&item_set_id[]=15&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0


        //Total
       // http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/infos?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0

        $json_objekat = cal_curl_api($api_url);

        if(!empty($json_objekat)) {
            foreach ($json_objekat as $objQuote) {

//                    echo "<pre>999=";
//                    print_r($objQuote->o_media);
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

                //cate

                $coverageNameArray= array();
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
                }

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

                $dcterms_createdArray= array();
                $dcterms_created="";
                if(isset($objQuote->dcterms_created)){
                    $dcterms_created_arr=$objQuote->dcterms_created;
                    if(!empty($dcterms_created_arr)){
                        foreach ($dcterms_created_arr as $date_set) {
                            $dcterms_created=trim($date_set->a_value);
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

                $mediaArray= array();
                if(isset($objQuote->o_media)){
//                    echo "<pre>999=";
//                    print_r($objQuote->o_media);
//                    echo "</pre>";
                   $media_arr=$objQuote->o_media;
                    if(!empty($media_arr)){
                        foreach ($media_arr as $item) {
                            $api_url4=$vomekas_url."media/".$item->o_id;
                            $json_objekat4    = cal_curl_api($api_url4);
                            if(!empty($json_objekat4)) {
                                $o_id4=$json_objekat4->o_id;
                                $o_source4=$json_objekat4->o_source;
                                $o_media_type=$json_objekat4->o_media_type;
                                $o_filename=$json_objekat4->o_filename;
                                $o_original_url=$json_objekat4->o_original_url;
                                $data_cate=array(
                                    "id"=>$o_id4,
                                    "source"=>$o_source4,
                                    "media_type"=>$o_media_type,
                                    "filename"=>$o_filename,
                                    "original_url"=>$o_original_url,
                                );
                                array_push($mediaArray, $data_cate);
                            }

                          //  array_push($mediaArray, trim($item->o_id));
                        }
                    }else{
                        $mediaArray[]="n.d.";
                    }
                }



                $dataAll=array();
                $data=array(
                    "title"=>$title,
                    "creator"=>$dcterms_creator,
                    "type"=>$typeArray,
                    "id"=>$o_id,
                    "thumbnail"=>$thumbnail,
                    "cates"=>$coverageNameArray,
                    "date"=>$dcterms_date,
                    "created"=>$dcterms_created,
                    "media"=>$mediaArray,
                );

                ////====================
                if(!empty($property)){
                    $vproperty=$property;
                    $property_array = explode(',', $vproperty );
                    foreach ($property_array as $key => $tag ) {
                        $property_name= $tag;
                       // echo $property_name.'|<br>';


                        $subjectArray= array();
                      //  $obj_arr = $objQuote->{$property_name};
                        if(isset($objQuote->{$property_name})){
                            $obj_arr = $objQuote->{$property_name};
                          //  echo "<pre>obj_arr =";
                         //   print_r($obj_arr);
                          //  echo "</pre>";
                            //   $subject_arr=$objQuote->dcterms_subject;
                            if(!empty($obj_arr)){
                                foreach ($obj_arr as $item) {
                                    array_push($subjectArray, trim($item->a_value));
                                }
                            }

                            //$data1=array(
                            //    $property_name=>$subjectArray,
                           // );
                            // $dataAll = array_merge($data,$data1);

                            ${$property_name}=array(
                                $property_name=>$subjectArray,
                            );
//                            echo "<pre>kkkk =";
//                            print_r(${$property_name});
//                            echo "</pre>";
                          //  $data[$property_name] = ${$property_name};
                            $data[$property_name] = $subjectArray;
                           // $dataAll = array_merge($data,${$property_name});
                        }else{
                            $data[$property_name] = "n.d.";
                        }


                    }
                }
                //====================
                array_push($dataArray, $data);
                /*if($dataAll){
                    array_push($dataArray, $dataAll);
                }else{
                    array_push($dataArray, $data);
                }*/


              //  array_push($dataArray, $data);
            }
        }

       // $array_a = array('0'=>'foo', '1'=>'bar');
       // $array_b = array('foo'=>'0', 'bar'=>'1');

       // $array_c = array_merge($array_a,$array_b);
//        echo "<pre>array_c =";
//        print_r($dataArray);
//        echo "</pre>";
//        exit();

       // $this->response($dataArray, 200);
           $this->response($dataArray, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
