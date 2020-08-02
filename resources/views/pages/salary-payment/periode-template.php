<script id="periode-template" type="text/x-handlebars-template">
    {{#isOffice}}
    <div class="form-group">
        <label>Periode</label>
        <input type="text" readonly="true" id="periode" name="periode">
    </div>
    {{/isOffice}}
    {{#isProject}}
    <div class="form-group">
        <label>Periode</label>
        <input type="text" readonly="true" id="periode" name="periode">
    </div>
    {{/isProject}}
</script>
