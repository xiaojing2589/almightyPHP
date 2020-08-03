<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;

use app\b2b2c\model\B2b2cVideoAlbum as B2b2cVideoAlbumModel;
use app\b2b2c\model\B2b2cVideoAlbumClass as B2b2cVideoAlbumClassModel;

/**
 * 视频
 */
class AlbymVideo extends Admin
{
    /**
     * 列表
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])) {
                if ($data['searchCondition'] == 'like') {
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                } else {
                    $where[] = [$data['searchField'], $data['searchCondition'], $data['searchKeyword']];
                }
            }

            // 数据列表
            $data_list = B2b2cVideoAlbumClassModel::alias('a')
                ->field('
                a.*,
                b.store_name,
                (SELECT count(*) FROM ' . config('database.prefix') . 'b2b2c_video_album WHERE video_class_id=a.video_class_id) AS video_num
                ')
                ->where($where)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->order('a.video_class_id DESC')
                ->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 提示
        $view->setExplanation(['媒体库删除后，媒体库内全部视频都会删除，不能恢复，请谨慎操作']);

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'a.video_class_id','condition'=>'=', 'default' => true],
            ['title' => '空间名称', 'field' => 'a.video_class_name','condition'=>'like', 'default' => false],
            ['title' => '店铺名称', 'field' => 'b.store_name','condition'=>'like', 'default' => false]
        ]);

        // 设置页面标题
        $view->setPageTitle('视频空间');

        // 设置列
        $view->setColumn([
            [
                'field' => 'video_class_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field' => 'video_class_name',
                'title' => '空间名称',
                'align' => 'center'
            ],
            [
                'field' => 'store_name',
                'title' => '所属店铺',
                'align' => 'center'
            ],
            [
                'field' => 'video_num',
                'title' => '视频数量',
                'align' => 'center',
                'width' => 50
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 300,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["video_class_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '查看视频',
                        'ico'        => 'fa fa-eye',
                        'class'      => 'btn btn-xs btn-success',
                        'url'        => url('videos'),
                        'query_data' => '{"field":["video_class_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 删除
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();
        if (false !== B2b2cVideoAlbumClassModel::del(['video_class_id' => $data['video_class_id']])) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 空间视频列表
     * @author 仇仇天
     * @param int $video_class_id
     * @return mixed
     */
    public function videos($video_class_id = 0)
    {
        if(empty($video_class_id))$this->error('参数错误',url('index'));

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];
            $where[] = ['video_class_id','=',$video_class_id];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])) {
                if ($data['searchCondition'] == 'like') {
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                } else {
                    $where[] = [$data['searchField'], $data['searchCondition'], $data['searchKeyword']];
                }
            }

            //  排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'a.video_id DESC' : $order;

            // 数据列表
            $dataList = B2b2cVideoAlbumModel::alias('a')
                ->field('
                a.*,
                b.store_name,
                c.video_class_name
                ')
                ->where($where)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->join('b2b2c_video_album_class c', 'a.video_class_id = c.video_class_id', 'LEFT')
                ->order($order)
                ->paginate($data['list_rows']);

            foreach ($dataList as &$value) {
                $value['video_cover'] = getB2b2cImg($value['video_cover'], ['type' => 'video']);
            }

            // 设置表格数据
            $view->setRowList($dataList);
        }

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'video_id','condition'=>'=', 'default' => true],
            ['title' => '视频名称', 'field' => 'video_name','condition'=>'like', 'default' => false]
        ]);

        // 设置页面标题
        $view->setPageTitle('空间视频');

        // 设置返回地址
        $view->setReturnUrl(url('index'));

        // 设置列
        $view->setColumn([
            [
                'field' => 'video_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field'     => 'video_cover',
                'title'     => '视频',
                'align'     => 'center',
                'show_type' => 'video',

            ],
            [
                'field' => 'video_name',
                'title' => '视频名称',
                'align' => 'center'
            ],
            [
                'field' => 'video_class_name',
                'title' => '所属空间',
                'align' => 'center'
            ],
            [
                'field' => 'store_name',
                'title' => '所属店铺',
                'align' => 'center'
            ],
            [
                'field' => 'video_tag',
                'title' => '视频标签',
                'align' => 'center'
            ],
            [
                'field'     => 'video_size',
                'title'     => '视频大小',
                'align'     => 'center',
                'sortable'  => true,
                'show_type' => 'byte'

            ],
            [
                'field'     => 'upload_time',
                'title'     => '视频上传时间',
                'align'     => 'center',
                'show_type' => 'datetime'
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 300,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('videosdel'),
                        'query_data' => '{"field":["video_id"]}',
                        'query_type' => 'post'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 删除
     * @author 仇仇天
     */
    public function videosDel()
    {
        $data = $this->request->post();
        if (false !== B2b2cVideoAlbumModel::del(['video_id' => $data['video_id']])) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
