<template>
  <b-container>
      <b-row class="vh-100" align-v="center">
          <b-col cols="6" offset="3">
              <register-form></register-form>
          </b-col>
      </b-row>
  </b-container>
</template>

<script>
import RegisterForm from '../components/RegisterForm';

export default {
    components: {
        RegisterForm,
    },
}
</script>

<style>

</style>