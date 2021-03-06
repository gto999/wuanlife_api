<?php
/**
 * 数据库接口服务类
 */
class Api_Post extends PhalApi_Api{
    public function getRules(){
        return array(
            'getIndexPost' => array(
                'user_id' => array('name' => 'user_id', 'type' => 'int', 'require' => false, 'desc' => '用户ID'),
                'page' =>array('name' => 'pn', 'type' => 'int',  'desc' => '第几页', 'default' => '1'),
            ),
            'getGroupPost' => array(
                'groupID' => array('name' => 'group_id', 'type' => 'int', 'require' => true, 'desc' => '小组ID'),
                'userID' => array('name' => 'user_id', 'type' => 'int', 'require' => false, 'desc' => '用户ID'),
                'page' =>array('name' => 'pn', 'type' => 'int',  'desc' => '第几页', 'default' => '1'),
            ),
             'getPrivateGroupPost' => array(
                'groupID' => array('name' => 'group_id', 'type' => 'int', 'require' => true, 'desc' => '小组ID'),
                'userID' => array('name' => 'user_id', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'page' =>array('name' => 'pn', 'type' => 'int',  'desc' => '第几页', 'default' => '1'),
            ),
            'getMyGroupPost' => array(
                'userID' => array('name' => 'id', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'page' =>array('name' => 'pn', 'type' => 'int',  'desc' => '第几页', 'default' => '1'),
            ),
            'getPostBase' => array(
                'postID' => array('name' => 'post_id', 'type' => 'int', 'require' => true, 'desc' => '帖子ID'),
                'userID' => array('name' => 'user_id', 'type' => 'int', 'require' => false,'desc' => '用户ID'),
            ),
            'getPostReply' => array(
                'postID' => array('name' => 'post_id', 'type' => 'int', 'require' => true, 'desc' => '帖子ID'),
                'page' =>array('name' => 'pn', 'type' => 'int',  'desc' => '当前回帖的页码', 'default' => '1'),
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => false,
                        'desc'    => '用户id',
                ),
            ),
            'PostReply' => array(
                'post_base_id' => array('name' => 'post_id', 'type' => 'int', 'require' => true, 'desc' => '帖子ID'),
                'text'   => array('name' => 'text', 'type' => 'string', 'min' => '1','require' => true, 'desc' => '回复内容'),
                'user_id' => array('name' => 'user_id', 'type' => 'string', 'require' => true, 'desc' => '回复人ID'),
                'replyfloor' => array('name' => 'replyfloor', 'type' => 'int', 'require' => false, 'desc' => '帖子内被回复的人的楼层')
            ),
            'editPost'  => array(
                'user_id' => array('name' => 'user_id', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
                'post_base_id' => array('name' => 'post_id', 'type' => 'int', 'require' => true, 'desc' => '帖子ID'),
                'title' => array('name' => 'title', 'type' => 'string', 'min' => '1','require' => true, 'desc' => '帖子标题'),
                'text' => array('name' => 'text', 'type' => 'string', 'min' => '1','require' => true, 'desc' => '帖子内容'),
                'p_image' => array('name' => 'p_image','type' => 'array','require' => false,'desc'=>'帖子图片',),
                'post_image_id'  => array('name' => 'post_image_id','type' => 'int','require' => false,'desc'=>'帖子图片id'),
            ),
            'stickyPost' => array(
                    'user_id'    => array(
                            'name'    => 'user_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '用户id',
                    ),
                    'post_id'    => array(
                            'name'    => 'post_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '帖子id',
                    ),
            ),

            'unStickyPost' => array(
                    'user_id'    => array(
                            'name'    => 'user_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '用户id',
                    ),
                    'post_id'    => array(
                            'name'    => 'post_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '帖子id',
                    ),
            ),

            'deletePost' => array(
                    'user_id'    => array(
                            'name'    => 'user_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '用户id',
                    ),
                    'post_id'    => array(
                            'name'    => 'post_id',
                            'type'    => 'int',
                            'require' => true,
                            'desc'    => '帖子id',
                    ),
            ),
            'lockPost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                ),
                'post_id'=>array(
                    'name'=>'post_id',
                    'type'=>'int',
                    'require'=>true,
                    'desc'=>'帖子ID',
                    ),
                ),
            'unlockPost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                ),
                'post_id'=>array(
                    'name'=>'post_id',
                    'type'=>'int',
                    'require'=>true,
                    'desc'=>'帖子ID',
                    ),
                ),
            'collectPost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                ),
                'post_id'=>array(
                    'name'=>'post_id',
                    'type'=>'int',
                    'require'=>true,
                    'desc'=>'帖子ID',
                    'min'=>'1',
                    ),
                ),
            'getCollectPost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                    ),
                'page' =>array(
                    'name' => 'pn',
                    'type' => 'int',
                    'desc' => '第几页',
                    'default' => '1'
                    ),
                ),
            'deleteCollectPost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                    ),
                'post_id' =>array(
                    'name' => 'post_id',
                    'type' => 'int',
                    'require'=>true,
                    'desc' => '帖子ID'
                    ),
                ),
            'deletePostReply'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                    ),
                'post_base_id'    => array(
                        'name'    => 'post_base_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '帖子id',
                    ),
                'floor'    => array(
                        'name'    => 'floor',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '楼层',
                    ),
                ),
            'approvePost'=>array(
                'user_id'    => array(
                        'name'    => 'user_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '用户id',
                    ),
                'post_id'    => array(
                        'name'    => 'post_id',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '帖子id',
                    ),
                'floor'    => array(
                        'name'    => 'floor',
                        'type'    => 'int',
                        'require' => false,
                        'default' => 1,
                        'desc'    => '楼层',
                    ),
                    /*
                'approved'    => array(
                        'name'    => 'approved',
                        'type'    => 'int',
                        'require' => true,
                        'desc'    => '是否点赞',
                    ),
                    */
                ),
        );
    }

    /**
     * 主页
     * @desc 主页面帖子显示
     * @return int posts.postID 帖子ID
     * @return string posts.title 标题
     * @return string posts.text 内容
     * @return date posts.createTime 发帖时间
     * @return string posts.nickname 发帖人
     * @return int posts.groupID 星球ID
     * @return int posts.lock 是否锁定
     * @return int posts.approved 是否点赞(0未点赞，1已点赞)
     * @return int posts.approvednum 点赞数
     * @return string posts.groupName 星球名称
     * @return int pageCount 总页数
     * @return int currentPage 当前页
     */
    public function getIndexPost(){
        $data   = array();

        $domain = new Domain_Post();
        $data = $domain->getIndexPost($this->page,$this->user_id);
        $data = $domain->getImageUrl($data);
        $data = $domain->deleteImageGif($data);
        $data = $domain->postImageLimit($data);
        $data = $domain->deleteHtmlPosts($data);
        $data = $domain->postTextLimit($data);

        return $data;
    }

    /**
     * 每个星球页面帖子显示
     * @desc 星球页面帖子显示
     * @return int creatorID 星球创建者ID
     * @return string creatorName 星球创建者名称
     * @return int groupID 星球ID
     * @return string groupName 星球名称
     * @return int post.digest 加精
     * @return string posts.title 标题
     * @return string posts.text 内容
     * @return date posts.createTime 发帖时间
     * @return int posts.postID 帖子ID
     * @return string posts.nickname 发帖人
     * @return int posts.sticky 是否置顶（0为未置顶，1置顶）
     * @return int pageCount 总页数
     * @return int currentPage 当前页
     * @return int identity 用户身份(01为创建者，02为成员，03非成员)
     * @return int private 是否私密(0为否，1为私密)
     */
    public function getGroupPost(){
          $data   = array();
          $domain = new Domain_Post();
          $common = new Domain_Common();
          $data['creatorID']=$domain->getCreaterID($this->groupID)['user_base_id'];
          $creatorName=$common->getCreator($this->groupID);
          $data['creatorName']=$creatorName;
          $data['groupID']=$this->groupID;
          $rs = $common->judgeGroupExist($data['groupID']);
          $data['groupName']=$common->getGroupName($this->groupID);
          $private=$common->judgeGroupPrivate($this->groupID);
          $data['private']=$private;
          $user=$common->judgeGroupUser($this->groupID,$this->userID);
          $creator=$common->judgeGroupCreator($this->groupID,$this->userID);
          $applicate=$common->judgeUserApplication($this->userID,$this->groupID);
          if(empty($rs)){
                $data['posts']='星球已关闭，不显示帖子';
                $data['pageCount']=1;
                $data['currentPage']=1;
                return $data;
          }
        if(empty($user)&&empty($creator)){
            $data['identity']='03';
           $data['posts']=array();
            if($private==1){
                if(!empty($applicate)){
                     $data['identity']='04';
                }
                $data['posts']=array();
                $data['pageCount']=1;
                $data['currentPage']=1;
                return $data;
           }
        }elseif (!empty($user)) {
            $data['identity']='02';
        }elseif (!empty($creator)) {
            $data['identity']='01';
         }
         $data =array_merge($data,$domain->getGroupPost($this->groupID,$this->page));
          $data = $domain->getImageUrl($data);
          $data = $domain->deleteImageGif($data);
          $data = $domain->postImageLimit($data);
          $data = $domain->deleteHtmlPosts($data);
         $data = $domain->postTextLimit($data);
        return $data;

    }


    /**
     * 我的星球
     * @desc 我的星球页面帖子显示
     * @return int posts.postID 帖子ID
     * @return string posts.title 标题
     * @return string posts.text 内容
     * @return date posts.createTime 发帖时间
     * @return string posts.nickname 发帖人
     * @return int posts.groupID 星球ID
     * @return string posts.groupName 星球名称
     * @return int pageCount 总页数
     * @return int currentPage 当前页
     * @return string user_name 用户名
     */
    public function getMyGroupPost(){
        $data   = array();

        $domain = new Domain_Post();
        $data = $domain->getMyGroupPost($this->userID,$this->page);
        $data = $domain->getImageUrl($data);
        $data = $domain->deleteImageGif($data);
        $data = $domain->postImageLimit($data);
        $data = $domain->deleteHtmlPosts($data);
        $data = $domain->postTextLimit($data);

        return $data;
    }
    /**
     * 帖子的内容
     * @desc 单个帖子的内容显示
     * @return int code 操作码，帖子删除为0. 正常显示为1. 私密帖子为2
     * @return int postID 帖子ID
     * @return int groupID 星球ID
     * @return string groupName 星球名称
     * @return string title 标题
     * @return string text 内容
     * @return int id 用户ID
     * @return int lock 是否锁定(0为未锁定，1为锁定)
     * @return int approved 是否点赞(0未点赞，1已点赞)
     * @return int approvednum 点赞数
     * @return string nickname 发帖人
     * @return date createTime 发帖时间
     * @return int sticky 是否置顶（0为未置顶，1置顶）
     * @return int lock 是否锁定（0为未锁定，1锁定）
     * @return boolean editRight 编辑权限(0为无权限，1有)
     * @return boolean deleteRight 删除权限(0为无权限，1有)
     * @return boolean stickyRight 置顶权限(0为无权限，1有)
     * @return boolean lockRight 锁帖权限(0为无权限，1有)
     * @return boolean collect 是否收藏(0为未，1为收藏)
     */
    public function getPostBase(){

        // $data   = array();

        $domain = new Domain_Post();
        $domain2 = new Domain_User();
        $domain3 = new Domain_Common();
        $data = $domain->getPostBase($this->postID,$this->userID);
        $groupID=$domain->getGroupId($this->postID);
        $privategroup = $domain3->judgeGroupPrivate($groupID);
        $data[0]['editRight']=0;
        $data[0]['deleteRight']=0;
        $data[0]['stickyRight']=0;
        $data[0]['lockRight']=0;
        $data[0]['code'] = 1;
        $re = $domain3->judgeGroupExist($groupID);
        $rs = $domain3->judgePostExist($this->postID);
        if(!$rs){
            unset($data);
            $data[0]['code'] = 0;
            if($re){
                $data[0]['msg'] = "帖子已被删除，不可查看！";
            }else{
                $data[0]['msg'] = "帖子所属星球已关闭，不可查看！";
            }
            return $data[0];
        }
        if($privategroup){
            if($this->userID !=null){
                $groupuser = $domain3->judgeGroupUser($groupID,$this->userID);
                $groupcreator = $domain3->judgeGroupCreator($groupID,$this->userID);
                if(empty($groupcreator)){
                    if(empty($groupuser)){
                        unset($data);
                        $data[0]['code'] = 2;
                        $data[0]['groupID'] = $groupID;
                        $data[0]['msg'] = "未加入，不可查看私密帖子！";
                    }
                }
            }else{
                unset($data);
                $data[0]['code'] = 2;
                $data[0]['groupID'] = $groupID;
                $data[0]['msg'] = "未登录，不可查看私密帖子！";
            }
        }
        if ($this->userID !=null){
            $userID=$this->userID;
            $creater= $domain2->judgeCreate($userID,$groupID);
            $poster = $domain->judgePoster($userID,$this->postID);
            $admin = $domain2->judgeAdmin($userID);
            if($poster)
            {
                $data[0]['editRight']=1;
                $data[0]['deleteRight']=1;
                $data[0]['lockRight']=1;
            }
            if($creater){
                $data[0]['deleteRight']=1;
                $data[0]['stickyRight']=1;
                $data[0]['lockRight']=1;
            }
            if($admin){
                $data[0]['deleteRight']=1;
                $data[0]['stickyRight']=1;
                $data[0]['lockRight']=1;
            }
        }
        return $data[0];
    }

    /**
     * 帖子的回复
     * @desc 单个帖子的回复内容显示
     * @return int reply.replyFloor 回复内容
     * @return string reply.text 回复内容
     * @return int reply.user_id 回帖人id
     * @return string reply.nickname 回帖人昵称
     * @return int reply.replyid 被回复人ID，为NULL代表回复楼主
     * @return string reply.replynickname 被回复人昵称，为NULL代表回复楼主
     * @return int reply.floor 帖子楼层
     * @return int approved 是否点赞(0未点赞，1已点赞)
     * @return int approvednum 点赞数
     * @return int reply.deleteRight 删除权限（1为有此权限）
     * @return date reply.createTime 回帖时间
     * @return int postID 帖子ID
     * @return int replyCount 回帖数
     * @return int pageCount 总页数
     * @return int currentPage 当前页
     */
    public function getPostReply(){

        $data   = array();

        $domain = new Domain_Post();
        $data = $domain->getPostReply($this->postID,$this->page,$this->user_id);
        $data = $domain->deleteHtmlReply($data);
        return $data;
    }
    /**
     * 帖子的回复
     * @desc 单个帖子的回复操作
     * @return int info.post_base_id 帖子ID
     * @return int info.user_base_id 发帖人ID
     * @return int info.replyid 回复人ID
     * @return string info.text 回复内容
     * @return int info.floor 回复楼层
     * @return date info.createTime 回帖时间
     */
    public function PostReply(){
        $rs = array();
        $data = array(
            'post_base_id'  => $this->post_base_id,
            'user_base_id'  => $this->user_id,
            'replyid'       => NULL,
            'text'          => $this->text,
            'floor'         => '',
            'createTime'    => '',
        );
        $domain1 = new Domain_Common();
        $domain = new Domain_Post();
        $judge =$domain1->judgePostExist($this->post_base_id);
        $lock=$domain1->judgePostLock($this->post_base_id);
        if($judge&&!$lock) {
            $rs = $domain->PostReply($data,$this->replyfloor);
        }else{
            $rs=null;
        }
        return $rs;
    }
    /**
     * 帖子的编辑
     * @desc 单个帖子的编辑操作
     * @return int code 操作码，1表示编辑成功，0表示编辑失败
     * @return int info.post_base_id 帖子ID
     * @return int info.user_base_id 编辑人ID
     * @return string info.title 编辑帖子标题
     * @return string info.text 编辑帖子内容
     * @return int info.floor 楼主楼层1
     * @return date info.createTime 编辑时间
     */
    public function editPost(){
        $rs = array();
        $data = array(
            'user_id'       => $this->user_id,
            'post_base_id'  => $this->post_base_id,
            'title'         => $this->title,
            'text'          => $this->text,
            'p_image'       => $this->p_image,
            'post_image_id'        => $this->post_image_id,
        );
        $domain = new Domain_Post();
        $rs = $domain->editPost($data);
        return $rs;
    }
    /**
     * 置顶帖子
     * @desc 帖子置顶
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function stickyPost(){
        $rs = array();
        $data = array(
                'user_id'       => $this->user_id,
                'post_id'       => $this->post_id,
        );

        $domain = new Domain_Post();
        $rs = $domain->stickyPost($data);

        return $rs;
    }

    /**
     * 取消置顶帖子
     * @desc 取消帖子置顶
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function unStickyPost(){
        $rs = array();
        $data = array(
                'user_id'       => $this->user_id,
                'post_id'       => $this->post_id,
        );

        $domain = new Domain_Post();
        $rs = $domain->unStickyPost($data);

        return $rs;
    }

    /**
     * 删除帖子
     * @desc 删除帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function deletePost(){
        $rs = array();
        $data = array(
                'user_id'       => $this->user_id,
                'post_id'       => $this->post_id,
        );

        $domain = new Domain_Post();
        $rs = $domain->deletePost($data);

        return $rs;
    }

    /**
     * 锁定帖子
     * @desc 锁定帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function lockPost(){
        $domain=new Domain_Post();
        $rs=$domain->lockPost($this->user_id,$this->post_id);
        return $rs;
    }

    /**
     * 解锁帖子
     * @desc 解锁帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function unlockPost(){
        $domain=new Domain_Post();
        $rs=$domain->unlockPost($this->user_id,$this->post_id);
        return $rs;
    }
    /**
     * 收藏帖子
     * @desc 收藏帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function collectPost(){
        $domain=new Domain_Post();
        $rs=$domain->collectPost($this->user_id,$this->post_id);
        return $rs;
    }

    /**
     * 获取收藏的帖子
     * @desc 获取收藏的帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function getCollectPost(){
        $data   = array();
        $domain = new Domain_Post();
        $data = $domain->getCollectPost($this->user_id,$this->page);
        return $data;
    }

    /**
     * 删除帖子回复
     * @desc 删除帖子回复
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function deletePostReply(){
        $data   = array();
        $domain = new Domain_Post();
        $data = $domain->deletePostReply($this->user_id,$this->post_base_id,$this->floor);
        return $data;
    }

    /**
     * 删除收藏帖子
     * @desc 删除收藏帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function deleteCollectPost(){
        $data   = array();
        $domain = new Domain_Post();
        $data = $domain->deleteCollectPost($this->user_id,$this->post_id);
        return $data;
    }

	/**
     * 点赞帖子
     * @desc 点赞帖子
     * @return int code 操作码，1表示操作成功，0表示操作失败
     * @return string re 提示信息
     */
    public function approvePost(){
        $rs = array();
        $data = array(
                'user_id'       => $this->user_id,
                'post_id'       => $this->post_id,
                'floor'         => $this->floor,
                //'approved'      => $this->approved,
        );
        $domain = new Domain_Post();
        $rs = $domain->approvePost($data);
        return $rs;
    }
}
