<script id="project-template" type="text/x-handlebars-template">
    <div class="form-group">
        <label>Project</label>
        <select name="project_id" class="form-control" required>
                {{#each projects.data}}
                    <option value="{{this.id}}" {{isSelected this.id}}>{{this.name}}</option>
                {{/each}}
        </select>
    </div>
</script>
