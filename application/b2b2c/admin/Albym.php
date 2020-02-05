<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;

use app\b2b2c\model\B2b2cAlbumPic as B2b2cAlbumPicModel;
use app\b2b2c\model\B2b2cAlbumClass as B2b2cAlbumClassModel;

/**
 * 相册
 * Class Advert
 * @package app\b2b2c\admin
 */
class Albym extends Admin
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

            if ($search_field == 'aclass_id' && $keyword !== '') $map[] = ['a.aclass_id', '=', $keyword];
            if ($search_field == 'aclass_name' && $keyword !== '') $map[] = ['a.aclass_name', 'like', "%" . $keyword . "%"];  // 普通搜索筛选
            if ($search_field == 'store_name' && $keyword !== '') $map[] = ['b.store_name', 'like', "%" . $keyword . "%"];  // 普通搜索筛选

            $list_rows = input('list_rows'); // 每页显示多少条
            $data_list = B2b2cAlbumClassModel::alias('a')
                ->field('a.*,b.store_name,(SELECT count(*) FROM ' . config('database.prefix') . 'b2b2c_album_pic WHERE aclass_id=a.aclass_id) AS pic_num')
                ->where($map)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->order('a.aclass_id DESC')
                ->paginate($list_rows);  // 数据列表
            foreach ($data_list as &$value) {
                $value['aclass_cover'] = getB2b2cImg($value['aclass_cover'], ['type' => 'aclass_cover']);
            }
            $view->setRowList($data_list);// 设置表格数据
        }

        $view->setExplanation(['相册删除后，相册内全部图片都会删除，不能恢复，请谨慎操作']); // 提示
        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'aclass_id', 'default' => true],
            ['title' => '相册名称', 'field' => 'aclass_name', 'default' => false],
            ['title' => '店铺名称', 'field' => 'store_name', 'default' => false]
        ]);
        $view->setPageTitle('图片空间'); // 设置页面标题
        // 设置列
        $view->setColumn([
            [
                'field' => 'aclass_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field' => 'aclass_name',
                'title' => '相册名称',
                'align' => 'center'
            ],
            [
                'field' => 'store_name',
                'title' => '所属店铺',
                'align' => 'center'
            ],
            [
                'field'     => 'aclass_cover',
                'title'     => '店铺封面',
                'align'     => 'center',
                'show_type' => 'avatar_image',
            ],
            [
                'field' => 'pic_num',
                'title' => '图片数量',
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
                        'query_data' => '{"field":["apic_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '查看图片',
                        'ico'        => 'fa fa-eye',
                        'class'      => 'btn btn-xs btn-success',
                        'url'        => url('pic'),
                        'query_data' => '{"field":["aclass_id"]}',
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
        if (false !== B2b2cAlbumClassModel::del(['apic_id' => $data['apic_id']])) {
            action_log('b2b2c.b2b2c_goods_class_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 相册图片列表
     * @author 仇仇天
     */
    public function pic($aclass_id = 0)
    {
        $view = ZBuilder::make('tables');  // 初始化 表格
        if ($this->request->isAjax()) {

            // 筛选参数
            $search_field = input('param.searchField/s', '', 'trim'); // 关键词搜索字段名
            $keyword      = input('param.searchKeyword/s', '', 'trim'); // 搜索关键词

            $map   = [];// 筛选参数设置
            $map[] = ['a.aclass_id ', '=', $aclass_id];
            if ($search_field == 'apic_id' && $keyword !== '') $map[] = ['a.apic_id', '=', $keyword];
            if ($search_field == 'apic_name' && $keyword !== '') $map[] = ['a.apic_name', 'like', "%" . $keyword . "%"];

            // 排序
            $orderSort = input('sort/s', '', 'trim'); //  排序字段
            $orderMode = input('order/s', '', 'trim'); // 排序方式
            if ($orderSort == 'apic_size') $order = 'a.apic_size' . ' ' . $orderMode;
            $order     = empty($order) ? 'a.apic_id DESC' : $order;
            $list_rows = input('list_rows'); // 每页显示多少条
            $data_list = B2b2cAlbumPicModel::alias('a')
                ->field('
                a.*,
                b.store_name,
                c.aclass_name
                ')
                ->where($map)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->join('b2b2c_album_class c', 'a.aclass_id = c.aclass_id', 'LEFT')
                ->order($order)
                ->paginate($list_rows);  // 数据列表
            foreach ($data_list as &$value) {
                $value['apic_cover'] = getB2b2cImg($value['apic_cover'], ['type' => 'aclass']);
            }
            $view->setRowList($data_list);// 设置表格数据
        }
        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'aclass_id', 'default' => true],
            ['title' => '图片名称', 'field' => 'aclass_name', 'default' => false]
        ]);
        $view->setPageTitle('相册图片'); // 设置页面标题
        $view->setReturnUrl(url('index')); // 设置返回地址
        // 设置列
        $view->setColumn([
            [
                'field' => 'apic_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field'     => 'apic_cover',
                'title'     => '图片',
                'align'     => 'center',
                'show_type' => 'avatar_image',
            ],
            [
                'field' => 'apic_name',
                'title' => '图片名称',
                'align' => 'center'
            ],
            [
                'field' => 'aclass_name',
                'title' => '所属相册',
                'align' => 'center'
            ],
            [
                'field' => 'store_name',
                'title' => '所属店铺',
                'align' => 'center'
            ],
            [
                'field' => 'apic_tag',
                'title' => '图片标签',
                'align' => 'center'
            ],
            [
                'field'     => 'apic_size',
                'title'     => '图片大小',
                'align'     => 'center',
                'sortable'  => true,
                'show_type' => 'byte'

            ],
            [
                'field' => 'apic_spec',
                'title' => '图片规格',
                'align' => 'center'
            ],
            [
                'field'     => 'upload_time',
                'title'     => '图片上传时间',
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
                        'url'        => url('del'),
                        'query_data' => '{"field":["apic_id"]}',
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
    public function picdel()
    {
        $data = $this->request->post();
        if (false !== B2b2cAlbumPicModel::del(['apic_id' => $data['apic_id']])) {
            action_log('b2b2c.b2b2c_goods_album_pic_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
