<?php

class Domain_Group {
    public $rs = array(
            'code' => 0,
            'msg'  => '',
            'info' => array(),
            );
    public $msg   = '';
    public $model = '';
    public $cookie = array();
    public $u_status = '0';
    public $g_status = '0';
    public $pages = array();

    public function checkN($g_name){
        $rs = $this->model->checkName($g_name);
        if (!preg_match('/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]{1,20}+$/u', $g_name)) {
            $this->msg = '小组名只能为中文、英文、数字或者下划线，但不得超过20字节！';
        }elseif (!empty($rs)) {
            $this->msg = '该星球已创建！';
        }else{
            $this->g_status = '1';
        }
    }

    public function checkG($g_id){
        $rs = $this->model->checkGroup($this->cookie['userID'], $g_id);
        if (!empty($rs)) {
            $this->msg = '已加入该星球！';
            $this->g_status = '1';
            // return $this->msg;
        }else{
            $this->g_status = '0';
            $this->msg = '未加入该星球！';
        }
    }

    public function checkStatus($user_id){
        // $config = array('crypt' => new Domain_Crypt(), 'key' => 'a secrect');
        // DI()->cookie = new PhalApi_Cookie_Multi($config);
        // $this->cookie['userID']   = DI()->cookie->get('userID');
        // $this->cookie['nickname'] = DI()->cookie->get('nickname');

        $rs['nickname'] = $this->model->getUser($user_id);
        $this->cookie['userID'] = $user_id;
        $this->cookie['nickname'] = $rs['nickname'];

        if (empty($this->cookie['nickname'])) {
            $this->msg = '用户尚未登录！';
            $this->u_status = '1';//为1取消用户登录验证，为0需要验证用户是否登录
            // return $this->msg;
        }else{
            $this->u_status = '1';
            $this->msg = '用户已登录！';
        }

        $rs['nickname'] = $this->model->getUser($user_id);
        $this->cookie['userID'] = $user_id;
        $this->cookie['nickname'] = $rs['nickname'];
    }


    public function create($data) {
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);
        $this->checkN($data['name']);
        if ($this->u_status == '1' && $this->g_status =='1') {
            if(empty($data['g_introduction'])) {
                $data['g_introduction']=NULL;
            }
            if(empty($data["g_image"])){
                $data = array('name' => $data['name'],'g_image'=>'http://7xlx4u.com1.z0.glb.clouddn.com/o_1aqt96pink2kvkhj13111r15tr7.jpg?imageView2/1/w/100/h/100','g_introduction' => $data['g_introduction'],'private'=>$data['private']) ;
            }
            else{
                $data = array('name' => $data['name'],'g_image'=>$data["g_image"],'g_introduction' => $data['g_introduction'],'private'=>$data['private']);
            }
            $result = DI()->notorm->group_base->insert($data);
            $data2 = array(
            'group_base_id' => $result['id'],
            'user_base_id'  => $this->cookie['userID'],
            'authorization'=>"01",
            );
            $result2 = DI()->notorm->group_detail->insert($data2);
            $this->rs['info'] = $data2;
            $this->rs['info']['name'] = $data['name'];
            $this->rs['info']['g_introduction'] = $result['g_introduction'];
            $this->rs['info']['URL'] = $data["g_image"];
            $this->rs['code'] = 1;
        }
        else{
            $this->rs['msg'] = $this->msg;
        }
        return $this->rs;
    }



    public function join($data){
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);
        $this->checkG($data['g_id']);

        if ($this->u_status == '1' && $this->g_status == '0') {
            $data = array(
                'group_base_id' => $data['g_id'],
                'user_base_id'  => $this->cookie['userID'],
                'authorization' => "03",
            );

            $result = $this->model->join($data);
            $this->model->joinMessage($data);
            $rs['msg'] = '加入成功！并通知星球创建者';
            $rs['code'] = 1;
        }else{
            $rs['msg'] = $this->msg;
        }
        return $rs;
    }
    public function quit($data){
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);
        $this->checkG($data['g_id']);
        if ($this->u_status == '1' && $this->g_status == '1') {
            $data = array(
                'group_base_id' => $data['g_id'],
                'user_base_id'  => $this->cookie['userID'],
            );
            $result = $this->model->quit($data);
            $this->model->quitMessage($data);
            $re['msg'] = '退出成功！并通知星球创建者';
            $re['code'] = 1;
        }else{
            $re['msg'] = $this->msg;
        }

        return $re;
    }

    public function uStatus($data){
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);

        if ($this->u_status == '1') {
            $this->rs['info'] = $this->cookie;
            $this->rs['code'] = 1;
        }else{
            $this->rs['msg'] = $this->msg;
        }

        return $this->rs;
    }

    public function gStatus($data){
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);
        $this->checkG($data['g_id']);

        if ($this->g_status == '1') {
            $this->rs['code'] = 1;
            $this->rs['msg']  = $this->msg;
        }else{
            $this->rs['code'] = 0;
            $this->rs['msg']  = $this->msg;
        }

        return $this->rs;
    }

    public function lists($page,$pages){
        $this->model  = new Model_Group();
        $domain = new Domain_Common();
        $all_num      = $this->model->getAllNum();              //总条
        $page_num     =empty($pages)?20:$pages;                 //每页条数
        $page_all_num =ceil($all_num/$page_num);                //总页数
        if ($page_all_num == 0){
            $page_all_num =1;
        }
        if($page > $page_all_num){
            $page = $page_all_num;
        }
        $page         =empty($page)?1:$page;                    //当前页数
        $page         =(int)$page;                              //安全强制转换
        $limit_st     =($page-1)*$page_num;                     //起始数

        $this->pages['pageCount'] = $page_all_num;
        $this->pages['currentPage'] = $page;
        $lists = $this->model->lists($limit_st, $page_num);
        $lists=$domain->judgeImageExist($lists);
        return $lists;
    }

    public function posts($data){
        $this->model = new Model_Group();
        $this->checkStatus($data['user_id']);
        $this->checkG($data['group_base_id']);
        //上传路径

        if ($this->u_status == '1' && $this->g_status == '1') {
            $b_data = array(
                'user_base_id'  => $this->cookie['userID'],
                'group_base_id' => $data['group_base_id'],
                'title'         => $data['title'],
            );
            $pb = DI()->notorm->post_base->insert($b_data);
            $time = date('Y-m-d H:i:s',time());
            $d_data = array(
                'post_base_id' => $pb['id'],
                'user_base_id' => $this->cookie['userID'],
                'text' => $data['text'],
                'floor'=> '1',
                'createTime' => $time,
            );
            $pd = DI()->notorm->post_detail->insert($d_data);
/*            foreach ($data['p_image'] as $key => $value) {
                if(!empty($value)) {
                    $fileName = $this->doFileUpload($key,$value);
                    $pi = $this->saveData($fileName,$value,$pb);
            }
            else{
                $pi = NULL;
            }
            }*/
            $this->rs['code'] = 1;
            $this->rs['info'] = $pd;
            $this->rs['info']['title']=$pb['title'];
            //$this->rs['info']['URL']=$pi['p_image'];
            //$this->rs['info']['post_image_id']=$pi['id'];
        }else{
            $this->rs['msg'] = $this->msg;
        }

        return $this->rs;
    }

    public function getJoined($page,$pages,$user_id){
        $this->model  = new Model_Group();
        $domain = new Domain_Common();
        $all_num      = $this->model->getAllGroupJoinednum($user_id);              //总条
        $all_num = count($all_num);
        $page_num     =empty($pages)?20:$pages;                 //每页条数
        $page_all_num =ceil($all_num/$page_num);                //总页数
        if ($page_all_num == 0){
            $page_all_num =1;
        }
        if($page > $page_all_num){
            $page = $page_all_num;
        }
        $page         =empty($page)?1:$page;                    //当前页数
        $page         =(int)$page;                              //安全强制转换
        $limit_st     =($page-1)*$page_num;                     //起始数

        $this->pages['pageCount'] = $page_all_num;
        $this->pages['currentPage'] = $page;
        $this->pages['num']=$all_num;
        $this->pages['user_name']=$this->model->getUser($user_id);
        $groups=$this->model->getJoined($limit_st, $page_num,$user_id);
        $groups=$domain->judgeImageExist($groups);
        return $groups;
    }

    public function getCreate($page,$pages,$user_id){
        $this->model  = new Model_Group();
        $domain = new Domain_Common();
        $all_num      = $this->model->getAllGroupCreatenum($user_id);          //总条
        $all_num = count($all_num);
        $page_num     =empty($pages)?2:$pages;                 //每页条数
        $page_all_num =ceil($all_num/$page_num);                //总页数
        if ($page_all_num == 0){
            $page_all_num =1;
        }
        if($page > $page_all_num){
            $page = $page_all_num;
        }
        $page         =empty($page)?1:$page;                    //当前页数
        $page         =(int)$page;                              //安全强制转换
        $limit_st     =($page-1)*$page_num;                     //起始数

        $this->pages['pageCount'] = $page_all_num;
        $this->pages['currentPage'] = $page;
        $this->pages['num']=$all_num;
        $this->pages['user_name']=$this->model->getUser($user_id);
        $groups=$this->model->getCreate($limit_st, $page_num,$user_id);
        $groups=$domain->judgeImageExist($groups);
        return $groups;
    }


    public function getGroupInfo($group_id){
        $this->model=new Model_Group();
        $this->rs=$this->model->getGroupInfo($group_id);
        return $this->rs;
    }

    public function alterGroupInfo($group_id,$g_introduction,$g_image){
        $this->model=new Model_Group();
        $this->rs=$this->model->alterGroupInfo($group_id,$g_introduction,$g_image);
        return $this->rs;
    }
    /*
    public function doFileUpload($order, $base64String) {
        $fileName = time() . "_" . $order;
        //echo "File " . $fileName . " upload....<br />";
        return $fileName;
    }*/

/*    public function saveData($fileName,$value,$pb) {
        $date=date("Y/m/d");
        $RootDIR = dirname(__FILE__);
        $path=$RootDIR."/../../Public/demo/upload/posts/$date/";
        $base64_image_string = $value;
        $output_file_without_extentnion = $fileName;
        $path_with_end_slash = "$path";
        //创建上传路径
        if(!is_readable($path)) {
            is_file($path) or mkdir($path,0777,true);
        }
        //调用接口保存base64字符串为图片
        $filepath = $this->save_base64_image($base64_image_string, $output_file_without_extentnion, $path_with_end_slash );
        $size = getimagesize ($filepath);
        if($size[0]>94&&$size[1]>94){
            include_once "../../Library/resizeimage.php";
            $imageresize = new ResizeImage($filepath, 94, 94,1, $filepath);//裁剪图片
        }
        $data["p_image"] = substr($filepath,-41);
        $i_data = array(
            'post_base_id' => $pb['id'],
            'p_image'   => $data["p_image"],
        );
        $pi = DI()->notorm->post_image->insert($i_data);
        $pi['p_image']=$_SERVER['HTTP_HOST'].$pi['p_image'];
        //echo "Save " . $filepath . " success!<br />";
        return $pi;
    }*/
/*
 * 用于申请加入私密星球
 */
    public function PrivateGroup($data) {
        $model = new Model_Group();
        $rs = $model->PrivateGroup($data);
        if($rs) {
            $info['code'] = 1;
            $info['msg'] = '申请成功！请等待创建者审核！';
        }else {
            $info['code'] = 0;
            $info['msg'] = '申请失败！';
        }
        return $info;
    }
/*
 * 用于显示加入星球的用户，方便管理
 */
    public function UserManage($data){
        $model = new Model_Group();
        $rs = $model->UserManage($data);
        foreach($rs as $keys => $value){
            $rs[$keys] = array(
                'user_id'           =>$value['user_base_id'],
                'user_name'         =>$model->getUser($value['user_base_id']),
                'user_image'        =>'http://7xlx4u.com1.z0.glb.clouddn.com/o_1aqt96pink2kvkhj13111r15tr7.jpg?imageView2/1/w/100/h/100',
            );
        }
        if($rs) {
            $info['group_id'] = $data['group_id'];
            $info['info'] = $rs;
            $info['code'] = 1;
            $info['msg'] = '显示成功！';
        }else {
            $info['code'] = 0;
            $info['msg'] = '该星球没有其他成员！';
        }
        return $info;
    }
/*
 * 用于删除加入星球的用户
 */
    public function deleteGroupMember($data){
        $model = new Model_Group();
        $rs = $model->deleteGroupMember($data);
        if($rs) {
            //$info['info'] = $rs;
            $info['code'] = 1;
            $info['msg'] = '操作成功！并通知被删除的成员';
            $rs = $model->dgmMessage($data);
        }else {
            $info['code'] = 0;
            $info['msg'] = '操作失败！';
        }
        return $info;
    }

    public function searchGroup($text,$gnum,$gn){
        $model=new Model_Group();
        $domain = new Domain_Common();
        $re=$model->searchGroup($text,$gnum,$gn);
        $page_num=$gnum;
        $all_num=$model->searchGroupNum($text);
        $page_all_num =ceil($all_num/$page_num);                //总页数
        if ($page_all_num == 0){
            $page_all_num =1;
        }
        $re['group']=$domain->judgeImageExist($re['group']);
        $re['GroupPage']=$page_all_num;
        return $re;
    }
}




 ?>