<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\base\Widget;
use yii\data\Pagination;

/**
 * LinkPager displays a list of hyperlinks that lead to different pages of target.
 *
 * LinkPager works with a [[Pagination]] object which specifies the totally number
 * of pages and the current page number.
 *
 * Note that LinkPager only generates the necessary HTML markups. In order for it
 * to look like a real pager, you should provide some CSS styles for it.
 * With the default configuration, LinkPager should look good using Twitter Bootstrap CSS framework.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LinkPager extends \yii\widgets\LinkPager
{
    public $jump;
    /**
     * @var string the label text.
     */
    public $label = '';
    
    /**
     * @var integer the defualt page size. This page size will be used when the $_GET['per-page'] is empty.
     */
    
    /**
     * @var string the name of the GET request parameter used to specify the size of the page. 
     * This will be used as the input name of the dropdown list with page size options.
     */
    public $pageSizeParam = 'per-page';
    
    /**
     * @var array the list of page sizes
     */
    public $sizes = [5=>5, 10 => 10, 15 => 15, 20 => 20, 30 => 30, 50 => 50, 100 => 100, 200 => 200,1000 => 1000];
    
    /**
     * @var string the template to be used for rendering the output.
     */
    public $template = '{list} {label}';
    
    /**
     * @var array the list of options for the drop down list.
     */
    
    /**
     * @var array the list of options for the label
     */
    public $labelOptions;
    
    /**
     * @var boolean whether to encode the label text.
     */
    public $encodeLabel = true;
    
    /**
     * Runs the widget and render the output
     */

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }
        /*
        $jump = Yii::t('app', 'page').Html::textInput('p', '', ['class'=>'input-page form-control input-sm'])
        .'&nbsp;&nbsp;页&nbsp;&nbsp;'.
        Html::button(Yii::t('app', 'confirm'),['id'=>'jump-btn','class'=>'btn btn-sm btn-default']);
        $options['class'] = 'jump-to hidden-xs';
        $buttons[] = Html::tag('li', Html::a($jump, $this->pagination->createUrl(1)), $options);
        */
        $options = [];
        if(empty($options['id'])) {
            $options['id'] = $this->id;
        }
        if($this->encodeLabel) {
            $this->label = Html::encode($this->label);
        }
        
        $perPage = $this->pagination->getPageSize();
        $this->sizes[$perPage] = $perPage;
        ksort($this->sizes);
        $listHtml = Html::dropDownList($this->pagination->pageSizeParam, $perPage, $this->sizes, ['data-href'=> $this->pagination->createUrl($this->pagination->page).'&'.$this->pagination->pageSizeParam.'=','class'=>'per-page form-control input-sm']);
        $labelHtml = Html::label($this->label, $options['id'], $this->labelOptions);
        $output = str_replace(['{list}', '{label}'], [$listHtml, Yii::t('app', 'page')], $this->template);
        $options['class'] = 'jump-to hidden-xs';
        $buttons[] = Html::tag('li', Html::a($output), $options);

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }

}
