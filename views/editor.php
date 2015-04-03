<div id="layotter" ng-controller="EditorCtrl" ng-class="{ 'layotter-loading' : data.isLoading }">
    <div id="layotter-top-buttons">
        <div id="layotter-top-buttons-left">
            <span class="layotter-button" ng-click="editOptions('post', data)" ng-show="optionsEnabled.post"><i class="fa fa-cog"></i><?php _e('Options', 'layotter'); ?></span>
            <div class="layotter-save-layout-button-wrapper" ng-if="enablePostLayouts">
                <span class="layotter-button" ng-click="saveNewLayout()"><i class="fa fa-download"></i><?php _e('Save layout', 'layotter'); ?></span>
            </div>
            <span class="layotter-button" ng-click="loadLayout()" ng-if="enablePostLayouts"><i class="fa fa-upload"></i><?php _e('Load layout', 'layotter'); ?></span>
        </div>
        <div id="layotter-top-buttons-right">
            <span class="layotter-button" toggle-templates ng-if="enableElementTemplates"><i class="fa fa-star"></i><?php _e('Element templates', 'layotter'); ?></span>
        </div>
    </div>

    <div class="layotter-add-row-button-wrapper">
        <span class="layotter-add-row-button" ng-click="addRow(-1)" ng-class="{ 'layotter-large': data.rows.length === 0 }">
            <span ng-show="data.rows.length"><i class="fa fa-plus"></i><?php _e('Add row', 'layotter'); ?></span>
            <span ng-hide="data.rows.length"><i class="fa fa-plus"></i><?php _e('Add your first row to get started', 'layotter'); ?></span>
        </span>
    </div>

    <div class="layotter-rows" ui-sortable="rowSortableOptions" ng-model="data.rows">
        <div class="layotter-row layotter-animate" ng-repeat="row in data.rows" ng-class="{ 'layotter-loading' : row.isLoading }">
            <div class="layotter-row-canvas">
                <div class="layotter-row-move">
                    <i class="fa fa-arrows-v"></i><?php _e('Move row', 'layotter'); ?>
                </div>
                <div class="layotter-row-buttons">
                    <span ng-click="deleteRow($index)" title="<?php _e('Delete row', 'layotter'); ?>"><i class="fa fa-trash-o"></i></span>
                    <span ng-click="duplicateRow($index)" title="<?php _e('Duplicate row', 'layotter'); ?>"><i class="fa fa-files-o"></i></span>
                    <span ng-click="editOptions('row', row)" ng-show="optionsEnabled.row" title="<?php _e('Row options', 'layotter'); ?>"><i class="fa fa-cog"></i></span>
                    <div class="layotter-row-select-layout" ng-if="allowedRowLayouts.length > 1">
                        <i class="fa fa-columns"></i>
                        <div class="layotter-row-select-layout-items">
                            <span class="layotter-row-layout-button" ng-repeat="colbutton in allowedRowLayouts" ng-class="{ 'layotter-row-layout-button-active': colbutton.layout === row.layout }" ng-click="setRowLayout(row, colbutton.layout)" data-layout="{{ colbutton.layout }}" title="">{{ colbutton.title }}</span>
                        </div>
                    </div>
                </div>
                <div class="layotter-cols">
                    <div class="layotter-col {{ 'layotter-col-' + getColLayout(row, $index) }}" ng-repeat="col in row.cols">
                        <div class="layotter-add-element-button-wrapper" ng-class="{ 'layotter-always-visible': col.elements.length === 0 }">
                            <span class="layotter-add-element-button" ng-click="showNewElementTypes(col.elements, -1)" title="<?php _e('Add element', 'layotter'); ?>"><i class="fa fa-plus"></i><span><?php _e('Add element', 'layotter'); ?></span></span>
                        </div>
                        <div class="layotter-elements" ui-sortable="elementSortableOptions" ng-model="col.elements">
                            <div class="layotter-element layotter-animate" ng-repeat="element in col.elements" ng-class="{ 'layotter-loading' : element.isLoading, 'layotter-highlight' : element.isHighlighted }">
                                <div class="layotter-element-canvas">
                                    <div class="layotter-element-buttons">
                                        <span class="layotter-element-button" ng-click="deleteElement(col.elements, $index)" title="<?php _e('Delete element', 'layotter'); ?>"><i class="fa fa-trash-o"></i></span>
                                        <span class="layotter-element-button" ng-hide="element.template_id !== undefined" ng-click="editElement(element)" title="<?php _e('Edit element', 'layotter'); ?>"><i class="fa fa-pencil"></i></span>
                                        <div class="layotter-element-dropdown">
                                            <i class="fa fa-caret-down"></i>
                                            <div class="layotter-element-dropdown-items">
                                                <span ng-click="editOptions('element', element)" ng-show="optionsEnabled.element"><i class="fa fa-cog"></i><?php _e('Element options', 'layotter'); ?></span>
                                                <span ng-click="duplicateElement(col.elements, $index)"><i class="fa fa-files-o"></i><?php _e('Duplicate element', 'layotter'); ?></span>
                                                <span ng-hide="element.template_id !== undefined" ng-click="saveNewTemplate(element)" ng-if="enableElementTemplates"><i class="fa fa-star"></i><?php _e('Save as template', 'layotter'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layotter-element-message" ng-show="element.template_id !== undefined && !element.template_deleted">
                                        <?php _e('This is a template.', 'layotter'); ?>
                                    </div>
                                    <div class="layotter-element-content" ng-bind-html="element.view"></div>
                                </div>
                                <div class="layotter-add-element-button-wrapper">
                                    <span class="layotter-add-element-button" ng-click="showNewElementTypes(col.elements, $index)" title="<?php _e('Add element', 'layotter'); ?>"><i class="fa fa-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layotter-add-row-button-wrapper">
                <span class="layotter-add-row-button" ng-click="addRow($index)"><i class="fa fa-plus"></i><?php _e('Add row', 'layotter'); ?></span>
            </div>
        </div>
    </div>
</div>
<div id="layotter-loading">
    <span><?php _e('Layotter loading &hellip;', 'layotter'); ?></span>
</div>
<script type="text/ng-template" id="layotter-add-element">
    <?php require dirname(__FILE__) . '/add-element.php'; ?>
</script>
<script type="text/ng-template" id="layotter-load-layout">
    <?php require dirname(__FILE__) . '/load-layout.php'; ?>
</script>
<script type="text/ng-template" id="layotter-modal-confirm">
    <?php require dirname(__FILE__) . '/confirm.php'; ?>
</script>
<script type="text/ng-template" id="layotter-modal-prompt">
    <?php require dirname(__FILE__) . '/prompt.php'; ?>
</script>