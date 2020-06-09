<script id="salary-template" type="text/x-handlebars-template">
    <div class="form-group">
        <label>Status</label>
        <input type="text" id="employee-status" readonly value="{{ employee.status }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Base Salary</label>
        <input type="text" id="base-salary-hide" name="salary" required value="{{ base_salary }}" class="form-control calculate-salary">
    </div>
    <div class="form-group">
        <label>Meal Allowance</label>
        <input type="text" id="meal-allowance-hide" readonly value="{{ meal_allowance }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Weekend Allowance</label>
        <input type="text" id="weekend-allowance-hide" readonly value="{{ weekend_allowance }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Work Hour</label>
        <input type="text" id="work-hour-hide" readonly value="{{ working_hour }}" class="form-control">
    </div>
</script>
