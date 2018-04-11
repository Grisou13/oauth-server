<template>
    <div>
      <h2>Waiting <span class="new badge red">{{ this.requests.length}}</span></h2>
      <table class="table responsive-table">
        <thead>
          <tr>
            <!--<th>Project name</th>-->
            <th>User</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
          <tbody>
            <tr v-for="request in requests">
              <!--<td>{{ request.project.name }}</td>-->
              <td>{{ request.user.credential }}</td>
              <td><a href="#!" @click="destroyApproval(request)"><i class="material-icons">delete_forever</i></a></td>
              <td><a href="#!" @click="approveApproval(request)"><i class="material-icons">check_circle</i></a></td>
            </tr>
          </tbody>

      </table>
    </div>
</template>
<script>
    export default {
        name: 'pending-project-list',
        ready(){
            this.prepareComponent()
        },
        mounted(){
            this.prepareComponent()
        },
        methods: {
            prepareComponent(){
                this.fetchRequests()
            },
            destroyApproval(request){
              axios.delete(`/dashboard/projects/apps/${request.project_id}/revoke/${request.user_id}`);
              this.fetchRequests()
            },
            approveApproval(request){
              axios.post(`/dashboard/projects/apps/${request.project_id}/approve/${request.user_id}`)
                this.fetchRequests()
            },
            fetchRequests(){
              axios.get(`/dashboard/projects/pending/${this.project}`).then(resp => this.requests = resp.data)
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
