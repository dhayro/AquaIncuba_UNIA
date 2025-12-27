// Import DataTables styles
import '../scss/light/plugins/table/datatable/dt-global_style.scss';
import '../scss/light/plugins/table/datatable/custom_dt_miscellaneous.scss';
import '../scss/light/plugins/table/datatable/custom_dt_custom.scss';

// Import jQuery and DataTables
import $ from 'jquery';
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

export default {
    init() {
        // Initialization will be handled in the blade template
        console.log('DataTable Menu Permissions module loaded');
    }
};
