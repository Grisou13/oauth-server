<template>
<div>
    <a class="action-link" tabindex="-1" @click="showScopeCreateForm">
        Add new scope
    </a>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody v-if="scopes.length > 0">
            <tr  v-for="scope in scopes">
                <td class="name">{{ scope.name }}</td>
                <td class="description">{{ scope.description }}</td>
                <td><a href="#" class="btn edit"><i class="material-icons" @click="showScopeEditForm(scope)">edit</i></a></td>
                <td><a href="#" class="btn delete"><i class="material-icons" @click="destroy(scope)">delete</i></a></td>
            </tr>
        </tbody>
    </table>
    <!-- Edit form -->
    <div class="modal fade" id="modal-create-scope" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Edit Scope
                    </h4>

                </div>

                <div class="modal-body">
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

                    <!-- Edit Scope Form -->
                    <form role="form">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>

                            <div class="col-md-9">
                                <input id="create-scope-name" type="text" class="form-control"
                                       @keyup.enter="create" v-model="createForm.name">

                                <span class="form-text text-muted">
                                        Name of the scope (technical name).
                                    </span>
                            </div>
                        </div>

                        <!-- Redirect URL -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>

                            <div class="col-md-9">
                                <input type="text" class="form-control" name="description"
                                       @keyup.enter="create" v-model="createForm.description">

                                <span class="form-text text-muted">
                                        Why, and for what will this scope be used for.
                                    </span>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" @click="create">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit form -->
    <div class="modal fade" id="modal-edit-scope" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Edit Scope
                    </h4>

                </div>

                <div class="modal-body">
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

                    <!-- Edit Scope Form -->
                    <form role="form">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>

                            <div class="col-md-9">
                                <input id="edit-scope-name" type="text" class="form-control"
                                       @keyup.enter="update" v-model="editForm.name">

                                <span class="form-text text-muted">
                                        Name of the scope (technical name).
                                    </span>
                            </div>
                        </div>

                        <!-- Redirect URL -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>

                            <div class="col-md-9">
                                <input type="text" class="form-control" name="description"
                                       @keyup.enter="update" v-model="editForm.description">

                                <span class="form-text text-muted">
                                        Why, and for what will this scope be used for.
                                    </span>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" @click="update">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        name: 'scopes-table',
        data () {
            return {
                project: this.$route.params.id,
                scopes: [],
                //project: window.project || this.project,
                selectedScope: null,
                editForm: {
                    errors: [],
                    id: '',
                    name:'',
                    description: ''
                },
                createForm: {
                    errors: [],
                    name:'',
                    description: ''
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
            prepareComponent() {
                this.getScopes();
                M.Modal.init($('#modal-create-scope'), {
                  onOpenEnd: function(){
                    $('#create-scope-name').focus();
                  }
                })
                M.Modal.init($('#modal-edit-scope'), {
                  onOpenEnd: function(){
                    $('#edit-scope-name').focus();
                  }
                })

            },
            call(method, url = '', form = null, modal = false){
              console.log(url)
              console.log(`/projects/${url}`)
              console.log(this.project)
              return axios[method](`/dashboard/projects/${this.project}/scopes/${url}`, form).then(resp => {
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
            getScopes(){
                axios.get(`/dashboard/projects/${this.project}/scopes`).then(resp => this.scopes = resp.data)
            },
            update(){
                this.call("patch",this.editForm.id, this.editForm, "#modal-edit-scope").then(resp => {
                    this.getScopes()
                })
            },
            create(){
                this.call("post",'', this.createForm,"#modal-create-scope").then(resp => {
                    this.scopes.push(resp.data)
                })
            },
            showScopeEditForm(scope) {
                this.editForm.id = scope.id;
                this.editForm.name = scope.name;
                this.editForm.description = scope.description;
                M.Modal.getInstance($('#modal-edit-scope')).open()

            },
            showScopeCreateForm(){
                M.Modal.getInstance($('#modal-create-scope')).open()
            },
            destroy(scope){
                this.call("delete", scope.id).then(resp => {
                    this.getScopes()
                })
            }
        }
    }
</script>
