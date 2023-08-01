<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/REST_Controller.php';
class Taglistpage_controller extends REST_Controller
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


        $page = $this->input->get('page', true);
        if(!empty($page)){
            $vpage=$page;
        }else{
            $vpage=1;
        }


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
            $vlimitperpage=2000;
        }

        //$vper_page=$per_page;
        $vper_page = "page=".$vpage."&per_page=" . $vlimitperpage;



        $api_url_all = $vomekas_url . "tags?" . $vper_page;

        ////รายการทั้งหมด
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/items?sort_by=created&sort_order=desc&page=1&per_page=100
        //http://ec2-18-139-29-204.ap-southeast-1.compute.amazonaws.com/api/infos?sort_by=created&sort_order=desc


        $json_obj_all = cal_curl_api($api_url_all);



        if(!empty($json_obj_all)) {
            $i=0;
            $start=($vpage-1)*$vlimitperpage;
            foreach ($json_obj_all as $objQuote) {

               // $vid=$objQuote->o_id;

            //    $api_url_item=$vomekas_url."items/".$vid;
               // array_push($dataArray, $objQuote->o_id);
                $start=$start+1;
                $t_txt=$start;

                $data=array(
                    $t_txt=>$objQuote->o_id,
                );

                array_push($dataArray, $data);

            }
        }

        $dataAll=array(
            "data"=>$dataArray,
            "page"=>$vpage,
            "page_size"=>$vlimitperpage,
          //  "total"=>$total,
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
