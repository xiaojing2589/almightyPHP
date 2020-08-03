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
            $dataList = B2b2cAlbumClassModel::alias('a')
                ->field('a.*,b.store_name,(SELECT count(*) FROM ' . config('database.prefix') . 'b2b2c_album_pic WHERE aclass_id=a.aclass_id) AS pic_num')
                ->where($where)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->order('a.aclass_id DESC')
                ->paginate($data['list_rows']);

            foreach ($dataList as &$value) {
                $value['aclass_cover'] = getB2b2cImg($value['aclass_cover'], ['type' => 'aclass_cover']);
            }

            // 设置表格数据
            $view->setRowList($dataList);
        }

        // 提示
        $view->setExplanation(['相册删除后，相册内全部图片都会删除，不能恢复，请谨慎操作']);

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'a.aclass_id','condition'=>'=', 'default' => true],
            ['title' => '相册名称', 'field' => 'a.aclass_name','condition'=>'like', 'default' => false],
            ['title' => '店铺名称', 'field' => 'b.store_name','condition'=>'like', 'default' => false]
        ]);

        // 设置页面标题
        $view->setPageTitle('图片空间');

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

        // 渲染页面
        return $view->fetch();
    }

    /**
     * 删除
     * @author 仇仇天
     * @throws \Exception
     */
    public function del()
    {
        $data = $this->request->post();
        if (false !== B2b2cAlbumClassModel::del(['apic_id' => $data['apic_id']])) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 相册图片列表
     * @author 仇仇天
     * @param int $aclass_id 相册id
     * @return mixed
     */
    public function pic($aclass_id = 0)
    {
        if(empty($aclass_id))$this->error('参数错误',url('index'));

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];
            $where[] = ['a.aclass_id','=',$aclass_id];

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
            $order = empty($orderSort) ? 'a.apic_id DESC' : $order;

            // 数据列表
            $dataList = B2b2cAlbumPicModel::alias('a')
                ->field('a.*, b.store_name,c.aclass_name')
                ->where($where)
                ->join('b2b2c_store b', 'a.store_id = b.store_id', 'LEFT')
                ->join('b2b2c_album_class c', 'a.aclass_id = c.aclass_id', 'LEFT')
                ->order($order)
                ->paginate($data['list_rows']);

            foreach ($dataList as &$value) {
                $value['apic_cover'] = getB2b2cImg($value['apic_cover'], ['type' => 'aclass']);
            }

            // 设置表格数据
            $view->setRowList($dataList);
        }

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'a.apic_id','condition'=>'=', 'default' => true],
            ['title' => '图片名称', 'field' => 'a.apic_name','condition'=>'like', 'default' => false]
        ]);

        // 设置页面标题
        $view->setPageTitle('相册图片');

        // 设置返回地址
        $view->setReturnUrl(url('index'));

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

        // 渲染页面
        return $view->fetch();
    }

    /**
     * 删除
     * @author 仇仇天
     * @throws \Exception
     */
    public function picdel()
    {
        $data = $this->request->post();
        if (false !== B2b2cAlbumPicModel::del(['apic_id' => $data['apic_id']])) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
