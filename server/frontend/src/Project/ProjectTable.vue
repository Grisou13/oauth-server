<template>
<div>
    <a class="action-link" tabindex="-1" @click="showProjectCreateForm">
        Add new project
    </a>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>url</th>
            <th>Description</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody v-if="projects.length > 0">
            <tr  v-for="project in projects" class="clickable">
                <td class="">{{ project.name }}</td>
                <td class="">{{ project.url }}</td>
                <td class="">{{ project.description }}</td>
                <td><a href="#" class="btn edit"  @click="showProjectEditForm(project)"><i class="material-icons" >edit</i></a></td>
                <td><a href="#" class="btn show"  @click="openProject(project)"><i class="material-icons">remove_red_eye</i></a></td>
                <td><a href="#" class="btn delete"><i class="material-icons" @click="destroy(project)">delete</i></a></td>
            </tr>
        </tbody>
    </table>
    <!-- Create form -->
    <div class="modal fade" id="modal-create-project" tabindex="-1" role="dialog">
          <div class="modal-content">
              <h4 class="modal-title">
                  Create project
              </h4>
              <!-- Form Errors -->
              <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                  <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                  <br>
                  <ul>
                      <li v-for="error in createForm.errors">
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
                          <input id="create-project-name" type="text" class="form-control"
                                 @keyup.enter="create" v-model="createForm.name">

                          <span class="form-text text-muted">
                                  Name of the api.
                              </span>
                      </div>
                  </div>

                  <!-- Url -->
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label">Url</label>

                      <div class="col-md-9">
                          <input  type="text" class="form-control"
                                 @keyup.enter="create" v-model="createForm.url">

                          <span class="form-text text-muted">
                                  Url of the api.
                              </span>
                      </div>
                  </div>

                  <!-- Description -->
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label">Description</label>

                      <div class="col-md-9">
                          <input type="text" class="form-control" name="description"
                                 @keyup.enter="create" v-model="createForm.description">

                          <span class="form-text text-muted">
                                  Why, and for what will this api be used for.
                              </span>
                      </div>
                  </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal('#modal-create-project')">Close</button>

                <button type="button" class="btn btn-primary" @click="create">
                    Save Changes
                </button>
            </div>
    </div>
    <!-- Edit form -->
    <div class="modal fade" id="modal-edit-project" tabindex="-1" role="dialog">
            <div class="modal-content">
                    <h4 class="modal-title">
                        Edit project
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

                                <span class="form-text text-muted">
                                        Url of the api.
                                    </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>

                            <div class="col-md-9">
                                <input type="text" class="form-control" name="description"
                                       @keyup.enter="update" v-model="editForm.description">

                                <span class="form-text text-muted">
                                        Why, and for what will this api be used for.
                                    </span>
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
        name: 'projects-table',
        props: ["project"],
        modals: {
          edit: null,
          create: null
        },
        data () {
            return {
                projects: [],
                //project: window.project || this.project,
                editForm: {
                    errors: [],
                    id: '',
                    name:'',
                    url: '',
                    description: ''
                },
                createForm: {
                    errors: [],
                    name:'',
                    description: '',
                    url: ''
                }
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
        methods: {
            closeModal(node){
              M.Modal.getInstance($(node)).close()
            },
            prepareComponent() {
                this.getProjects();
                M.Modal.init($("#modal-create-project"),{
                    onOpenEnd: function(){
                      $('#create-project-name').focus();
                    }
                })
                M.Modal.init($("#modal-edit-project"),{
                    onOpenEnd: function(){
                      $('#edit-project-name').focus();
                    }
                })

            },
            call(method, url = '', form = {}, modal = null){
                console.log(url)

                return axios[method](`/dashboard/projects/${url}`, form).then(resp => {
                    form.name = ''
                    form.description = ''
                    form.errors = []
                    if(modal)
                      M.Modal.getInstance($(modal)).close()
                    return resp.data

                }).catch(error => {
                  console.error(error)
                    if (typeof error.response.data === 'object') {
                        form.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            openProject(project){
              this.$router.push('/projects/'+project.id)
            },
            getProjects(){
                return axios.get("/dashboard/projects").then(resp => this.projects = resp.data)
            },
            update(){
                this.call("patch",this.editForm.id, this.editForm, "#modal-edit-project").then(resp => {
                    this.getProjects()
                })
            },
            create(){
                this.call("post",'', this.createForm,"#modal-create-project").then(resp => {
                    this.projects.push(resp.data)
                })
            },
            showProjectEditForm(project) {
                this.editForm.id = project.id;
                this.editForm.name = project.name;
                this.editForm.description = project.description;

                M.Modal.getInstance($('#modal-edit-project')).open();
            },
            showProjectCreateForm(){
                M.Modal.getInstance($('#modal-create-project')).open();
            },
            destroy(project){
              console.log(project)
                this.call("delete", project.id).then(resp => {
                    this.getProjects()
                })
            }
        }
    }
</script>
