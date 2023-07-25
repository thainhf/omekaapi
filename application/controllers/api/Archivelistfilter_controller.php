<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivelistfilter_controller extends REST_Controller
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

        $cates = $this->input->get('cates', true);
        $search_cates="";
        if(!empty($cates)){
            $listcates=$cates;
          //  $listcates = '10,12,17';
            $cate_array = explode(',', $listcates );
            foreach ($cate_array as $key => $tag ) {
                $search_cates= $search_cates."&item_set_id[]=".$tag;
            }

        }else{
            $listcates=0;
        }

        $search = $this->input->get('search', true);
        $search_text = "";
        if(!empty($search)){
            $search_text="&fulltext_search=".$search;
        }else{
            $search_text="";
        }

        $sort_by = $this->input->get('sort_by', true);
        $sort_by_txt = "";
        if(!empty($sort_by)){
            $sort_by_txt="&sort_by=".$sort_by;
        }else{
            $sort_by_txt="";
        }


        $sort_order = $this->input->get('sort_order', true);
        $sort_order_txt = "";
        if(!empty($sort_order)){
            $sort_order_txt="&sort_order=".$sort_order;
        }else{
            $sort_order_txt="";
        }

        $media_types = $this->input->get('media_types', true);
        $media_types_txt = "";
        if(!empty($media_types)){
            $media_array = explode(',', $media_types );
            foreach ($media_array as $key => $media ) {
                if($media=="book"){
                    $tag ="application/pdf";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                }

                if($media=="image"){
                    $tag ="image/jpeg";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                    $tag ="image/png";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                }

                if($media=="vdo" || $media=="sound" ){
                    $tag ="audio/mpeg";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                }
                if($media=="doc"){
                    $tag ="application/msword";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                    $tag ="application/rtf";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                    $tag ="application/msword";
                    $media_types_txt= $media_types_txt."&media_types[]=".$tag;
                }

            }
        }else{
            $media_types_txt="";
        }

     //   application/msword application/pdf application/rtf audio/mpeg image/jpeg image/png application/msword  audio/mpeg image/jpeg image/png

          //  $listcates = '10,12,17';

      //  echo $search_text.$search_cates.$sort_by_txt.$sort_order_txt.$media_types_txt;
     //   exit();



        //$vper_page=$per_page;
        $vper_page = "&page=".$vpage."&per_page=" . $vlimitperpage;

        $vomekas_url = $vconfig->config["omekas_url"];
       // $api_url = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0" . $vper_page;
        //$api_url_info = $vomekas_url . "infos?resource_class_id[]=23&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0";

        $api_url = $vomekas_url . "items?resource_class_id[]=23&has_tags=0".$search_text.$search_cates.$sort_by_txt.$sort_order_txt.$media_types_txt . $vper_page;
        $api_url_info = $vomekas_url . "infos?resource_class_id[]=23&has_tags=0".$search_text.$search_cates.$media_types_txt;

        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?resource_class_id[]=23&item_set_id[]=10&item_set_id[]=12&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

//***1.เรียงจาก title ตัวอักษร ก-ฮ
       // http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?resource_class_id[]=23
        //&item_set_id[]=10&item_set_id[]=12&item_set_id[]=13
        //&sort_by=title&sort_order=asc
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

        //***2.เรียงจาก title ตัวอักษร ฮ-ก
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?resource_class_id[0]=23
        //&item_set_id[0]=10&item_set_id[1]=12&item_set_id[2]=13
        //&sort_by=title&sort_order=desc
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&has_media=&has_original=&has_thumbnails=&has_tags=0&page=1

        //***3.เรียงจาก date น้อย ไป มาก asc
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id[]=23
        //&item_set_id[]=10&item_set_id[]=12&item_set_id[]=13
        //&sort_by=date&sort_order=asc
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

        //***4.เรียงจาก date มาก ไป น้อย desc
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id[]=23
        //&item_set_id[]=10&item_set_id[]=12&item_set_id[]=13
        //&sort_by=date&sort_order=desc
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

        //***5.มีคำค้น=ประเวศ   เรียงจาก date มาก ไป น้อย desc และ Media
        // //Class Collection Item set สิ่งแวดล้อม สุขภาพ การศึกษา Media types application/pdf image/jpeg application/pdf image/jpeg
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?
        //fulltext_search=ประเวศ
        //&resource_class_id[]=23&item_set_id[]=10&item_set_id[]=12&item_set_id[]=13
        //&sort_by=created&sort_order=desc
        //&media_types[]=application/pdf&media_types[]=image/jpeg
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/admin/item?fulltext_search=ประเวศ&resource_class_id[]=23&item_set_id[]=10&item_set_id[]=12&item_set_id[]=13&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=
        //&media_types[]=application/pdf&media_types[]=image/jpeg
        //&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_media=&has_original=&has_thumbnails=&has_tags=0

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
                        $thumbnail = $thumbnail_url;
                    }
                }



                //cat Collection

                //cate

               /* $coverageNameArray= array();
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
                           // $dcterms_date=trim($date_set->a_value);
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
