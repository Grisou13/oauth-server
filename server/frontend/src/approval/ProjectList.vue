<template>
    <div>
      <table>
        <thead>
          <tr>
            <th>Project name</th>
            <th></th>
          </tr>
          <tbody>
            <tr v-for="project in projects">
              <td>{{ project.name }}</td>
              <td><a href="#!" @click="askForProject(project)"><i class="material-icons">add_circle_outline</i></a></td>
            </tr>
          </tbody>
        </thead>
      </table>
    </div>
</template>
<script>
    export default {
        name: 'askable-project-list',
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
            askForProject(project){
              axios.post(`/dashboard/projects/apps/${project.id}/ask`);
            }
            fetchProjects(){
              axios.get(`/dashboard/projects/apps`).then(resp => this.projects = resp.data)
            }
        },
        data(){
          projects : []
        }
    }
</script>
