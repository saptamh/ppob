<script id="salary-template" type="text/x-handlebars-template">
    <div class="form-group">
        <label>Status</label>
        <input type="text" id="employee-status" readonly value="{{ salary.employee.status }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Location</label>
        <input type="text" id="employee-status" readonly value="{{ salary.employee.location }}" class="form-control">
    </div>
    {{#isdefined salary.employee.status}}
    <div class="form-group">
        <label>Project</label>
        <select name="project_id" class="form-control" required>
            {{#each projects.data}}
                <option value="{{this.id}}" {{isSelected this.id}}>{{this.name}}</option>
            {{/each}}
        </select>
    </div>
    {{/isdefined}}
    <div class="form-group">
        <label>Base Salary</label>
        <input type="text" id="base-salary-hide" name="salary" required value="{{ salary.base_salary }}" class="form-control calculate-salary">
    </div>
    <div class="form-group">
        <label>Meal Allowance</label>
        <input type="text" id="meal-allowance-hide" readonly value="{{ salary.meal_allowance }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Weekend Allowance</label>
        <input type="text" id="weekend-allowance-hide" readonly value="{{ salary.weekend_allowance }}" class="form-control">
    </div>
    <div class="form-group">
        <label>Work Hour</label>
        <input type="text" id="work-hour-hide" readonly value="{{ salary.working_hour }}" class="form-control">
    </div>

</script>
