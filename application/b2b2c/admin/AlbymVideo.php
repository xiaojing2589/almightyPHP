<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;

use app\b2b2c\model\B2b2cVideoAlbum as B2b2cVideoAlbumModel;
use app\b2b2c\model\B2b2cVideoAlbumClass as B2b2cVideoAlbumClassModel;

/**
 * 视频
 * Class Advert
 * @package app\b2b2c\admin
 */
class AlbymVideo extends Admin
{
    /**
     * 列表
     * @param int $id
     * @param int $pid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 仇仇天
     */
    public function index()
    {
        $view = ZBuilder::make('tables');  // 初始化 表格

        if ($this->request->isAjax()) {

            // 筛选参数
            $search_field = input('param.searchField/s', '', 'trim'); // 关键词搜索字段名
            $keyword      = input('param.searchKeyword/s', '', 'trim'); // 搜索关键词

            $map = [];// 筛选参数设置

            if ($search_field == 'video_class_id' && $keyword !== '') $map[] = ['a.video_class_id', '=', $keyword];
            if ($search_field == 'video_class_name' && $keyword !== '') $map[] = ['a.video_class_name', 'like', "%" . $keyword . "%"];  // 普通搜索筛选
            if ($search_field == 'store_name' && $keyword !== '') $map[] = ['b.store_name', 'like', "%" . $keyword . "%"];  // 普通搜索筛选

            $list_rows = input('list_rows'); // 每页显示多少条
            $data_list = B2b2cVideoAlbumClassModel::alias('a')
                ->field('
                a.*,
                b.store_name,
                (SELECT count(*) FROM ' . config('database.prefix') . 'b2b2c_video_album WHERE video_class_id=a.video_class_id) AS video_num
                ')
                ->where($map)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->order('a.video_class_id DESC')
                ->paginate($list_rows);  // 数据列表
            $view->setRowList($data_list);// 设置表格数据
        }

        $view->setExplanation(['媒体库删除后，媒体库内全部视频都会删除，不能恢复，请谨慎操作']); // 提示
        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'video_class_id', 'default' => true],
            ['title' => '空间名称', 'field' => 'video_class_name', 'default' => false],
            ['title' => '店铺名称', 'field' => 'store_name', 'default' => false]
        ]);
        $view->setPageTitle('视频空间'); // 设置页面标题
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
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();
        if (false !== B2b2cVideoAlbumClassModel::del(['video_class_id' => $data['video_class_id']])) {
            action_log('b2b2c.b2b2c_goods_album_video_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 空间视频列表
     * @author 仇仇天
     */
    public function videos($video_class_id = 0)
    {
        $view = ZBuilder::make('tables');  // 初始化 表格
        if ($this->request->isAjax()) {

            // 筛选参数
            $search_field = input('param.searchField/s', '', 'trim'); // 关键词搜索字段名
            $keyword      = input('param.searchKeyword/s', '', 'trim'); // 搜索关键词

            $map   = [];// 筛选参数设置
            $map[] = ['a.video_class_id ', '=', $video_class_id];
            if ($search_field == 'video_id' && $keyword !== '') $map[] = ['a.video_id', '=', $keyword];
            if ($search_field == 'video_name' && $keyword !== '') $map[] = ['a.video_name', 'like', "%" . $keyword . "%"];

            // 排序
            $orderSort = input('sort/s', '', 'trim'); //  排序字段
            $orderMode = input('order/s', '', 'trim'); // 排序方式
            if ($orderSort == 'video_size') $order = 'a.video_size' . ' ' . $orderMode;
            $order     = empty($order) ? 'a.video_id DESC' : $order;
            $list_rows = input('list_rows'); // 每页显示多少条
            $data_list = B2b2cVideoAlbumModel::alias('a')
                ->field('
                a.*,
                b.store_name,
                c.video_class_name
                ')
                ->where($map)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->join('b2b2c_video_album_class c', 'a.video_class_id = c.video_class_id', 'LEFT')
                ->order($order)
                ->paginate($list_rows);  // 数据列表
            foreach ($data_list as &$value) {
                $value['video_cover'] = getB2b2cImg($value['video_cover'], ['type' => 'video']);
            }
            $view->setRowList($data_list);// 设置表格数据
        }
        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'video_id', 'default' => true],
            ['title' => '视频名称', 'field' => 'video_name', 'default' => false]
        ]);
        $view->setPageTitle('空间视频'); // 设置页面标题
        $view->setReturnUrl(url('index')); // 设置返回地址
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
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function videosDel()
    {
        $data = $this->request->post();
        if (false !== B2b2cVideoAlbumModel::del(['video_id' => $data['video_id']])) {
            action_log('b2b2c.b2b2c_goods_album_video_videos_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
