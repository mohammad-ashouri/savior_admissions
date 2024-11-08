/*! Bootstrap integration for DataTables' SearchPanes
 * © SpryMedia Ltd - datatables.net/license
 */

import jQuery from 'jquery';
import DataTable from 'datatables.net-zf';
import SearchPanes from 'datatables.net-searchpanes';

// Allow reassignment of the $ variable
let $ = jQuery;

$.extend(true, DataTable.SearchPane.classes, {
    buttonGroup: 'secondary button-group',
    disabledButton: 'disabled',
    narrow: 'dtsp-narrow',
    narrowButton: 'dtsp-narrowButton',
    narrowSearch: 'dtsp-narrowSearch',
    paneButton: 'secondary button',
    pill: 'badge secondary',
    search: 'search',
    searchLabelCont: 'searchCont',
    show: 'col',
    table: 'unstriped'
});
$.extend(true, DataTable.SearchPanes.classes, {
    clearAll: 'dtsp-clearAll button secondary',
    collapseAll: 'dtsp-collapseAll button secondary',
    disabledButton: 'disabled',
    panes: 'panes dtsp-panesContainer',
    search: DataTable.SearchPane.classes.search,
    showAll: 'dtsp-showAll button secondary',
    title: 'dtsp-title'
});


export default DataTable;
