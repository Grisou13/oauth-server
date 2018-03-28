<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
      <h3>OAuth Clients <a class="btn-floating btn-large waves-effect waves-light red" @click="showCreateClientForm"><i class="material-icons">add</i></a></h3>
      <!-- Current Clients -->
      <p class="mb-0" v-if="clients.length === 0">
          You have not created any OAuth clients.
      </p>

      <table class="table table-borderless mb-0" v-if="clients.length > 0">
          <thead>
              <tr>
                  <th>Client ID</th>
                  <th>Name</th>
                  <th>Secret</th>
                  <th></th>
                  <th></th>
              </tr>
          </thead>

          <tbody>
              <tr v-for="client in clients">
                  <!-- ID -->
                  <td style="vertical-align: middle;">
                      {{ client.id }}
                  </td>

                  <!-- Name -->
                  <td style="vertical-align: middle;">
                      {{ client.name }}
                  </td>

                  <!-- Secret -->
                  <td style="vertical-align: middle;">
                      <code>{{ client.secret }}</code>
                  </td>

                  <!-- Edit Button -->
                  <td style="vertical-align: middle;">
                      <a class="action-link" tabindex="-1" @click="edit(client)">
                          Edit
                      </a>
                  </td>

                  <!-- Delete Button -->
                  <td style="vertical-align: middle;">
                      <a class="action-link text-danger" @click="destroy(client)">
                          Delete
                      </a>
                  </td>
              </tr>
          </tbody>
      </table>

        <!-- Create Client Modal -->
        <div class="modal fade" id="modal-create-client" tabindex="-1" role="dialog">
            <div class="modal-content">
              <h4 class="modal-title">
                  Create Client
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

                <!-- Create Client Form -->
                <form role="form">
                    <!-- Name -->
                    <div class="row">
                      <div class="input-field col s12">
                          <label for="create-client-name">Name</label>
                          <input id="create-client-name" type="text" class="form-control"
                                                      @keyup.enter="store" v-model="createForm.name">

                          <span class="form-text text-muted">
                              Something your users will recognize and trust.
                          </span>

                      </div>
                    </div>


                    <!-- Redirect URL -->
                    <div class=" row">

                        <div class="form-group col s12">
                            <label for="redirect">Redirect URL</label>
                            <input type="text" class="form-control" name="redirect"
                                            @keyup.enter="store" v-model="createForm.redirect">

                            <span class="form-text text-muted">
                                Your application's authorization callback URL.
                            </span>
                        </div>
                    </div>
                </form>
            <!-- Modal Actions -->
            <div class="modal-footer">
                <button type="button" class="btn red" data-dismiss="modal" @click="closeModal('#modal-create-client')">Close</button>

                <button type="button" class="btn blue" @click="store">
                    Create
                </button>
            </div>
        </div>
      </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-client" tabindex="-1" role="dialog">
            <div class="modal-content">
                <h4 class="modal-title">
                    Edit Client
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

                <!-- Edit Client Form -->
                <form role="form">
                    <!-- Name -->
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="edit-client-name">Name</label>
                            <input id="edit-client-name" type="text" class="form-control"
                                                        @keyup.enter="update" v-model="editForm.name">

                            <span class="form-text text-muted">
                                Something your users will recognize and trust.
                            </span>
                        </div>
                    </div>

                    <!-- Redirect URL -->
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="redirect">Redirect URL</label>
                            <input type="text" class="form-control" name="redirect"
                                            @keyup.enter="update" v-model="editForm.redirect">

                            <span class="form-text text-muted">
                                Your application's authorization callback URL.
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal Actions -->
            <div class="modal-footer">
                <button type="button" class="btn red" data-dismiss="modal" @click="closeModal('#modal-edit-client')">Close</button>

                <button type="button" class="btn blue" @click="update">
                    Save Changes
                </button>
            </div>
          </div>
  </div>
</template>

<script>
    export default {
        name: 'clients',

        /*
         * The component's data.
         */
        data() {
            return {
                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
            };
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
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();
                M.Modal.init($("#modal-create-client"),{
                    onOpenEnd: function(){
                      $('#create-client-name').focus();
                    }
                })
                M.Modal.init($("#modal-edit-client"),{
                    onOpenEnd: function(){
                      $('#edit-client-name').focus();
                    }
                })
                /*$('#modal-create-client').modal({
                    onOpenEnd: function(){
                      $('#create-client-name').focus();
                    }
                });

                $('#modal-edit-client').modal({
                    onOpenEnd: function(){
                      $('#edit-client-name').focus();
                    }
                });*/
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
              M.Modal.getInstance($('#modal-create-client')).open()
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createForm, '#modal-create-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;
                M.Modal.getInstance($('#modal-edit-client')).open()

            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/clients/' + this.editForm.id,
                    this.editForm, '#modal-edit-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];
                        M.Modal.getInstance($(modal)).close()
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
