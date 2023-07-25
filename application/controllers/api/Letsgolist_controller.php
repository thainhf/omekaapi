<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Letsgolist_controller extends REST_Controller
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
        $limit = $this->input->get('limit', true);
        if(!empty($limit)){
            $vlimit=$limit;
        }else{
            $vlimit=10;
        }


        //$vper_page=$per_page;
       // $vper_page = "&page=1&per_page=" . $vlimit;
        $vper_page = "page=1&per_page=" . $vlimit;
        if($vlimit > 100){
            $vlimit2=$vlimit;
        }else{
            $vlimit2=100;
        }
       // $p_n=$vskipy; //เลข ปี ย้อนหลัง
      //  $p_mm=$vskipm;
        $curYear = date('Y'); //คศ.
      //  $p_yyyy=$curYear-$p_n;

      //  echo $skipy;

      //  if($p_mm == "00"){
       //     $YYYY_MM=$p_yyyy; //2023-04
      //  }else{
       //     $YYYY_MM=$p_yyyy."-".$p_mm; //2023-04
      //  }

//http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?per_page=100
        //รายการทั้งหมดใน collection
     //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?resource_class_id[]=23&sort_by=created&sort_order=desc&per_page=1000
        $vomekas_url = $vconfig->config["omekas_url"];
        $api_url = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc" . $vper_page;
        $vper_page2 = "page=1&per_page=".$vlimit2;
        $api_url2 = $vomekas_url . "items?resource_class_id[]=23&sort_by=created&sort_order=desc" . $vper_page2;
        $api_url1 = $vomekas_url . "items?resource_class_id[]=23&id=";
      //  echo $api_url2;



        $random_numbers = [];
      //  $random_numbers2 = [];
        $random_number2 = 0;
        while(count($random_numbers) < $vlimit){

            do  {
                $random_number = mt_rand(200,3000);
                $api_url3=$api_url1.$random_number;
                //http://localhost/omeka-s323/api/items?resource_class_id[]=23&id=127
               // echo $api_url3.'<br><br>';
                $json_objekat3 = cal_curl_api($api_url3);
                if(!empty($json_objekat3)){
                    //ที่ไม่มี error หรือ มีข้อมูล
                  //  if(empty( $json_objekat3->errors)){
                       // echo "<pre>";
                       // print_r($json_objekat3);
                       // echo "</pre>";
                      //  $random_numbers2[] = $random_number;

                        $title=$json_objekat3[0]->o_title;
                        $o_id=$json_objekat3[0]->o_id;

                    $catesNameArray= array();
                    if(isset($json_objekat3[0]->dcterms_coverage)){
                        $dcterms_coverage_arr=$json_objekat3[0]->dcterms_coverage;
                        if(!empty($dcterms_coverage_arr)){
                            foreach ($dcterms_coverage_arr as $item_coverage) {
                                array_push($catesNameArray, trim($item_coverage->a_value));
                            }
                        }
                    }

                        $data=array(
                            "id"=>$o_id,
                           "title"=>$title,
                            "catenames"=>$catesNameArray,
                        );
                        array_push($dataArray, $data);
                        $random_number2 = $random_number;
                  //  }
                }

           // } while (in_array($random_number, $random_numbers));
            } while (in_array($random_number2, $random_numbers));
           // $random_numbers[] = $random_number;
            if($random_number2 != 0){
                $random_numbers[] = $random_number2;
            }

        }

//        echo "<pre>";
//        print_r($random_numbers);
//        echo "</pre>";

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
