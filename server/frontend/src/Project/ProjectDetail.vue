<template>
<div>
  <div v-if="this.project">
    <button @click="goBack"><i class="material-icons">keyboard_arrow_left</i></button>
    <a href="#" class="btn edit"  @click="showProjectEditForm(project)"><i class="material-icons" >edit</i></a>

    <div class="row">
      <div class="col s2">
          <p class="grey-text">Name of the project</p>
      </div>
      <div class="col s10">
        <p>{{this.project.name}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col s2">
        <p class="grey-text">Description</p>
      </div>
      <div class="col s10">
        <p>{{this.project.description}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col s2">
        <p class="grey-text">Url</p>
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
  <!-- Edit form -->
  <div class="modal fade" id="modal-edit-project" tabindex="-1" role="dialog">
    <div class="modal-content">
      <h4 class="modal-title">
        Edit an api
      </h4>
      <!-- Form Errors -->
      <div class="alert alert-danger" v-if="editForm.errors.length > 0">
        <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
        <br>
        <ul>
          <li v-for="error in editForm.errors">
            {{ error }}
          </li>
        </ul>
      </div>

      <!-- Edit Project Form -->
      <form role="form">
        <!-- Name -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label">Name</label>

          <div class="col-md-9">
            <input id="edit-project-name" type="text" class="form-control"
                   @keyup.enter="update" v-model="editForm.name">

            <span class="form-text text-muted">
                                        Name of the api.
                                    </span>
          </div>
        </div>
        <!-- Url -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label">Url</label>

          <div class="col-md-9">
            <input type="text" class="form-control"
                   @keyup.enter="update" v-model="editForm.url">

            <span class="form-text text-muted">Url of the api.</span>
          </div>
        </div>

        <!-- Description -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label">Description</label>

          <div class="col-md-9">
            <input type="text" class="form-control" name="description"
                   @keyup.enter="update" v-model="editForm.description">

            <span class="form-text text-muted">Why, and for what will this api be used for.</span>
          </div>
        </div>
      </form>
    </div>
    <!-- Modal Actions -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal('#modal-edit-project')">Close</button>

      <button type="button" class="btn btn-primary" @click="update">
        Save Changes
      </button>
    </div>

  </div>
</div>

</template>
<script>
  import ScopesTable from "./ScopeTable";

  export default {
    components: {ScopesTable},
    methods: {
      closeModal(node){
          M.Modal.getInstance($(node)).close()
      },
      showProjectEditForm(project) {
          this.editForm.id = project.id;
          this.editForm.name = project.name;
          this.editForm.url = project.url;
          this.editForm.description = project.description;

          M.Modal.getInstance($('#modal-edit-project')).open();
      },
      goBack () {
          return this.$router.back();
        /*window.history.length > 1
          ? this.$router.go(-1)
          : this.$router.push('/')*/
      },
      prepareComponent(){
        this.get()
        M.Modal.init($("#modal-edit-project"),{
            onOpenEnd: function(){
                $('#edit-project-name').focus();
            }
        })
      },
      update(){
          return axios["put"](`/dashboard/projects/${this.project.id}`, this.editForm).then(resp => {

              this.editForm.name = ''
              this.editForm.description = ''
              this.editForm.url = ''
              this.editForm.errors = []

              const modal  = "#modal-edit-project"
              if(modal)
                  M.Modal.getInstance($(modal)).close()
              this.get()

          }).catch(error => {
              console.error(error)
              if (typeof error.response.data === 'object') {
                  this.editForm.errors = _.flatten(_.toArray(error.response.data.errors));
              } else {
                  this.editForm.errors = ['Something went wrong. Please try again.'];
              }
          });
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
        project: false,
        editForm: {
            errors: [],
            id: '',
            name:'',
            url: '',
            description: ''
        },
      }
    }
  }
</script>
