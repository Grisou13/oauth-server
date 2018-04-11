<template>
    <div>
        <h2>Approved</h2>
        <table>
            <thead>
            <tr>
                <!--<th>Project name</th>-->
                <th>User</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="request in requests">
                <!--<td>{{ request.project.name }}</td>-->
                <td>{{ request.user.credential }}</td>
                <td><a href="#!" @click="revokeProject(request)"><i class="material-icons">delete</i></a></td>
            </tr>
            </tbody>

        </table>
    </div>
</template>
<script>
    export default {
        name: 'approved-project-list',
        ready(){
            this.prepareComponent()
        },
        mounted(){
            this.prepareComponent()
        },
        methods: {
            prepareComponent(){
                this.fetchProjects()
            },
            revokeProject(approval){
                axios.post(`/dashboard/projects/apps/${approval.project_id}/revoke/${approval.user_id}`);
            },
            fetchProjects(){
                axios.get(`/dashboard/projects/approved/${this.project}`).then(resp => this.requests = resp.data)
                /*.then(resp => this.requests = resp.data.reduce( (acc, project) => {
              return acc.concat(project.approvals.map( a => Object.assign({}, a, {project: project})))
            }, [] ))*/
            }
        },
        data(){
            return {
                project: this.$route.params.id,
                requests : []
            }
        }
    }
</script>
