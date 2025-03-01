<?php

/**
 * Class ForceCMS_Helpers_HtmlLibraries
 */
class HtmlLibraries extends Zend_View_Helper_Abstract
{
    /**
     * Self point method so we can access all other
     * functions outside the helper. We can achieve modularity in application
     * <b>
     * @uses $this->getHelper('HelperName')->methodName()
     * This is how to use the same just call method getHelper outside the class
     * And continue in chain with methods.
     * </b>
     * @return $this
     */
    public function htmlLibraries()
    {
        return $this;
    }

    public function _getLibraries(array $libraries)
    {
        if (is_array($libraries)) {
            $library = $this->_setLibraries($libraries);
            // Return parsed libraries
            return $library;
        } else
             // Resource is not valid
             throw new Exception('You must provide array with bla bla.');
    }

    public function _setLibraries(array $libraries)
    {
        // Zend View object inicialization
        $this->view = new Zend_View();

        // Check resource type
        foreach ($libraries as $library) {
            $libraryType = substr($library, -3);
            if ($libraryType == 'css') {
                $this->view->headLink()->appendStylesheet($this->view->baseUrl($library));
            } elseif ($libraryType == '.js') {
                $this->view->inlineScript()->appendFile($this->view->baseUrl($library));
            }
        }
    }

    public function library($index)
    {
        $libraries = [
            'datatables' => [
                '/skins/backend/global/plugins/datatables/datatables.min.css',
                '/skins/backend/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',
                '/skins/backend/global/plugins/datatables/datatables.min.js',
                '/skins/backend/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',
                '/skins/backend/pages/scripts/table-datatables-managed.min.js'
            ],
            'fileinput' => [
                '/skins/backend/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
                '/skins/backend/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'
            ],
            'sweetalert' => [
                '/skins/backend/global/plugins/bootstrap-sweetalert/sweetalert.css',
                '/skins/backend/global/plugins/bootstrap-sweetalert/sweetalert.min.js',
                '/skins/backend/pages/scripts/ui-sweetalert.min.js',
            ],
            'blog' => [
                '/skins/backend/pages/css/blog.min.css',
            ],
            'editable' => [
                '/skins/backend/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css',
                '/skins/backend/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js',
            ],
            'ckeditor' => [
                '/skins/backend/cms/js/ckeditor/ckeditor.js'
            ],
            'datetimepicker' => [
                '/skins/backend/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                '/skins/backend/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            ],
            'tagsinput' => [
                '/skins/backend/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
                '/skins/backend/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css',
                '/skins/backend/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
            ],
            'select2' => [
                '/skins/backend/global/plugins/select2/css/select2.min.css',
                '/skins/backend/global/plugins/select2/css/select2-bootstrap.min.css',
                '/skins/backend/global/plugins/select2/js/select2.full.min.js',
                '/skins/backend/pages/scripts/components-select2.min.js',
            ],
            'jqueryui' => [
                '/skins/backend/global/plugins/jquery-ui/jquery-ui.min.js',
                '/skins/backend/global/plugins/jquery-ui/jquery-ui.min.css',
            ],
            'plupload' => [
                '/skins/backend/cms/js/plupload/jquery.plupload.queue.css',
                '/skins/backend/cms/js/plupload/js/moxie.js',
                '/skins/backend/cms/js/plupload/js/plupload.full.min.js',
                '/skins/backend/cms/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js'
            ],
        ];

        return $this->_getLibraries($libraries[$index]);
    }
}