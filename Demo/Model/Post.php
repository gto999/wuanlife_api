<?php

class Model_Post extends PhalApi_Model_NotORM {

/*
 * 主页帖子展示
 */
    public function getIndexPost($page,$userID) {

        $num=30;
        $rs   = array();

        $sql = 'SELECT ceil(count(*)/:num) AS pageCount '
             . "FROM post_base pb,group_base gb WHERE pb.delete=0 AND pb.group_base_id=gb.id AND gb.private='0' AND gb.delete='0'";

        $params = array(':num' =>$num);
        $pageCount = DI()->notorm->post_base->queryAll($sql, $params);
        $rs['pageCount'] = (int)$pageCount[0]['pageCount'];
        if ($rs['pageCount'] == 0 ){
            $rs['pageCount']=1;
        }
        if($page > $rs['pageCount']){
            $page = $rs['pageCount'];
        }
        $rs['currentPage'] = $page;
        $sql = 'SELECT pb.id AS postID,pb.title,pd.text,pb.lock,pd.createTime,ub.nickname,gb.id AS groupID,gb.name AS groupName,(SELECT approved FROM post_approved WHERE user_id=:userID AND post_id=pb.id AND floor=1) AS approved,(SELECT count(approved) FROM post_approved WHERE floor=1 AND post_id=pb.id AND approved=1) AS approvednum '
             . 'FROM post_detail pd,post_base pb ,group_base gb,user_base ub,post_approved pa '
             . "WHERE pb.id=pd.post_base_id AND pb.user_base_id=ub.id AND pb.group_base_id=gb.id AND pb.delete='0' AND gb.delete='0' AND gb.private='0' "
             . 'GROUP BY pb.id '
             . 'ORDER BY MIN(pd.createTime) DESC '
             . 'LIMIT :start,:num ';
        $params = array(':start' =>($page-1)*$num , ':num' =>$num,':userID'=>$userID);
        $rs['posts'] = DI()->notorm->user_base->queryAll($sql, $params);
        foreach ($rs['posts'] as $key => $value) {
            if(empty($rs['posts']["$key"]['approved'])){
                $rs['posts']["$key"]['approved'] = '0';
            }
        }
        return $rs;
    }

/*
 * 单个星球的帖子展示
 */
    public function getGroupPost($groupID,$page) {

        $num=30;
        $rs   = array();
        $groupData=DI()->notorm->group_base
        ->select('id as groupID,name as groupName')
        ->where('id =?',$groupID)
        ->fetchAll();
        if(empty($groupData)){
             throw new PhalApi_Exception_BadRequest('星球不存在！');
        }
        $rs['groupID'] = $groupData['0']['groupID'];
        $rs['groupName'] = $groupData['0']['groupName'];


        $sql = 'SELECT ceil(count(*)/:num) AS pageCount '
             . 'FROM post_base pb,group_base gb '
             . 'WHERE pb.group_base_id=gb.id AND gb.id=:group_id AND pb.delete=0 ';

        $params = array(':group_id' =>$groupID,':num' =>$num);
        $pageCount = DI()->notorm->user_base->queryAll($sql, $params);
        $rs['pageCount'] = (int)$pageCount[0]['pageCount'];
        if ($rs['pageCount'] == 0 ){
            $rs['pageCount']=1;
        }
        if($page > $rs['pageCount']){
            $page = $rs['pageCount'];
        }
        $rs['currentPage'] = $page;
        $sql = 'SELECT  pb.digest,pb.id AS postID,pb.title,pd.text,pd.createTime,ub.id,ub.nickname,pb.sticky,pb.lock '
             . 'FROM post_detail pd,post_base pb ,group_base gb,user_base ub '
             . 'WHERE pb.id=pd.post_base_id AND pb.user_base_id=ub.id AND pb.group_base_id=gb.id AND pb.group_base_id=:group_id AND pb.delete=0 '
             . 'GROUP BY pb.id '
             . 'ORDER BY pb.sticky DESC, '
             . 'MAX(pd.createTime) DESC '
             . 'LIMIT :start,:num ';
        $params = array(':group_id' =>$groupID,':start' =>($page-1)*$num , ':num' =>$num);
        $rs['posts'] = DI()->notorm->post_base->queryAll($sql, $params);
        return $rs;
    }

/*
 * 我的星球帖子展示
 */
    public function getMyGroupPost($userID,$page) {

        $num=30;
        $rs   = array();

        $sql = 'SELECT ceil(count(*)/:num) AS pageCount '
             . 'FROM post_base pb,group_base gb,group_detail gd '
             . 'WHERE pb.group_base_id=gb.id AND gb.id=gd.group_base_id AND gd.user_base_id=:user_id AND pb.delete=0 AND gb.delete=0 ';

        $params = array(':user_id' =>$userID,':num' =>$num);
        $pageCount = DI()->notorm->user_base->queryAll($sql, $params);
        $rs['pageCount'] = (int)$pageCount[0]['pageCount'];
        if ($rs['pageCount'] == 0 ){
            $rs['pageCount']=1;
        }
        if($page > $rs['pageCount']){
            $page = $rs['pageCount'];
        }
        $rs['currentPage'] = $page;
        $sql = 'SELECT  pb.id AS postID,pb.title,pd.text,pb.lock,pd.createTime,ub.nickname,gb.id AS groupID,gb.name AS groupName '
             . 'FROM post_detail pd,post_base pb ,group_base gb,user_base ub '
             . 'WHERE pb.id=pd.post_base_id AND pb.user_base_id=ub.id AND pb.group_base_id=gb.id AND pb.delete=0 AND gb.delete=0 '
             . 'AND gb.id in (SELECT group_base_id FROM group_detail gd WHERE gd.user_base_id =:user_id )'
             . 'GROUP BY pb.id '
             . 'ORDER BY MAX(pd.createTime) DESC '
              . 'LIMIT :start,:num ';
        $params = array(':user_id' =>$userID,':start' =>($page-1)*$num , ':num' =>$num );
        $rs['posts'] = DI()->notorm->post_base->queryAll($sql, $params);
        return $rs;
    }

/*
 * 单个帖子展示
 */
    public function getPostBase($postID,$userID) {
        $rs   = array();
        $sql = 'SELECT pb.id AS postID,gb.id AS groupID,gb.name AS groupName,pb.title,pd.text,ub.id,ub.nickname,pd.createTime,pb.sticky,pb.lock,(SELECT approved FROM post_approved WHERE user_id=:user_id AND post_id=:post_id AND floor=1) AS approved,(SELECT count(approved) FROM post_approved WHERE floor=1 AND post_id=:post_id AND approved=1) AS approvednum '
             . 'FROM post_detail pd,post_base pb ,group_base gb,user_base ub '
             . 'WHERE pb.id=pd.post_base_id AND pb.delete=0 AND pb.user_base_id=ub.id AND pb.group_base_id=gb.id AND pb.id=:post_id AND pd.floor=1' ;
        $params = array(':post_id' =>$postID,':user_id'=>$userID );
        $rs = DI()->notorm->post_base->queryAll($sql, $params);
        foreach ($rs as $key => $value) {
            if(empty($rs["$key"]['approved'])){
                $rs["$key"]['approved'] = '0';
            }
        }
        if (!empty($rs)){
            $rs[0]['sticky']=(int)$rs[0]['sticky'];
            $rs[0]['lock']=(int)$rs[0]['lock'];
            preg_match_all("(http://[-a-zA-Z0-9@:%_\+.~#?&//=]+[.jpg.gif.png])",$rs[0]['text'],$rs[0]['p_image']);
            /*
            $p_image = array();
            $results = DI()->notorm->post_image
                ->select('p_image,post_image_id')
                ->where('post_base_id =?', $postID)
                ->AND('post_image.delete=?','0');
                // ->fetchall();

            foreach ($results as $key => $row) {
                $p_image[$key] = array("id"=>(int)$row['post_image_id'],"URL"=>"http://".$_SERVER['HTTP_HOST'].$row['p_image']);
            }
            $rs[0]['p_image']=$p_image;
            if(empty($p_image)){
                $rs[0]['p_image']=NULL;
            }
            */
        }
        return $rs;

    }

/*
 * 单个帖子的回复展示
 */
    public function getPostReply($postID,$page,$userID) {

        $num=30;
        $rs   = array();

        $rs['postID']=$postID;
        $sql = 'SELECT ceil(count(pd.post_base_id)/:num) AS pageCount,count(*) AS replyCount '
         . 'FROM post_detail as pd '
         . 'WHERE pd.post_base_id=:post_id AND pd.floor>1 AND pd.delete=0 ';

        $params = array(':post_id' =>$postID,':num' =>$num);
        $count = DI()->notorm->user_base->queryAll($sql, $params);
        $rs['replyCount'] = (int)$count[0]['replyCount'];
        $rs['pageCount'] = (int)$count[0]['pageCount'];
        if ($rs['pageCount'] == 0 ){
            $rs['pageCount']=1;
        }
        if($page > $rs['pageCount']){
            $page = $rs['pageCount'];
        }
        $rs['currentPage'] = $page;
        /*
        $rs['reply']= DI()->notorm->post_detail
        ->SELECT('post_detail.text,user_base.id AS user_id,user_base.nickname,post_detail.replyid,(SELECT nickname FROM user_base WHERE user_base.id =post_detail.replyid ) AS replynickname,post_detail.createTime,post_detail.floor')
        ->WHERE('post_detail.post_base_id = ?',$postID)
        ->AND('post_detail.delete = ?',0)
        ->AND('post_detail.floor > ?','1')
        ->order('post_detail.floor ASC')
        ->limit(($page-1)*$num,$num)
        ->fetchALL();
        */
        $sql = 'SELECT pd.replyFloor,pd.text,ub.id AS user_id,ub.nickname,pd.replyid,(SELECT nickname FROM user_base WHERE user_base.id = pd.replyid) AS replynickname,pd.createTime,pd.floor,(SELECT approved FROM post_approved WHERE user_id=:user_id AND post_id=:post_id AND floor=pd.floor) AS approved,(SELECT count(approved) FROM post_approved WHERE floor=pd.floor AND post_id=:post_id AND approved=1) AS approvednum '
             . 'FROM user_base ub,post_detail pd '
             . 'WHERE pd.post_base_id = :post_id AND pd.delete = 0 AND pd.floor > 1 AND ub.id=pd.user_base_id '
             . 'ORDER BY pd.floor ASC '
             . 'LIMIT :start,:num ';
        $params = array(':post_id' =>$postID,':start'=>($page-1)*$num,':num' =>$num,':user_id' =>$userID);
        $rs['reply'] = DI()->notorm->post_detail->queryAll($sql, $params);
        foreach ($rs['reply'] as $key => $value) {
            if(empty($rs['reply']["$key"]['approved'])){
                $rs['reply']["$key"]['approved'] = '0';
            }
        }
        return $rs;
    }
/*
 * 帖子回复操作
 */
    public function PostReply($data,$replyfloor) {
        $rs = array();
        $time = date('Y-m-d H:i:s',time());
        //查询最大楼层
        $sql=DI()->notorm->post_detail
        ->select('post_base_id,user_base_id,max(floor)')
        ->where('post_base_id =?',$data['post_base_id'])
        ->fetchone();
        $data['createTime'] = $time;
        $data['floor'] = ($sql['max(floor)'])+1;
        $replyid=DI()->notorm->post_detail->select('user_base_id')->where('post_base_id =?',$data['post_base_id'])->where('floor =?',$replyfloor)->fetchone();
        $data['replyid']=$replyid['user_base_id'];
        $data['replyFloor']=$replyfloor;
        $a=DI()->notorm->user_base->select('nickname')->where('id',$data['user_base_id'])->fetchone();


        $rs = DI()->notorm->post_detail->insert($data);
        $rs['nickname']=$a['nickname'];
        $rs['user_id']=$rs['user_base_id'];
        $rs['postID']=$rs['post_base_id'];
        $rs['replynickname']=DI()->notorm->user_base->select('nickname')->where('id =?',$data['replyid'])->fetchone()['nickname'];
        $rs['page']=$this->getPostReplyPage($data['post_base_id'],$data['floor']);
        $this->addReplyMessage($rs);
        unset($rs['user_base_id']);
        unset($rs['post_base_id']);
        return $rs;
    }
/*
 * 获得帖子回复的位置楼层的回复详情
 */
    public function getPostReplyInfo($p_id,$floor){
        $sql=DI()->notorm->post_detail
        ->select('*')
        ->where('post_base_id =?',$p_id)
        ->where('floor =?',$floor)
        ->fetchone();
    }
/*
 * 通过帖子id查找帖子主人id
 */
    public function getPostCreator($p_id){
        $sql=DI()->notorm->post_base->select('user_base_id')->where('id',$p_id)->fetch();
        return $sql;
    }
/*
 *将回复情况发送给帖主或者楼主
 */
    public function addReplyMessage($rs){
        $postc=$this->getPostCreator($rs['post_base_id']);
        if(!empty($rs['replyid'])){
            $postc['user_base_id'] = $rs['replyid'];
        }
            if($postc['user_base_id']==$rs['user_base_id']){
                return false;
        }
        $field=array(
                    'message_base_code'=>'0007',
                    'user_base_id'=>$postc['user_base_id'],
                    'id_1'    =>$rs['user_base_id'],
                    'id_2'    =>$rs['post_base_id'],
                    'createTime'=>time(),
        );
        $sql = DI()->notorm->message_detail->insert($field);
        if($sql){
            $field = array(
                        'message_detail_id' =>$sql['id'],
                        'text'              =>$rs['floor'],
            );
            DI()->notorm->message_text->insert($field);
        }
    }
/*
 * 编辑帖子
 */
    public function editPost($data) {
        $rs = array();
        $time = date('Y-m-d H:i:s',time());
        $b_data = array(
                'title' => $data['title'],
        );
        $d_data = array(
                'text' => $data['text'],
                'createTime' => $time,
        );
        $sql=DI()->notorm->post_detail
        ->select('user_base_id')
        ->where('post_base_id =?',$data['post_base_id'])
        ->fetchone();
        if($data['user_id']==$sql['user_base_id']) {
            $pb = DI()->notorm->post_base
            ->where('id =?', $data['post_base_id'])
            ->update($b_data);
            $pd = DI()->notorm->post_detail
            ->where('post_base_id =?', $data['post_base_id'])
            ->AND('post_detail.floor = ?','1')
            ->update($d_data);
            if(!empty($data['post_image_id'])){
                $delimage = DI()->notorm->post_image
                ->where('post_image_id =?', $data['post_image_id'])
                ->AND('post_image.post_base_id = ?',$data['post_base_id'])
                ->update(array('`delete`'=>'1'));
            }
/*          $domain = new Domain_Group();
            $pei = array("id"=>$data['post_base_id']);
            foreach ($data['p_image'] as $key => $value) {
                if(!empty($value)) {
                    $fileName = $domain->doFileUpload($key,$value);
                    $pi = $domain->saveData($fileName,$value,$pei);
                }
                else {
                    $pi = NULL;
                }
            }*/
            $rs['code']=1;
            $rs['info']['post_base_id']=$data['post_base_id'];
            $rs['info']['user_base_id']=$data['user_id'];
            $rs['info']['title']=$data['title'];
            $rs['info']['text']=$data['text'];
            $p_image = array();
            $results = DI()->notorm->post_image
            ->select('p_image,post_image_id')
            ->where('post_base_id =?', $data['post_base_id'])
            ->AND('post_image.delete=?','0');
            // ->fetchall();
            /*不需要返回URL值
            foreach ($results as $key => $row) {
                $p_image[$key] = array("id"=>(int)$row['post_image_id'],"URL"=>"http://".$_SERVER['HTTP_HOST'].$row['p_image']);
            }
            if(empty($p_image)){
                $p_image=NULL;
            }
            $rs['info']['URL']=$p_image;
            */
            $rs['info']['floor']=1;
            $rs['info']['createTime']=$time;
        }else{
            $rs['code']=0;
            $rs['msg']="您没有权限!";
        }
        return $rs;
    }
    protected function getTableName($id) {
        return 'user';
    }

/*
 * 置顶帖子
 */
    public function stickyPost($data){
        $rs = array();

        $s_data = array(
            'sticky' => '1',
        );
        $s = DI()->notorm->post_base
            ->where('id =?', $data['post_id'])
            ->update($s_data);
        $rs['code']=1;
        $rs['re']="操作成功";

        return $rs;
    }

/*
 * 取消置顶帖子
 */
    public function unStickyPost($data){
        $rs = array();

        $s_data = array(
                'sticky' => '0',
        );
            $s = DI()->notorm->post_base
            ->where('id =?', $data['post_id'])
            ->update($s_data);
            $rs['code']=1;
            $rs['re']="操作成功";

        return $rs;
    }

/*
 * 逻辑删除帖子
 */
    public function deletePost($data){
        $rs = array();

        $d_data = array(
                '`delete`' => '1',
        );

            $sa = DI()->notorm->post_base
            ->where('id =?', $data['post_id'])
            ->update($d_data);
            /*$sb = DI()->notorm->post_detail
            ->where('post_base_id=?', $data['post_id'])
            ->update($d_data);*/
            $rs['code']=1;
            $rs['re']="操作成功";
        return $rs;
    }

/*
 * 通过星球id查找创建者id
 */
    public function getCreaterId($groupID){
        $createrId=DI()->notorm->group_detail
        ->select('user_base_id')
        ->where('group_base_id=?',$groupID)
        ->and('authorization=?','01')
        ->fetchone();
        return $createrId;
        }

/*
 * 通过帖子id查找星球id
 */
    public function getGroupId($post_id){
        $sqla=DI()->notorm->post_base
            ->select('group_base_id')
            ->where('id=?',$post_id)
            ->fetchone();
        return $sqla['group_base_id'];
    }

/*
 * 判断用户是否是帖子主人
 */
    public function judgePoster($user_id,$post_id){
        $sql=DI()->notorm->post_detail->select('floor')->where('user_base_id=?',$user_id)->where('post_base_id=?',$post_id)->fetch();
        if($sql['floor']==1){
            $rs=1;
        }else{
            $rs=0;
        }
        return $rs;
    }

/*
 * 判断帖子是否存在
 */
    public function judgePostExist($post_id){
        $g_id = $this->getGroupId($post_id);
        $model=new Model_Group();
        $rs=$model->judgeGroupExist($g_id);
        if($rs){
            $sql=DI()->notorm->post_base->select('id')->where(array('id'=>$post_id,'`delete`'=>'0'))->fetch();
        }
        if(!empty($sql)){
            $rs=1;
        }else{
            $rs=0;
        }
        return $rs;
    }

/*
 * 锁定帖子
 */
    public function lockPost($post_id){
        $data = array(
            '`lock`' => '1',
        );
        $sql=DI()->notorm->post_base->where('id',$post_id)->update($data);
        return $sql;
    }

/*
 * 解除锁定帖子
 */
    public function unlockPost($post_id){
        $data = array(
            '`lock`' => '0',
        );
        $sql=DI()->notorm->post_base->where('id',$post_id)->update($data);
        return $sql;
    }

/*
 * 判断用户是否是帖子主人
 */
    public function judgePostUser($user_id,$post_id){
        $sql=DI()->notorm->post_base->where('id',$post_id)->where('user_base_id',$user_id)->fetch();
        return $sql;
    }

/*
 * 判断帖子是否被锁定
 */
    public function judgePostLock($post_id){
        $sql=DI()->notorm->post_base->where('id=?',$post_id)->fetch();
        return $sql['lock'];
    }
/*
 * 查询帖子
 */
    public function searchPosts($text,$pnum,$pn){
        if(empty($pn)){
            $rs['posts'] = array();
            return $rs;
        }
        $text = strtolower($text);
        $num=($pn-1)*$pnum;
        $sql = 'SELECT pb.id AS postID,pb.title,pd.text,pb.lock,pd.createTime,ub.nickname,gb.id AS groupID,gb.name AS groupName '
             . 'FROM post_detail pd,post_base pb ,group_base gb,user_base ub '
             . "WHERE pb.id=pd.post_base_id AND pb.user_base_id=ub.id AND pb.group_base_id=gb.id AND pb.delete='0' AND gb.delete='0' AND gb.private='0' "
             . "AND lower(pb.title) LIKE '%$text%' "
             . 'GROUP BY pb.id '
             . 'ORDER BY COUNT(pd.post_base_id) DESC '
             . "LIMIT $num,$pnum";
        $rs['posts'] = DI()->notorm->user_base->queryAll($sql);
        return $rs;


    }

/*
 * 查询帖子（数量）
 */
    public function searchPostsNum($text){
        $text = strtolower($text);
        $sql = 'SELECT count(*) AS num '
             . "FROM post_base pb,group_base gb WHERE pb.delete=0 AND pb.group_base_id=gb.id AND gb.private='0' AND gb.delete='0'"
             . "AND lower(pb.title) LIKE '%$text%'";
        $re = $this->getORM()->queryAll($sql);
        return $re[0]['num'];
    }

/*
 * 收藏帖子
 */
    public function collectPost($user_id,$post_id){
        $data = array(
            'post_base_id' => $post_id,
            'user_base_id'=>$user_id,
            'createTime'=>time()
        );
        $sql=DI()->notorm->user_collection->insert($data);
        return $sql;
    }

/*
 * 获取收藏帖子详情
 */
    public function getCollectPost($userID,$page) {

        $num=20;
        $rs   = array();

        $sql = 'SELECT ceil(count(*)/:num) AS pageCount '
             . 'FROM user_collection '
             . 'WHERE user_collection.user_base_id=:user_id AND user_collection.delete=0 ';
        $params = array(':user_id' =>$userID,':num' =>$num);
        $pageCount = DI()->notorm->user_base->queryAll($sql, $params);
        $rs['pageCount'] = (int)$pageCount[0]['pageCount'];
        if ($rs['pageCount'] == 0 ){
            $rs['pageCount']=1;
        }
        if($page > $rs['pageCount']){
            $page = $rs['pageCount'];
        }
        $rs['currentPage'] = $page;
        $sql = 'SELECT pb.id AS postID,uc.createTime,pb.title AS post_name,gb.id AS gbID,gb.name AS groupName,ub.nickname AS user_name,pb.delete '
             . 'FROM user_collection uc,post_base pb,group_base gb,user_base AS ub '
             . 'WHERE pb.id=uc.post_base_id AND pb.group_base_id=gb.id AND uc.delete=0 AND uc.user_base_id=:user_id AND uc.delete=0 AND pb.user_base_id=ub.id '
              . 'LIMIT :start,:num ';
        $params = array(':user_id' =>$userID,':start' =>($page-1)*$num , ':num' =>$num );
        $rs['posts'] = DI()->notorm->post_base->queryAll($sql, $params);
        foreach ($rs['posts'] as $key => $value) {
            $rs['posts']["$key"]['createTime']=date('Y-m-d H:i:s',$rs['posts']["$key"]['createTime']);
        }
        return $rs;
    }


    public function deletePostReply($user_id,$post_base_id,$floor){
        $data=array('`delete`' => '1');
        $sql=DI()->notorm->post_detail->where('post_base_id =?',$post_base_id)->where('floor =?',$floor)->update($data);
            $rs['code']=1;
            $rs['re']="操作成功";
        return $rs;
    }

    public function ifExistCollectPost($post_id,$user_id){
        $sql=DI()->notorm->user_collection->select('*')->where('post_base_id',$post_id)->where('user_base_id',$user_id)->fetch();
        return $sql;
    }

    public function judgeCollectPost($post_id,$user_id){
        $sql=DI()->notorm->user_collection->select('*')->where('post_base_id',$post_id)->where('user_base_id',$user_id)->where('`delete`',0)->fetch();
        if($sql){
            $collect=1;
        }else{
            $collect=0;
        }
        return $collect;
    }


    public function existCollectPost($user_id,$post_id){
        $time=time();
        $data=array('`delete`' => '0',
            'createtime'=>$time);
        $sql=DI()->notorm->user_collection->where('post_base_id =?',$post_id)->where('user_base_id =?',$user_id)->update($data);
            $rs['code']=1;
            $rs['re']="收藏成功";
        return $rs;
    }

/*
 * 判断用户是否是帖子回复的主人
 */
    public function judgePostReplyUser($user_id,$post_id,$floor){
        $sql=DI()->notorm->post_detail->where('post_base_id',$post_id)->where('user_base_id',$user_id)->where('floor',$floor)->fetch();
        return $sql;
    }


    public function deleteCollectPost($user_id,$post_id){
        $data=array('`delete`' => '1');
        $sql=DI()->notorm->user_collection->where('post_base_id',$post_id)->where('user_base_id',$user_id)->update($data);
        if($sql){
            $rs['code']=1;
            $rs['re']="操作成功";
        }else{
            $rs['code']=0;
            $rs['re']="操作失败";
        }
        return $rs;
    }

/*
 * 查询帖子回复所在的页数
 */
    public function getPostReplyPage($p_id,$floor){
        $num=30;
        $sql = 'SELECT ceil(count(pd.post_base_id)/:num) AS pageCount,count(*) AS replyCount '
         . 'FROM post_detail as pd '
         . 'WHERE pd.post_base_id=:post_id AND pd.floor>1 AND pd.delete=0 ';
        $params = array(':post_id' =>$p_id,':num' =>$num);
        $count = DI()->notorm->post_detail->queryAll($sql, $params);
        for($i=1;$i<=$count[0]['pageCount'];$i++){
            $floors = DI()->notorm->post_detail
            ->SELECT('floor')
            ->WHERE('post_detail.post_base_id = ?',$p_id)
            ->AND('post_detail.delete = ?',0)
            ->limit(($i-1)*$num,$num)
            ->fetchAll();
            foreach($floors as $key =>$value){
                if($value['floor'] == $floor){
                    return $i;
                }
            }
        }
        return false;
    }
    public function getApprovePost($data){
        $sql=DI()->notorm->post_approved->select('*')->where('post_id',$data['post_id'])->where('user_id',$data['user_id'])->where('floor',$data['floor'])->fetch();
        return $sql;
    }
    public function updateApprovePost($data){
        $approved = $this->getApprovePost($data);
        if($approved['approved']){
            $field = array('approved'=>0);
            $rs['code'] = 2;
            $rs['msg'] = '取消点赞成功';
        }else{
            $field = array('approved'=>1);
            $rs['code'] = 1;
            $rs['msg'] = '点赞成功';
        }
        $sql=DI()->notorm->post_approved->where('post_id',$data['post_id'])->where('user_id',$data['user_id'])->where('floor',$data['floor'])->update($field);
        if($sql){
            return $rs;
        }else{
            $rs['code'] = 0;
            $rs['msg'] = '操作失败';
            return $rs;
        }
    }
    public function addApprovePost($data){
        $field = array(
                    'user_id' => $data['user_id'],
                    'post_id' => $data['post_id'],
                    'floor'   => $data['floor'],
                    'approved' => 1,
        );
        $sql=DI()->notorm->post_approved->insert($field);
        if($sql){
            $rs['code'] = 1;
            $rs['msg']  = '点赞成功';
        }else{
            $rs['code'] = 0;
            $rs['msg']  = '点赞失败';
        }
        return $rs;
    }
}