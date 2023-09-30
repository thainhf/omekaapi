<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Archivelistallpagebyid_controller extends REST_Controller
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
        $vomekas_url = $vconfig->config["omekas_url"];


        $get_id = $this->input->get('id', true);
        if(!empty($get_id)){
            $vid=$get_id;
        }else{
            $vid=0;
        }

        if($vid==0) {
          //  echo $vid;
            $data=array(
                "id"=>0,
                "title"=>"NO data",
            );
            array_push($dataArray, $data);

            $dataAll = array(
                "data" => $dataArray,
            );
            $this->response($dataAll, 200);
           // echo "999";
            exit();
        }

        //2194,5865

        $id_arr = explode(',', $vid);
        //               echo "<pre>999=";
        //            print_r($id_arr);
        //            echo "</pre>";
        //   exit();

       // if (count($id_arr) == 1) {
           // $id_arr[]
      //  } else {
           // $id_arr
      //  }

        //หาจำนวน รายการทั้งหมด // ส่วนที่เป็นข้อมูล Visibility=Public
       /* $api_url_info = $vomekas_url . "infos";
        $json_objekat_info = cal_curl_api($api_url_info);
        $total=0;
        if(!empty($json_objekat_info)){
            if(isset($json_objekat_info->items)){
                $itemsall_arr=$json_objekat_info->items;
                if(!empty($itemsall_arr)){
                    $total=$itemsall_arr->total;
                }
            }
        }*/

      //  $limitperpage = $this->input->get('limitperpage', true);

      //  if(!empty($limitperpage)){
       //     $vlimitperpage=$limitperpage;
      //  }else{
       //     $vlimitperpage=$total;
      //  }

        //$vper_page=$per_page;
      //  $vper_page = "&id=".$vid."&per_page=" . $vlimitperpage;



      //  $api_url_all = $vomekas_url . "items/" . $id_arr[];
      //  $api_url_info = $vomekas_url . "infos";
      //  echo $api_url;

        ////รายการทั้งหมด
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?sort_by=created&sort_order=desc&page=1&per_page=100
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/infos?sort_by=created&sort_order=desc
       // https://omeka.p-set.org/api/items?pretty_print=1

      //  $json_obj_all = cal_curl_api($api_url_all);

      //  if(!empty($json_obj_all)) {
      //      foreach ($json_obj_all as $objQuote) {
        if (count($id_arr) != 0) {
            foreach ($id_arr as $o_id) {
               // $vid=$objQuote;
                $vid=$o_id;
                $api_url_item=$vomekas_url."items/".$vid;

                $json_objekat    = cal_curl_api($api_url_item);
//                echo "<pre>999=".$api_url_item;
//                    print_r($json_objekat);
//                    echo "</pre>";
             //   exit();
                if(!empty($json_objekat)) {
                    if(empty( $json_objekat->errors)){

                        $creatorArray= array();
                        if(isset($json_objekat->dcterms_creator)){
                            $dcterms_creator_arr=$json_objekat->dcterms_creator;
                            if(!empty($dcterms_creator_arr)){
                                foreach ($dcterms_creator_arr as $item) {
                                    array_push($creatorArray, trim($item->a_value));
                                }
                            }
                        }

                        $catesNameArray= array();
                        if(isset($json_objekat->dcterms_coverage)){
                            $dcterms_coverage_arr=$json_objekat->dcterms_coverage;
                            if(!empty($dcterms_coverage_arr)){
                                foreach ($dcterms_coverage_arr as $item_coverage) {
                                    array_push($catesNameArray, trim($item_coverage->a_value));
                                }
                            }
                        }else{
                            array_push($catesNameArray, $vconfig->config["nd"]);
                        }

                        $subjectNameArray= array();
                        if(isset($json_objekat->dcterms_subject)){
                            $dcterms_subject_arr=$json_objekat->dcterms_subject;
                            if(!empty($dcterms_subject_arr)){
                                foreach ($dcterms_subject_arr as $item) {
                                    array_push($subjectNameArray, trim($item->a_value));
                                }
                            }
                        }else{
                            array_push($subjectNameArray, $vconfig->config["nd"]);
                        }

                        $description=  $vconfig->config["nd"];
                        if(isset($json_objekat->dcterms_description)){
                            $dcterms_description_arr=$json_objekat->dcterms_description;
                            if(!empty($dcterms_description_arr)){
                                foreach ($dcterms_description_arr as $item) {
                                    // array_push($descriptionNameArray, trim($item->a_value));
                                    $description= trim($item->a_value);
                                }
                            }
                        }

                        $abstract= $vconfig->config["nd"];
                        if(isset($json_objekat->dcterms_abstract)){
                            $dcterms_abstract_arr=$json_objekat->dcterms_abstract;
                            if(!empty($dcterms_abstract_arr)){
                                foreach ($dcterms_abstract_arr as $item) {
                                    //  array_push($abstractNameArray, trim($item->a_value));
                                    $abstract=trim($item->a_value);
                                }
                            }
                        }

                        $dcterms_creator= $vconfig->config["nd"];
                        if(isset($json_objekat->dcterms_date)){
                            $dcterms_date_arr=$json_objekat->dcterms_date;
                            if(!empty($dcterms_date_arr)){
                                foreach ($dcterms_date_arr as $item) {
                                    //   array_push($dateArray, trim($item->a_value));
                                    $vdate = trim($item->a_value);
                                    $dcterms_date=convert_date_yyyy_mm_dd($vdate);
                                    $dcterms_creator=$dcterms_date;
                                }
                            }
                        }

                        //rights
                        $rightsArray= array();
                        if(isset($json_objekat->dcterms_rights)){
                            $dcterms_rights_arr=$json_objekat->dcterms_rights;
                            if(!empty($dcterms_rights_arr)){
                                foreach ($dcterms_rights_arr as $item) {
                                    array_push($rightsArray, trim($item->a_value));
                                }
                            }
                        }else{
                            array_push($rightsArray, $vconfig->config["nd"]);
                        }

                        //ประเภทของ รายการ
                        $typeArray= array();
                        if(isset($json_objekat->dcterms_type)){
                            $type_arr=$json_objekat->dcterms_type;
                            if(!empty($type_arr)){
                                foreach ($type_arr as $item) {
                                    array_push($typeArray, trim($item->a_value));
                                }
                            }
                        }else{
                            array_push($typeArray, $vconfig->config["nd"]);
                        }

                        //thumbnail
                        $thumbnail="";
                        if(isset($json_objekat->thumbnail_display_urls)){
                            $thumbnail_display_urls=$json_objekat->thumbnail_display_urls;
                            $thumbnail=$thumbnail_display_urls->large;
                        }

                        $created= $vconfig->config["nd"];
                        if(isset($json_objekat->dcterms_created)){
                            $created_arr=$json_objekat->dcterms_created;
                            if(!empty($created_arr)){
                                foreach ($created_arr as $item) {
                                    $vdate = trim($item->a_value);
                                    $created_date=convert_date_yyyy_mm_dd($vdate);
                                    $created=$created_date;
                                }
                            }
                        }

                        //dcterms_coverage
                        $dcterms_coverageArray= array();
                        if(isset($json_objekat->dcterms_coverage)){
                            $coverage_arr=$json_objekat->dcterms_coverage;
                            if(!empty($coverage_arr)){
                                foreach ($coverage_arr as $item) {
                                    array_push($dcterms_coverageArray, trim($item->a_value));
                                }
                            }
                        }else{
                            array_push($dcterms_coverageArray, $vconfig->config["nd"]);
                        }

                        //หาไฟล์ media
                        $mediaArray= array();
                        $count_mm=0;
                        if(isset($json_objekat->o_media)){
                            $media_arr=$json_objekat->o_media;
                            if(!empty($media_arr)){
                                $count_mm=count($media_arr);

                                //cates
                                $creatorArray= array();
                                if(isset($json_objekat->dcterms_creator)){
                                    $dcterms_creator_arr=$json_objekat->dcterms_creator;
                                    if(!empty($dcterms_creator_arr)){
                                        foreach ($dcterms_creator_arr as $item) {
                                            array_push($creatorArray, trim($item->a_value));
                                        }
                                    }
                                }else{
                                    array_push($creatorArray, $vconfig->config["nd"]);
                                }

                            }
                        } //end media

                        //tags
                        //https://archives.nrct.go.th/mhesi/api/tags?resource_id=11
                        $tagsArray= array();
                        $tags= $vconfig->config["nd"];
                        if(isset($json_objekat->o_module_folksonomy_tag)){
                            $tag_arr=$json_objekat->o_module_folksonomy_tag;
                            if(!empty($tag_arr)){
                                foreach ($tag_arr as $tag) {
                                    array_push($tagsArray, trim($tag->o_id));
                                }
                            }
                        }else{
                            array_push($tagsArray, $tags);
                        }

                        //ถ้ามีไฟล์หลายไฟล์ สร้างเป็น หลาย รายการ
                        if($count_mm > 1){
                           // $dataArraymm= array();
                            foreach ($media_arr as $item) {
                                $api_url2=$vomekas_url."media/".$item->o_id;
                                $json_objekat2    = cal_curl_api($api_url2);

                                $source="";
                                $original_url="";
                                $media_type="";
                                $filename="";
                                $id_media=$item->o_id;
                                if(!empty($json_objekat2)) {
                                    $original_url=$json_objekat2->o_original_url;
                                    $media_type=$json_objekat2->o_media_type;
                                    $source=$json_objekat2->o_source;
                                    $filename=$json_objekat2->o_filename;
                                }
                                $data=array(
                                    "id"=>$json_objekat->o_id,
                                    "title"=>$json_objekat->o_title,
                                    "cates"=>$catesNameArray,
                                    "subject"=>$subjectNameArray,
                                    "creator"=>$creatorArray,
                                    "detail"=>$description,
                                    "abstract"=>$abstract,
                                    "date"=>$dcterms_creator,
                                    "type"=>$typeArray,
                                    "source"=>$source,
                                    "id_media"=>$id_media,
                                    "media_type"=>$media_type,
                                    "filename"=>$filename,
                                    "original_url"=>$original_url,
                                    "thumbnail"=>$thumbnail,
                                    "created"=>$created,
                                    "rights"=>$rightsArray,
                                    "coverage"=>$dcterms_coverageArray,
                                    "tags"=>$tagsArray,
                                );

                                array_push($dataArray, $data);
                              //  array_push($dataArraymm, $data);
                            }
                           // array_push($dataArray, $dataArraymm);
                        }else{
                            $source = "";
                            $original_url = "";
                            $media_type = "";
                            $filename = "";
                            $id_media = "";
                            foreach ($media_arr as $item) {
                                $api_url2 = $vomekas_url . "media/" . $item->o_id;
                                $json_objekat2 = cal_curl_api($api_url2);

                                $id_media = $item->o_id;
                                if (!empty($json_objekat2)) {

                                    $original_url = $json_objekat2->o_original_url;
                                    $media_type = $json_objekat2->o_media_type;
                                    $source = $json_objekat2->o_source;
                                    $filename = $json_objekat2->o_filename;

                                }
                            }
                            $data=array(
                                "id"=>$json_objekat->o_id,
                                "title"=>$json_objekat->o_title,
                                "cates"=>$catesNameArray,
                                "subject"=>$subjectNameArray,
                                "creator"=>$creatorArray,
                                "detail"=>$description,
                                "abstract"=>$abstract,
                                "date"=>$dcterms_creator,
                                "type"=>$typeArray,
                                "source"=>$source,
                                "id_media"=>$id_media,
                                "media_type"=>$media_type,
                                "filename"=>$filename,
                                "original_url"=>$original_url,
                                "thumbnail"=>$thumbnail,
                                "created"=>$created,
                                "rights"=>$rightsArray,
                                "coverage"=>$dcterms_coverageArray,
                                "tags"=>$tagsArray,
                            );
                            array_push($dataArray, $data);
                        }

                    } //end error
                }



            } //end loop
        } // end if

        $dataAll=array(
            "data"=>$dataArray,
           // "page"=>$vpage,
           // "page_size"=>$vlimitperpage,
           // "total"=>$total,
        );


           $this->response($dataAll, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
