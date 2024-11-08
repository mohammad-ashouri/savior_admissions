/*! Bulma integration for DataTables' SearchPanes
 * © SpryMedia Ltd - datatables.net/license
 */

import jQuery from 'jquery';
import DataTable from 'datatables.net-bm';
import SearchPanes from 'datatables.net-searchpanes';

// Allow reassignment of the $ variable
let $ = jQuery;

$.extend(true, DataTable.SearchPane.classes, {
    disabledButton: 'is-disabled',
    paneButton: 'button dtsp-paneButton is-white',
    search: 'input search'
});
$.extend(true, DataTable.SearchPanes.classes, {
    clearAll: 'dtsp-clearAll button',
    collapseAll: 'dtsp-collapseAll button',
    disabledButton: 'is-disabled',
    search: DataTable.SearchPane.classes.search,
    showAll: 'dtsp-showAll button'
});


export default DataTable;
