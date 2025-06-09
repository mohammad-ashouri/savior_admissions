<div id="viewerContent">
    <script>
        var report = new Stimulsoft.Report.StiReport();
        report.loadFile("{{ asset('reports/TuitionCardFa.mrt') }}");

        //Pass value to variable
        // var variable = report.dictionary.variables.getByName("shomare");
        // variable.value = "Hello from JavaScript!";


        // Assigning a report to the Viewer:
        viewer.report = report;

        // After loading the HTML page, display the visual part of the Viewer in the specified container.
        function onLoad() {
            viewer.renderHtml("viewerContent");
        }
    </script>
</div>
