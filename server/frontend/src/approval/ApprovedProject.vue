<template>
    <div>
      <table>
        <thead>
          <tr>
            <th>Project name</th>
            <th></th>
          </tr>
          <tbody>
            <tr v-for="request in requests">
              <td>{{ request.user.credential }} has access to {{ request.project.name }}</td>
              <td><a href="#!" @click="approveProject(request.project, request)"><i class="material-icons">delete</i></a></td>
            </tr>
          </tbody>
        </thead>
      </table>
    </div>
</template>
<script>
    export default {
        name: 'approved-projects',
        ready(){
            this.prepareCompoenent()
        },
        mounted(){
            this.prepareCompoenent()
        },
        methods: {
            prepareCompoenent(){
                this.fetchProjects()
            },
            approveProject(project, approval){
              axios.post(`/dashboard/projects/apps/${project.id}/revoke/${approval.id}`);
            }
            fetchProjects(){
              axios.get(`/dashboard/projects/apps/approved`).then(resp => this.requests = resp.data.reduce( (acc, project) => {
                return acc.concat(project.approvals.map( a => Object.assign({}, a, {project: project})))
              }, [] ))
            }
        },
        data(){
          requests : []
        }
    }
</script>
