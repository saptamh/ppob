<script id="salary-template" type="text/x-handlebars-template">
    <div class="row">
        <div class="col-lg-12">
            <br>
            <center>
                <h2>Detail Payment</h2>
                <table class="table table-responsive">
                    <tr>
                        <td>Employee</td>
                        <td>: {{ salary.data.employee.name }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {{ salary.data.employee.status }}</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>: {{ salary.data.periode }}</td>
                    </tr>
                    <tr>
                        <td>Salary</td>
                        <td>: {{ salary.data.salary }}</td>
                    </tr>
                    <tr>
                        <td>Work Day</td>
                        <td>: {{ salary.data.work_day }}</td>
                    </tr>
                    <tr>
                        <td>Overtime In Day</td>
                        <td>: {{ salary.data.over_time_day }}</td>
                    </tr>
                    <tr>
                        <td>Overtime In Hour</td>
                        <td>: {{ salary.data.over_time_hour }}</td>
                    </tr>
                    <tr>
                        <td>Meal Allowance</td>
                        <td>: {{ salary.data.meal_allowance }}</td>
                    </tr>
                    <tr>
                        <td>Bonus</td>
                        <td>: {{ salary.data.bonus }}</td>
                    </tr>
                    <tr>
                        <td>Cashbon</td>
                        <td>: {{ salary.data.cashbon }}</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>: {{ salary.data.total_salary }}</td>
                    </tr>
                </table>
            </center>
        </div>
    </div>
</script>
