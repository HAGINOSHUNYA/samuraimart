<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('price', __('Price'))->sortable();
        //$grid->column('category_id', __('Category id'));
        $grid->column('category.name', __('Category Name'));//idではなくnameをカラム
        $grid->column('image', __('Image'))->image();//CRUD画面で商品画像の設定や表示ができるようになります。
        $grid->column('recommend_flag', __('Recommend Flag'));//おすすめ商品
        $grid->column('carriage_flag', __('Carriage Flag'));//送料フラグの表示
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();
        //$grid->column('image', __('Image'));

        $grid->filter(function($filter) {//フィルター
            $filter->like('name', '商品名');
            $filter->like('description', '商品説明');
            $filter->between('price', '金');
            $filter->in('category_id', 'カテゴリー')->multipleSelect(Category::all()->pluck('name', 'id'));
            $filter->equal('recommend_flag', 'おすすめフラグ')->select(['0' => 'false', '1' => 'true']);
            $filter->equal('carriage_flag', '送料フラグ')->select(['0' => 'false', '1' => 'true']);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)//product　id
    {
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        //$show->field('category_id', __('Category id'));
        $show->field('category.name', __('Category Name'));//
        $show->field('image', __('Image'))->image();//CRUD画面で商品画像の設定や表示ができるようになります。
        $show->field('recommend_flag', __('Recommend Flag'));//おすすめ商品
        $show->field('carriage_flag', __('Carriage Flag'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        //$show->field('image', __('Image'));

       

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('name', __('Name'));
        $form->textarea('description', __('Description'));
        $form->number('price', __('Price'));
        //$form->number('category_id', __('Category id'));
        $form->select('category_id', __('Category Name'))->options(Category::all()->pluck('name', 'id'));
        //存在するカテゴリー名から選択できるようにしています。
        $form->image('image', __('Image'));
        $form->switch('recommend_flag', __('Recommend Flag'));//おすすめ商品
        $form->switch('carriage_flag', __('Carriage Flag'));

        return $form;
    }
}
