<template>
  <div class="page">
    <div class="">
      <Clients />
    </div>
    <div class="">
      <PersonalAccessTokens />
    </div>
    <div class="">
      <AuthorizedClients />
    </div>

  </div>
</template>

<script>
import AuthorizedClients from "./AuthorizedClients.vue"
import Clients from "./Clients.vue"
import PersonalAccessTokens from "./PersonalAccessTokens.vue"
export default {
  name: 'dashboard',
  components: {AuthorizedClients, Clients, PersonalAccessTokens},
  data () {
    return {

    }
  }
}
</script>

<style lang="scss">

</style>
