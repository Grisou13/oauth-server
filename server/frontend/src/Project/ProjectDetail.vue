<template>
<div>
  <div v-if="this.project">
    <button @click="goBack"><i class="material-icons">keyboard_arrow_left</i></button>
    <div class="row">
      <div class="col s2">
          <label>Name of the project</label>
      </div>
      <div class="col s10">
        <p>{{this.project.name}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col s2">
        <label>Description</label>
      </div>
      <div class="col s10">
        <p>{{this.project.description}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col s2">
        <label>Url</label>
      </div>
      <div class="col s10">
        <p>{{this.project.url}}</p>
      </div>
    </div>
    <div class="row">
      <scopes-table project="this.project.id"></scopes-table>
    </div>
  </div>
  <div v-else>
    <h3>Loading project info...</h3>
  </div>
</div>

</template>
<script>
  import ScopesTable from "./ScopeTable";

  export default {
    components: {ScopesTable},
    methods: {
      goBack () {
        window.history.length > 1
          ? this.$router.go(-1)
          : this.$router.push('/')
      },
      prepareComponent(){
        this.get()
      },
      get(){
        return axios.get(`/dashboard/projects/${this.$route.params.id}`).then(resp => this.project = resp.data)
      }
    },
    /**
     * Prepare the component (Vue 1.x).
     */
    ready() {
        this.prepareComponent();
    },

    /**
     * Prepare the component (Vue 2.x).
     */
    mounted() {
        this.prepareComponent();
    },
    data(){
      return {
        project: false
      }
    }
  }
</script>
