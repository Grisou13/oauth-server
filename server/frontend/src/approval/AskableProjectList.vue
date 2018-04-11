<template>
    <div>
      <h2>List of available apis</h2>
      <table>
        <thead>
          <tr>
            <th>Project name</th>
            <th></th>
          </tr>
        </thead>
          <tbody>
            <tr v-for="project in projects">
              <td>{{ project.name }}</td>
              <td><a href="#!" @click="askForProject(project)"><i class="material-icons">add_circle_outline</i></a></td>
            </tr>
          </tbody>

      </table>
    </div>
</template>
<script>
    export default {
        name: 'askable-project-list',
        data(){
          return {
            projects : []
          }
        },
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
            askForProject(project){
              axios.post(`/dashboard/projects/apps/${project.id}/ask`);
              this.fetchProjects()
            },
            fetchProjects(){
              axios.get(`/dashboard/projects/apps`).then(resp => this.projects = resp.data)
            }
        },

    }
</script>
