<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class News_controller extends REST_Controller
{
    public function index_get()
    {
        $data = array(
            array(
                "id"=>1,
                "topic"=>"หัวข้อข่าวที่ 1"
            ),
            array(
                "id"=>2,
                "topic"=>"หัวข้อข่าวที่ 2"
            ),
            array(
                "id"=>3,
                "topic"=>"หัวข้อข่าวที่ 3222"
            ),
        );
        $this->response($data, 200);

     /*   $this->response(array(
            "status" => 1,
            "message" => "Students found99999",
            "data" => "9999"
        ), REST_Controller::HTTP_OK);*/

        // แสดงรายการข่าวทั้งหมด
    }

    public function monthlylist()
    {
        $data = array(
            array(
                "id"=>1,
                "topic"=>"หัวข้อข่าวที่ 1 999"
            ),
            array(
                "id"=>2,
                "topic"=>"หัวข้อข่าวที่ 2"
            ),
            array(
                "id"=>3,
                "topic"=>"หัวข้อข่าวที่ 3"
            ),
        );
        $this->response($data, 200);

        /*   $this->response(array(
               "status" => 1,
               "message" => "Students found99999",
               "data" => "9999"
           ), REST_Controller::HTTP_OK);*/

        // แสดงรายการข่าวทั้งหมด
    }

}
/*
class News_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

}*/
