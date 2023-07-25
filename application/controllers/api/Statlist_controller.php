<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Statlist_controller extends REST_Controller
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
        ////นับจำนวนไฟล์เอกสารที่อ่านอย่างเดียว .pdf

        $api_url1 = $vomekas_url . "infos/media?media_types[]=application/msword&media_types[]=application/pdf&media_types[]=application/rtf&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=1&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&has_original=&has_thumbnails=&has_tags=0";

        $json_objekat1 = cal_curl_api($api_url1);
        $documents=0;
        if(!empty($json_objekat1)) {

            $documents=$json_objekat1->total;
        }


        //นับจำนวนไฟล์ภาพ .jpeg + .png +
        $api_url2 = $vomekas_url . "infos/media?media_types[]=image/jpeg&media_types[]=image/png&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_original=&has_thumbnails=&has_tags=0";
        $json_objekat2 = cal_curl_api($api_url2);
        $photos=0;
        if(!empty($json_objekat2)) {
            $photos=$json_objekat2->total;
        }

        //นับจำนวนไฟล์เสียง audio/mpeg audio/mpeg
        $api_url3 = $vomekas_url . "infos/media?media_types[]=audio/mpeg&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_original=&has_thumbnails=&has_tags=0";
        $json_objekat3 = cal_curl_api($api_url3);
        $sounds=0;
        if(!empty($json_objekat3)) {
                $sounds=$json_objekat3->total;
        }

        //นับจำนวนไฟล์ภาพเคลื่อนไหว
        //.mp4 + .mov + …
        $api_url4 = $vomekas_url . "infos/media?media_types[]=video/mp4&sort_by=created&sort_order=desc&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&datetime[0][joiner]=and&datetime[0][field]=created&datetime[0][type]=gte&datetime[0][value]=&is_public=&has_original=&has_thumbnails=&has_tags=0";

        $json_objekat4 = cal_curl_api($api_url4);
        $videos=0;
        if(!empty($json_objekat4)) {
            $videos=$json_objekat4->total;
        }


        $data=array(
            "documents"=>$documents,
            "photos"=>$photos,
            "sounds"=>$sounds,
            "videos"=>$videos,
        );
      //  array_push($dataArray, $data);



       // $this->response($dataArray, 200);
           $this->response($data, 200);
        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/
        // แสดงรายการข่าวทั้งหมด

    }

}
