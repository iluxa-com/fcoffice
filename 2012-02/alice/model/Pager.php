<?php
/**
 * 分页类
 *
 * @author xianlinli@gmail.com
 * @package Alice
 */
class Pager {
    /**
     * 获取分页列表
     * @param string $urlPrefix URL前缀
     * @param int $curPage 当前页
     * @param int $totalPage 总页数
     * @param int $range 范围
     * @param string $curPageClass 当前页的Class
     * @param string $attrStr 属性字符串
     * @return string
     */
    public static function getPageList($urlPrefix, $curPage, $totalPage, $range = 5, $curPageClass = '', $attrStr = '') {
        $start = max(2, $curPage - $range);
        $end = min($totalPage - 1, $curPage + $range);
        $delta = $range * 2 - ($end - $start);
        if ($delta > 0) {
            if ($start < $range) {
                $end = min($totalPage -1, $end + $delta);
            } else {
                $start = max(2, $start - $delta);
            }
        }
        $str = '';
        $str .= self::__getPageTag($urlPrefix, 1, $curPage, $curPageClass, $attrStr);
        if ($start > 2) {
            $str .= '...';
        }
        for ($page = $start; $page <= $end; ++$page) {
            $str .= self::__getPageTag($urlPrefix, $page, $curPage, $curPageClass, $attrStr);
        }
        if ($end < $totalPage - 1) {
            $str .= '...';
        }
        if ($totalPage > 1) {
            $str .= self::__getPageTag($urlPrefix, $totalPage, $curPage, $curPageClass, $attrStr);
        }
        return $str;
    }

    /**
     * 获取页标签
     * @param string $urlPrefix URL前缀
     * @param int $page 页码
     * @param int $curPage 当前页
     * @param string $curPageClass 当前页的Class
     * @param string $attrStr 属性字符串
     * @return string
     */
    private static

    function __getPageTag($urlPrefix, $page, $curPage, $curPageClass, $attrStr) {
        if ($page == $curPage) {
            return '<a href="' . $urlPrefix . $page . '" class="' . $curPageClass . '"' . $attrStr . '>' . $page . '</a>';
        } else {
            return '<a href="' . $urlPrefix . $page . '"' . $attrStr . '>' . $page . '</a>';
        }
    }
}
?>