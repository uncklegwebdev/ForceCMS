<?php

namespace Core\Model\Admin\Blog;

class BlogAuthor extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_author';

    // soft delete constants
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

}