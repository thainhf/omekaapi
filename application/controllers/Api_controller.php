<?php defined('BASEPATH') OR exit('No direct script access allowed');
//require(APPPATH.'libraries/RESTController.php');
//require APPPATH . 'libraries/RestController.php';
require APPPATH.'libraries/REST_Controller.php';
//use chriskacerguis\RestServer\RestController;
class Api_controller extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
     //   $this->load->model('StudentModel');
    }

    public function index_get()
    {
        $vconfig=$this->config;
        $vomekas_url= $vconfig->config["omekas_url"];

        $data = array(
            array(
                "id"=>1,
                "topic"=>"เนื้อหาคัดสรร (ปี crty = 2023 , เดือน ไตรมาส เช่น เมษายน,พฤษภาคม,กรกฎาคม , crtm=04,05,06  , จำนวนรายการต่อหน้า limitpermonth=10) [crty=yyyy,crtm=mm,limitpermonth=n]",
              //  "url"=>base_url()."api/monthlylist?crty=2023&crtm=04&limitpermonth=10"
                "url"=>base_url()."api/monthlylist?crty=2023&crtm=04,05,06&limitpermonth=10"
            ),
            array(
                "id"=>2,
                "topic"=>"เนื้อหายอดนิยม 5 รายการ hits สูงสุด toplimit [n]",
                "url"=>base_url()."api/popularlist?toplimit=10"
            ),
            array(
                "id"=>3,
                "topic"=>"หารายการ ของปี ย้อนสำรวจความทรงจำสำคัญ ที่เคยเกิดขึ้น เมื่อ 5 ปีที่แล้ว ( 2023-5 = 2018 )-> skipy=5, / 10 รายการ -> limitperpage=10 ต่อหน้า [skipy=5,  limitperpage=10]",
               // "url"=>base_url()."api/flashbacklist?skipy=20&skipm=04&limitperpage=10"
                 "url"=>base_url()."api/flashbacklist?skipy=5&limitperpage=10"
            ),
            array(
                "id"=>4,
                "topic"=>"ชวนออกสำรวจ ตามหัวเรื่อง เลือกสุ่มจำนวนหัวเรื่อง/ limit=20 จำนวนรายการ [limit=20]",
                "url"=>base_url()."api/letsgolist?limit=20"
            ),
            array(
                "id"=>5,
                "topic"=>"เนื้อหารายละเอียด รายการ / id=2114  [id=2114]",
                "url"=>base_url()."api/item?id=2114"
            ),
            array(
                "id"=>6,
                "topic"=>"คลังเอกสารอิเล็กทรอนิกส์ที่เกี่ยวข้อง / id=1966  [id=1966,limit=8]",
                "url"=>base_url()."api/relations?id=1966&limit=8"
            ),
            array(
                "id"=>7,
                "topic"=>"คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  [limitperpage=10]",
                "url"=>base_url()."api/archivelist?page=1&limitperpage=10"
            ),
            array(
                "id"=>8,
                "topic"=>"หมวดหมู่ คลังเอกสารอิเล็กทรอนิกส์ ",
                "url"=>base_url()."api/archivegroup"
            ),
            array(
                "id"=>9,
                "topic"=>"คลังเอกสารอิเล็กทรอนิกส์ / page=หน้าที่ , limitperpage=จำนวนรายการต่อหน้า  ([limitperpage=10],[cates=10,12,17 หรือ 0 ทั้งหมด] ,search=คำค้น,sort_by=title หรือ date ,sort_order=desc หรือ asc, [media_types= book,image,vdo,sound,doc หรือ 0  ทั้งหมด] )",
                "url"=>base_url()."api/archivelistfilter?page=1&limitperpage=10&cates=10,12,17,18&search=ประเวศ&sort_by=title&sort_order=asc&media_types=0"
            ),
            array(
                "id"=>10,
                "topic"=>"ลองสำรวจจากชนิดของสื่อที่หลากหลาย explore-by-media ",
                "url"=>base_url()."api/statlist"
            ),
            array(
                "id"=>11,
                "topic"=>"เนื้อหาโดดเด่นที่คัดสรรจากทีมงาน ในเดือน staff-picks revm=4 จำนวนเดือนย้อนหลัง จากเดือนปัจจุบัน , limitpermonth=8  หรือ ถ้า 0 คือทั้งหมด",
                "url"=>base_url()."api/staffpicks?revm=4&limitpermonth=8"
            ),
            array(
                "id"=>12,
                "topic"=>"รวมเนื้อหายอดนิยม  รายปี",
                "url"=>base_url()."api/popularall?revm=3&toplimit=0"
            ),
            array(
                "id"=>13,
                "topic"=>"**A แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image] ",
                "url"=>base_url()."api/archivegroupfilter?class=Collection,Dataset,Event,Image"
            ),
            array(
                "id"=>14,
                "topic"=>"**B แสดงรายการ property  ",
                "url"=>base_url()."api/archiveproperties"
            ),
            array(
                "id"=>15,
                "topic"=>"**แสดงรายการ คอลเลกชั่น และ [ Class =  Collection,Dataset,Event,Image],[search=สสส] ,[Collection=10,12,13,14  ex. 10=สิ่งแวดล้อม,12=สุขภาพ,13=การศึกษา ดูจาก id=13 **B แสดงรายการ คอลเลกชั่น] , [property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents  ดูจาก id=14 **B แสดงรายการ property]",
                "url"=>base_url()."api/archivelistall?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100"
            ),
            array(
                "id"=>1,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=1&limitperpage=200"
            ),
            array(
                "id"=>2,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=2&limitperpage=200"
            ),
            array(
                "id"=>3,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=3&limitperpage=200"
            ),
            array(
                "id"=>4,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=4&limitperpage=200"
            ),
            array(
                "id"=>5,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=5&limitperpage=200"
            ),
            array(
                "id"=>6,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=6&limitperpage=200"
            ),
            array(
                "id"=>7,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200]",
                "url"=>base_url()."api/archivelistallpage?page=7&limitperpage=200"
            ),
            array(
                "id"=>8,
                "topic"=>"**แสดงรายการ [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=200] ...",
                "url"=>base_url()."api/archivelistallpage?page=8&limitperpage=200"
            ),
            array(
                "id"=>9,
                "topic"=>"**แสดงรายการ Tags [หน้า-> page=1, จำนวนรายการต่อหน้า -> limitperpage=2000]",
                "url"=>base_url()."api/taglistpage?page=1&limitperpage=2000"
            ),
            array(
                "id"=>10,
                "topic"=>"**แสดงรายการ ข้อมูล media หาจาก ID [id ที่ต้องการหา-> id=2194,5865]",
                "url"=>base_url()."api/archivelistallpagebyid?id=2194,5865"
            ),
            array(
                "id"=>99,
                "topic"=>"api omeka",
                //  "url"=>base_url()."api/monthlylist?crty=2023&crtm=04&limitpermonth=10"
                "url"=>$vomekas_url
            ),
            //$route['api/relations']['GET'] = 'api/relations_controller';

        );
      //  $this->response($data, 200);

        $this->response(array(
            "status" => 1,
            "message" => "ok",
            "data" => $data
        ), REST_Controller::HTTP_OK);

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
