<?php

namespace Core\Model\Admin\Blog;

class BlogCategory extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_category';

    // soft delete constants
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    /**
     * Return array-object of all categories from database
     * @return Array-Object
     */
    public function getAll() {
        $select = $this->select();
        $select->where('status = ?', self::STATUS_VISIBLE)
               ->where('is_deleted = ?', self::IS_ACTIVE);

        $categories = $this->fetchAll($select);
        return $categories;
    }

    /**
     * If category exist return row-object else return null
     * @param int $categoryId
     * @return Object\Zend_Db_Table_Row
     */
    public function getCategoryById($categoryId) {
        $select = $this->select();
        $select->where('id = (?)', $categoryId);

        $row = $this->fetchRow($select);
        if ($row instanceof Zend_Db_Table_Row) {
            return $row;
        } else
            return;
    }

    /**
     * @param array $categoryData
     * @return object of newly created category
     */
    public function addCategory($categoryData) {
        $category = $this->insert($categoryData);
        return $category;
    }

    /**
     * @param int $categoryId
     * @param array $categoryDate
     */
    public function editCategory($categoryId, $categoryData) {
        if (isset($categoryData['id'])) {
            unset($categoryData['id']);
        }
        $this->update($categoryData, 'id = ' . $categoryId);
    }

    /**
     * If category with given id is found do soft-delete else throw Exception
     * @param int $categoryId
     * @return Row Delete|Exception
     */
    public function deleteCategory($categoryId) {
        $select = $this->select();
        $select->where('id = (?)', $categoryId);
        $row = $this->fetchRow($select);

        if ($row) {
            $this->update([
                'deleted' => self::IS_DELETED
            ], 'id = ' . $categoryId);
        } else
            return new Exception('No category found for given id;');
    }
}