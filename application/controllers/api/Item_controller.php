<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Item_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $dataArray= array();
        $vconfig=$this->config;
        $vomekas_url= $vconfig->config["omekas_url"];



        $id = $this->input->get('id', true);
        if(!empty($id)){
            $vid=$id;
        }else{
            $vid=0;
        }
        $api_url=$vomekas_url."items/".$vid;

      //  echo $vomekas_url;
       // print_r($vconfig->config["omekas_url"]);
      //  echo "</pre>";

        $json_objekat    = cal_curl_api($api_url);
//        echo "<pre>999=";
//        print_r($json_objekat);
//        echo "</pre>";
//        exit();

        if(!empty($json_objekat)) {

            if(empty( $json_objekat->errors)){

                //หาไฟล์ media
                $mediaArray= array();
                $count_mm=0;
                if(isset($json_objekat->o_media)){
                    $media_arr=$json_objekat->o_media;


                    if(!empty($media_arr)){

//                          echo "<pre>999=";
//                          print_r($media_arr);
//                           echo "</pre>";
//                        echo count($media_arr);
                        $count_mm=count($media_arr);

                       /* foreach ($media_arr as $item) {
                            //  array_push($mediaArray, trim($item->o_id));

                            $api_url2=$vomekas_url."media/".$item->o_id;
                            $json_objekat2    = cal_curl_api($api_url2);
                            if(!empty($json_objekat2)) {

                                // if(!empty($dcterms_rights_arr)){
                                $original_url=$json_objekat2->o_original_url;
                                $media_type=$json_objekat2->o_media_type;
                                $data_media=array(
                                    "source"=>$original_url,
                                    "type"=>$media_type,
                                );
                                array_push($mediaArray, $data_media);
                                //foreach ($dcterms_rights_arr as $item) {
                                //    array_push($rightsArray, trim($item->a_value));
                                // }
                                // }
                            }

                        }*/
                    }
                }



                $creatorArray= array();
                if(isset($json_objekat->dcterms_creator)){
                    $dcterms_creator_arr=$json_objekat->dcterms_creator;
                    if(!empty($dcterms_creator_arr)){
                        foreach ($dcterms_creator_arr as $item) {
                            array_push($creatorArray, trim($item->a_value));
                        }
                    }
                }

//cate

               /* $coverageNameArray= array();
                if(isset($json_objekat->o_item_set)){
                    $dcterms_coverage_arr=$json_objekat->o_item_set;
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
                if(isset($json_objekat->o_item_set)){
                    $item_set_arr=$json_objekat->o_item_set;
                    if(!empty($item_set_arr)){
                        foreach ($item_set_arr as $item_set) {
                            $vid_set=$item_set->o_id;
                            array_push($catesArray, $vid_set);
                        }
                    }
                }


                if(isset($json_objekat->dcterms_coverage)){
                    $dcterms_coverage_arr=$json_objekat->dcterms_coverage;
                    if(!empty($dcterms_coverage_arr)){
                        foreach ($dcterms_coverage_arr as $item_coverage) {
                            array_push($catesNameArray, trim($item_coverage->a_value));
                        }
                    }
                }


                $subjectNameArray= array();
                if(isset($json_objekat->dcterms_subject)){
                    $dcterms_subject_arr=$json_objekat->dcterms_subject;
                    if(!empty($dcterms_subject_arr)){
                        foreach ($dcterms_subject_arr as $item) {
                            array_push($subjectNameArray, trim($item->a_value));
                        }
                    }
                }

                $descriptionNameArray= array();
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


                $abstractNameArray= array();
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

                $dateArray= array();
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
                }

                //thumbnail
                $thumbnail="";
                if(isset($json_objekat->thumbnail_display_urls)){
                    $thumbnail_display_urls=$json_objekat->thumbnail_display_urls;
                    $thumbnail=$thumbnail_display_urls->large;
                }

                //created
                $createdArray= array();
                $created= $vconfig->config["nd"];
                if(isset($json_objekat->dcterms_created)){
                    $created_arr=$json_objekat->dcterms_created;
                    if(!empty($created_arr)){
                        foreach ($created_arr as $item) {
                           // array_push($createdArray, trim($item->a_value));
                           // $created=trim($item->a_value);
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
                            // $created=trim($item->a_value);
                           // $vdate = trim($item->a_value);

                        }
                    }
                }



                //ถ้ามีไฟล์หลายไฟล์ สร้างเป็น หลาย รายการ
                if($count_mm > 1){

                    foreach ($media_arr as $item) {
                        $api_url2=$vomekas_url."media/".$item->o_id;
                        $json_objekat2    = cal_curl_api($api_url2);
//                          echo "<pre>media=";
//                          print_r($json_objekat2);
//                           echo "</pre>";
                      //  $data_media=array();

                        $source="";
                        $original_url="";
                        $media_type="";
                        $filename="";
                        $id_media=$item->o_id;
                        if(!empty($json_objekat2)) {



                            // if(!empty($dcterms_rights_arr)){
                            $original_url=$json_objekat2->o_original_url;
                            $media_type=$json_objekat2->o_media_type;
                            $source=$json_objekat2->o_source;
                            $filename=$json_objekat2->o_filename;

                            /*$data_media=array(
                                "source"=>$original_url,
                                "type"=>$media_type,
                            );*/


                          //  array_push($mediaArray, $data_media);

                        }

                        $data=array(
                            "id"=>$json_objekat->o_id,
                            "title"=>$json_objekat->o_title,
                            //   "coverage"=>$coverageNameArray,
                            //"cates"=>$catesArray,
                           // "catenames"=>$catesNameArray,
                            "cates"=>$catesNameArray,
                            "subject"=>$subjectNameArray,
                            "creator"=>$creatorArray,
                            //  "detail"=>$descriptionNameArray,
                            "detail"=>$description,

                            //   "abstract"=>$abstractNameArray,
                            "abstract"=>$abstract,
                            //  "date"=>$dateArray,
                            "date"=>$dcterms_creator,
                            "type"=>$typeArray,
                            //"media"=>$mediaArray,
                           // "media"=>$data_media,
                            "source"=>$source,
                            "id_media"=>$id_media,
                            "media_type"=>$media_type,
                            "filename"=>$filename,
                            "original_url"=>$original_url,

                            "thumbnail"=>$thumbnail,
                            //"created"=>$createdArray,
                            "created"=>$created,
                            "rights"=>$rightsArray,
                            "coverage"=>$dcterms_coverageArray,
                        );
                        array_push($dataArray, $data);

                    }


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
                        //   "coverage"=>$coverageNameArray,
                        //"cates"=>$catesArray,
                        // "catenames"=>$catesNameArray,
                        "cates"=>$catesNameArray,
                        "subject"=>$subjectNameArray,
                        "creator"=>$creatorArray,
                        //  "detail"=>$descriptionNameArray,
                        "detail"=>$description,

                        //   "abstract"=>$abstractNameArray,
                        "abstract"=>$abstract,
                        //  "date"=>$dateArray,
                        "date"=>$dcterms_creator,
                        "type"=>$typeArray,
                        //"media"=>$mediaArray,
                        // "media"=>$data_media,
                        "source"=>$source,
                        "id_media"=>$id_media,
                        "media_type"=>$media_type,
                        "filename"=>$filename,
                        "original_url"=>$original_url,

                        "thumbnail"=>$thumbnail,
                        //"created"=>$createdArray,
                        "created"=>$created,
                        "rights"=>$rightsArray,
                        "coverage"=>$dcterms_coverageArray,
                    );
                    array_push($dataArray, $data);

//                    $source="";
//                    $original_url="";
//                    $media_type="";
//                    $filename="";
//                    $id_media=$item->o_id;
//                    if(!empty($json_objekat2)) {
//                        $original_url=$json_objekat2->o_original_url;
//                        $media_type=$json_objekat2->o_media_type;
//                        $source=$json_objekat2->o_source;
//                        $filename=$json_objekat2->o_filename;
//                    }
//                    $data=array(
//                        "id"=>$json_objekat->o_id,
//                        "title"=>$json_objekat->o_title,
//                        //   "coverage"=>$coverageNameArray,
//                       // "cates"=>$catesArray,
//                        "cates"=>$catesNameArray,
//                        "subject"=>$subjectNameArray,
//                        "creator"=>$creatorArray,
//                        //  "detail"=>$descriptionNameArray,
//                        "detail"=>$description,
//
//                        //   "abstract"=>$abstractNameArray,
//                        "abstract"=>$abstract,
//                        //  "date"=>$dateArray,
//                        "date"=>$dcterms_creator,
//                        "type"=>$typeArray,
//                        "media"=>$mediaArray,
//                        "thumbnail"=>$thumbnail,
//                        //"created"=>$createdArray,
//                        "created"=>$created,
//                        "rights"=>$rightsArray,
//                    );
//                    array_push($dataArray, $data);

                }



                $id_link=$json_objekat->o_id;
            }

        }


        $this->response($dataArray, 200);
    //====สำหรับ เรียก link รายการเพื่อให้นับ hits=====
       // $api_url22="http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/s/Prawase/item/".$id_link;
        $vomekas_url_item= $vconfig->config["omekas_url_item"];
        $api_url22=$vomekas_url_item.$id_link;
        $json_objekat2    = cal_curl_api($api_url22);
        //========================


     //   $this->response($dataAll, 200);
     /*   $this->response(array(
            "status" => 1,
            "message" => "Students found99999",
            "data" => "9999"
        ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด
    }


}
