<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div>
                <h3>Personal Access Tokens<a class="btn-floating btn-large waves-effect waves-light red" @click="showCreateTokenForm"><i class="material-icons">add</i></a></h3>


                <!-- No Tokens Notice -->
                <p class="mb-0" v-if="tokens.length === 0">
                    You have not created any personal access tokens.
                </p>

                <!-- Personal Access Tokens -->
                <table class="table table-borderless mb-0" v-if="tokens.length > 0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="token in tokens">
                            <!-- Client Name -->
                            <td style="vertical-align: middle;">
                                {{ token.name }}
                            </td>

                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                <a class="action-link text-danger" @click="revoke(token)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>

        <!-- Create Token Modal -->
        <div class="modal fade" id="modal-create-token" tabindex="-1" role="dialog">
                <div class="modal-content">
                  <h4>
                      Create Token
                  </h4>
                    <!-- Form Errors -->
                    <div class="alert alert-danger" v-if="form.errors.length > 0">
                        <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                        <br>
                        <ul>
                            <li v-for="error in form.errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Create Token Form -->
                    <form role="form" @submit.prevent="store">
                        <!-- Name -->
                        <div class="row">
                          <div class="input-field col s12">
                            <input id="create-token-name" type="text" class="" name="name" v-model="form.name">
                            <label for="create-token-name">Name</label>
                          </div>
                        </div>

                        <!-- Scopes -->
                        <div class="row" v-if="scopes.length > 0">
                            <label class="col m12">Scopes</label>

                            <div class="col-md-2" v-for="scope in scopes">
                                <label>
                                    <input type="checkbox"
                                        @click="toggleScope(scope.id)"
                                        :checked="scopeIsAssigned(scope.id)">
                                    <span>{{ scope.id }}</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal Actions -->
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close btn red" @click="closeModal('modal-create-token')">Close</a>

                    <button type="button" class="modal-action modal-close btn light-blue" @click="store">
                        Create
                    </button>
                </div>
        </div>

        <!-- Access Token Modal -->
        <div class="modal fade" id="modal-access-token" tabindex="-1" role="dialog">
            <div class="modal-content">
                <h4>
                    Personal Access Token
                </h4>
                <p>
                    Here is your new personal access token. This is the only time it will be shown so don't lose it!
                    You may now use this token to make API requests.
                </p>
                <textarea id="access-token-input" class="form-control" rows="10" style="height: 250px!important">{{ accessToken }}</textarea>
            </div>
            <!-- Modal Actions -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal('#modal-access-token')">Close</button>
            </div>
        </div>
      </div>
</template>

<script>
    export default {
        name: 'personal-access-tokens',
        /*
         * The component's data.
         */
        data() {
            return {
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
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
                this.getTokens();
                this.getScopes();

                M.Modal.init($('#modal-create-token'),{
                    onOpendEnd: function(){
                      $('#create-client-name').focus();
                    }
                });
                M.Modal.init($('#modal-access-token'),{
                  onOpenEnd: function(){
                    $("#access-token-input").focus()
                    $("#access-token-input").select()
                  }
                })


            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                M.Modal.getInstance($('#modal-create-token')).open();
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                M.Modal.getInstance($('#modal-create-token')).close();

                this.accessToken = accessToken;

                M.Modal.getInstance($('#modal-access-token')).open();
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
