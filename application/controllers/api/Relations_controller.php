<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Relations_controller extends REST_Controller
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
        $vomekas_url= $vconfig->config["omekas_url"];

        $limit = $this->input->get('limit', true);
        if(!empty($limit)){
            $vlimit=$limit;
        }else{
            $vlimit=8;
        }

        $id = $this->input->get('id', true);
        if(!empty($id)){
            $vid=$id;
        }else{
            $vid=0;
        }
        $api_url=$vomekas_url."items/".$vid;
        $json_objekat    = cal_curl_api($api_url);

//        echo "<pre>";
//        print_r($json_objekat);
//        echo "</pre>";

        //link cat
        $creatorArray= array();
        if(!empty($json_objekat)) {
            if(empty( $json_objekat->errors)){
                if(isset($json_objekat->o_item_set)){
                    $o_item_set_arr=$json_objekat->o_item_set;
                    if(!empty($o_item_set_arr)){
                        foreach ($o_item_set_arr as $item) {
                            array_push($creatorArray, trim($item->o_id));
                        }
                    }
                }
            }
        }


         $link_cat="";

        if(!empty($creatorArray)){
            foreach ($creatorArray as $item_cat) {
               // echo $item_cat."<br>";
                $link_cat=$link_cat."&item_set_id[]=".$item_cat;
            }
        }

        // &resource_template_id[]=2 =>Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี //Date Issued contains 2023-04 OR Date Issued contains 2023-05 Template ชุดเมทาดาทาศาสตราจารย์นายแพทย์ประเวศ วะสี
        $resource_template="&resource_template_id[]=2";

        $api_url_cat = $vomekas_url . "items?resource_class_id[]=23&".$link_cat."&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0&page=1&per_page=100".$resource_template;

      //  echo  $api_url_cat;

        $api_url_count = $vomekas_url."infos?resource_class_id[]=23&".$link_cat."&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&has_tags=0";
        $json_objcount = cal_curl_api($api_url_count);
//            echo "<pre>";
//            print_r($json_objcount->items->total);
//            echo "</pre>";

        $vcount=$json_objcount->items->total;
        if($vlimit>=$vcount){
            $vlimit = $vcount;
        }

      //  echo  $api_url_count;


        $json_objekat3 = cal_curl_api($api_url_cat);

        if(!empty($json_objekat3)){

            $rand_keys = array_rand($json_objekat3, $vlimit);
         //   $rand_keys = array_rand($json_objekat3, $vcount);

            foreach ($rand_keys as $key) {
             //   echo "key=".$key;
//                echo "<pre>";
//                print_r($json_objekat3[$key]);
//                echo "</pre>";

                $arr_objekat_item= $json_objekat3[$key];


                if(!empty($arr_objekat_item)) {
                    if (empty($arr_objekat_item->errors)) {

                        $title=$arr_objekat_item->o_title;
                        $o_id=$arr_objekat_item->o_id;
                        $o_thumbnail=$arr_objekat_item->thumbnail_display_urls;
                        $thumbnail="";
                        //part รูป
                        if(isset($arr_objekat_item->thumbnail_display_urls)){
                            $thumbnail_arr=$arr_objekat_item->thumbnail_display_urls;
                            if(!empty($thumbnail_arr)){
                                $thumbnail_url=$thumbnail_arr->large;
                                $thumbnail = $thumbnail_url;
                            }
                        }

                        //ผู้สร้าง/เจ้าของผลงาน
                        $dcterms_creator="";
                        if(isset($arr_objekat_item->dcterms_creator)){
                            $dcterms_creator_arr=$arr_objekat_item->dcterms_creator;
                            if(!empty($dcterms_creator_arr)){
                                foreach ($dcterms_creator_arr as $date_set) {
                                    $dcterms_creator=trim($date_set->a_value);
                                    //array_push($dcterms_dateArray, $vdcterms_date);
                                }
                            }
                        }

                        //cat Collection

                        $catesArray= array();
                        $catesNameArray= array();

//                 echo "<pre>===========";
//                print_r($arr_objekat_item);
//                echo "</pre>";


                        if(isset($arr_objekat_item->o_item_set)){
                            $item_set_arr=$arr_objekat_item->o_item_set;
                            if(!empty($item_set_arr)){
                                foreach ($item_set_arr as $item_set) {
                                    $vid_set=$item_set->o_id;
                                    array_push($catesArray, $vid_set);
                                }
                            }
                        }


                                              if(isset($arr_objekat_item->dcterms_coverage)){
                                                  $dcterms_coverage_arr=$arr_objekat_item->dcterms_coverage;
                                                  if(!empty($dcterms_coverage_arr)){
                                                      foreach ($dcterms_coverage_arr as $item_coverage) {
                                                          array_push($catesNameArray, trim($item_coverage->a_value));
                                                      }
                                                  }
                                              }


                       /* $catesNameArray= array();
                        if(isset($arr_objekat_item->dcterms_coverage)){
                            $dcterms_coverage_arr=$arr_objekat_item->dcterms_coverage;
                            if(!empty($dcterms_coverage_arr)){
                                foreach ($dcterms_coverage_arr as $item_coverage) {
                                    array_push($catesNameArray, trim($item_coverage->a_value));
                                }
                            }
                        }*/

                        $dateArray= array();
                        if(isset($arr_objekat_item->dcterms_date)){
                            $dcterms_date_arr=$arr_objekat_item->dcterms_date;
                            if(!empty($dcterms_date_arr)){
                                foreach ($dcterms_date_arr as $item) {
                                    array_push($dateArray, trim($item->a_value));
                                }
                            }
                        }

                        $createdArray= array();
                        if(isset($arr_objekat_item->dcterms_created)){
                            $dcterms_date_arr=$arr_objekat_item->dcterms_created;
                            if(!empty($dcterms_date_arr)){
                                foreach ($dcterms_date_arr as $item) {
                                    array_push($createdArray, trim($item->a_value));
                                }
                            }
                        }



                        $data=array(
                            "id"=>$o_id,
                            "thumbnail"=>$thumbnail,
                            "cates"=>$catesArray,
                            "catenames"=>$catesNameArray,
                            "date"=>$dateArray,
                            "title"=>$title,
                            "creator"=>$dcterms_creator,
                            "created"=>$createdArray,
                        );
                        array_push($dataArray, $data);

                    }
                }
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
